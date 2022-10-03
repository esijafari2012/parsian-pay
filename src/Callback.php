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
    private $confirmResult;

    /**
     * Callback constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
        $this->confirmResult=null;
    }

    /**
     * @param CallbackPay $callbackPay
     * @return array
     * @throws ParsianErrorException
     */
    protected function confirmRequest( CallbackPay $callbackPay){

        if (empty($callbackPay->getToken()) or !is_numeric($callbackPay->getStatus())) {
            throw new ParsianErrorException( -3);
        }
        if ($callbackPay->getStatus() != 0 || !$callbackPay->getRRN()) {
            throw new ParsianErrorException($callbackPay->getStatus());
        }

        if ($callbackPay->getRRN() > 0 and $callbackPay->getStatus() == 0) {

            $parameters = [
                'LoginAccount' =>  $callbackPay->getPin(),
                'Token' => $callbackPay->getToken()
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
                'Status' => $callbackPay->getStatus(),
                'Token' => $callbackPay->getToken(),
                'Message' => self::codeToMessage($callbackPay->getStatus()),
                'RRN' => $callbackPay->getRRN(),
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
        $status = $_POST["status"] ?? -4;
        $RRN = $_POST["RRN"] ?? null;



        if($status==-4||$token==null||$RRN==null){
            $this->confirmResult = new  ConfirmResult([
                'Status' => $status,
                'Token' => $token,
                'Message' => self::codeToMessage($status),
                'RRN' => $RRN,
                'CardNumberMasked' => ''
            ]);
            $this->plog->writeError($this->getResultMessage($this->confirmResult));
        }else {
            $callbackPay = new CallbackPay();
            $callbackPay->setPin($this->pin);
            $callbackPay->setStatus($status);
            $callbackPay->setRRN($RRN);
            $callbackPay->setToken($token);

            $this->plog->writeInfo($this->getRequestMessage($callbackPay));

            $this->confirmResult = null;

            try {
                $res = $this->confirmRequest($callbackPay);
                $this->confirmResult = new  ConfirmResult($res);
                $this->plog->writeInfo($this->getResultMessage($this->confirmResult));
            } catch (ParsianErrorException $e) {
                $this->confirmResult = new  ConfirmResult([
                    'Status' => $callbackPay->getStatus(),
                    'Token' => $callbackPay->getToken(),
                    'Message' => self::codeToMessage($callbackPay->getStatus()),
                    'RRN' => $callbackPay->getRRN(),
                    'CardNumberMasked' => ''
                ]);
                $this->plog->writeError($this->getResultMessage($this->confirmResult));
            } catch (\Exception $e) {
                $this->confirmResult = new  ConfirmResult([
                    'Status' => $callbackPay->getStatus(),
                    'Token' => $callbackPay->getToken(),
                    'Message' => self::codeToMessage(-1, $e->getMessage()),
                    'RRN' => $callbackPay->getRRN(),
                    'CardNumberMasked' => ''
                ]);
                $this->plog->writeError($this->getResultMessage($this->confirmResult));
            }
        }
        return $this->confirmResult;
    }
}