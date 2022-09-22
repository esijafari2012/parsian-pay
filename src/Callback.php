<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\CallbackPay;

/**
 * Class Callback
 * @package Esijafari2012\ParsianPay
 */
class Callback  extends ParsianIPG
{

    /**
     * Callback constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
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

            $result = $this->sendRequest($this->ConfirmService,'ConfirmPayment',$parameters);

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
     * @return array
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


        try {
            $res=$this->confirmRequest($cbPay);
            return  $res;
        } catch (ParsianErrorException $e) {
            return [
                'Status' => $cbPay->getStatus(),
                'Token' => $cbPay->getToken(),
                'Message' => self::codeToMessage($cbPay->getStatus()),
                'RRN' => $cbPay->getRRN(),
                'CardNumberMasked' => ''
            ] ;

        } catch (\Exception $e) {
            return [
                'Status' => $cbPay->getStatus(),
                'Token' => $cbPay->getToken(),
                'Message' => self::codeToMessage(-1,$e->getMessage()),
                'RRN' => $cbPay->getRRN(),
                'CardNumberMasked' => ''
            ] ;
        }
    }
}