<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\CallbackPay;
use Esijafari2012\ParsianPay\Entities\ConfirmResult;

/**
 * Class Callback
 * @package Esijafari2012\ParsianPay
 */
class Callback  extends ParsianIPG
{

    /**
     * @var ConfirmResult|null
     */
    private $cr;

    /**
     * Callback constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
        $this->cr=null;
    }


    /**
     * @param CallbackPay $cbPay
     * @return array
     * @throws ParsianErrorException
     */
    protected function confirmRequest( CallbackPay $cbPay){

        if (empty($cbPay->getToken()) or !is_numeric($cbPay->getStatus())) {
            throw new ParsianErrorException( -3);
        }
        if ($cbPay->getStatus() != 0 || !$cbPay->getRRN()) {
            throw new ParsianErrorException($cbPay->getStatus());
        }

        if ($cbPay->getRRN() > 0 and $cbPay->getStatus() == 0) {

            $parameters = [
                'LoginAccount' =>  $cbPay->getPin(),
                'Token' => $cbPay->getToken()
            ];

            $result = $this->sendRequest($this->confirm_url,'ConfirmPayment',$parameters);

            // update database

            return  [
                'Status' => $result['ConfirmPaymentResult']['Status'] ?? -123456789,
                'Token' => $result['ConfirmPaymentResult']['Token'],
                'Message' => $result['ConfirmPaymentResult']['Message']  ,
                'RRN' => $result['ConfirmPaymentResult']['RRN'],
                'CardNumberMasked' => $result['ConfirmPaymentResult']['CardNumberMasked']
            ] ;

        } else {

            // update database
            return [
                'Status' => $cbPay->getStatus(),
                'Token' => $cbPay->getToken(),
                'Message' => self::codeToMessage($cbPay->getStatus()),
                'RRN' => $cbPay->getRRN(),
                'CardNumberMasked' => ''
            ] ;
        }
    }


    /**
     * @return ConfirmResult|null
     */
    public   function confirm()
    {
        $token = $_POST["Token"] ?? null;
        $status = $_POST["status"] ?? -1;
        $RRN = $_POST["RRN"] ?? null;

        $cbPay=new CallbackPay();
        $cbPay->setPin($this->pin);
        $cbPay->setStatus($status);
        $cbPay->setRRN($RRN);
        $cbPay->setToken($token);

        $this->cr=null;

        try {
            $res=$this->confirmRequest($cbPay);
            $this->cr=new  ConfirmResult($res);
        } catch (ParsianErrorException $e) {
            $this->cr= new  ConfirmResult([
                'Status' => $cbPay->getStatus(),
                'Token' => $cbPay->getToken(),
                'Message' => self::codeToMessage($cbPay->getStatus()),
                'RRN' => $cbPay->getRRN(),
                'CardNumberMasked' => ''
            ] );

        } catch (\Exception $e) {
            $this->cr= new  ConfirmResult([
                'Status' => $cbPay->getStatus(),
                'Token' => $cbPay->getToken(),
                'Message' => self::codeToMessage(-1,$e->getMessage()),
                'RRN' => $cbPay->getRRN(),
                'CardNumberMasked' => ''
            ]) ;
        }
        return  $this->cr;
    }
}