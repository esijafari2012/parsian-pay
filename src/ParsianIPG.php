<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\CallbackPay;
use Esijafari2012\ParsianPay\Entities\ConfirmResult;
use Esijafari2012\ParsianPay\Entities\PeyResult;
use Esijafari2012\ParsianPay\Entities\PinPayment;
use Esijafari2012\ParsianPay\Entities\ReverseToken;
use nusoap_client;


/**
 * Class ParsianIPG
 * @package Esijafari2012\ParsianPay
 */
class ParsianIPG extends ParsianRequest
{

    /**
     * @var PayLogger
     */
    public $payLogger;


    /**
     * ParsianIPG constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
        $this->payLogger = new PayLogger();
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws ParsianErrorException
     */
    protected function sendRequest( string $url,string $method,array $parameters=[]){
        $client = new nusoap_client($url, 'wsdl');
        $client->soap_defencoding = $this->Encoding;
        $client->decode_utf8 = FALSE;
        $err = $client->getError();
        if ($err) {
            throw new ParsianErrorException( -1,$err);
        }
        $result = $client->call($method, ['requestData' => $parameters]);
        $err = $client->getError();
        if ($err) {
            throw new ParsianErrorException( -1,$err);
        } else {
            return $result;
        }
    }

    /**
     * @param $rs
     * @return string
     */
    public function getRequestMessage($rs){
        $now = new \DateTime();
        $str=$now->format('Y-m-d H:i:s');
        if($rs instanceof PinPayment){
            $str=$str." >>  SalePayment Request >> " ;
            $str=$str." >>  Amount :: ".$rs->getAmount();
            $str=$str." >>  OrderId :: ".$rs->getOrderId();
            $str=$str." >>  CallbackUrl :: ".$rs->getCallbackUrl();
        }elseif ($rs instanceof CallbackPay){
            $str=$str." >>  ConfirmPayment Request >> " ;
            $str=$str." >>  Token :: ".$rs->getToken();
            $str=$str." >>  Status :: ".$rs->getStatus();
            $str=$str." >>  RRN :: ".$rs->getRRN();
        }elseif ($rs instanceof ReverseToken){
            $str=$str." >>  Reversal  Request >> " ;
            $str=$str." >>  Token :: ".$rs->getToken();
        }

        return $str;
    }

    /**
     * @param $rs
     * @return string
     */
    public function getResultMessage($rs){
        $now = new \DateTime();
        $str=$now->format('Y-m-d H:i:s');
        if($rs instanceof PeyResult){
            $str=$str." >>  Token :: ".$rs->getToken();
            $str=$str." >>  Status :: ".$rs->getStatus();
            $str=$str." >>  Message :: ".$rs->getMessage();
        }elseif ($rs instanceof ConfirmResult){
            $str=$str." >>  Token :: ".$rs->getToken();
            $str=$str." >>  Status :: ".$rs->getStatus();
            $str=$str." >>  RRN :: ".$rs->getRRN();
            $str=$str." >>  CardNumberMasked :: ".$rs->getCardNumberMasked();
            $str=$str." >>  Message :: ".$rs->getMessage();
        }
        return $str;
    }
}