<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\ConfirmPaymentRequest;
use Esijafari2012\ParsianPay\Entities\ConfirmResult;
use Esijafari2012\ParsianPay\Entities\PayResult;
use Esijafari2012\ParsianPay\Entities\SalePaymentRequest;
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
     * @param string $address
     */
    public function createLogger(string $address=''){
        $this->payLogger->create($address);
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
     * @param $request
     * @return string
     */
    public function getRequestMessage($request){
        $now = new \DateTime();
        $str=$now->format('Y-m-d H:i:s');
        if($request instanceof SalePaymentRequest){
            $str=$str." >>  SalePayment Request >> " ;
            $str=$str." >>  Amount :: ".$request->getAmount();
            $str=$str." >>  OrderId :: ".$request->getOrderId();
            $str=$str." >>  CallbackUrl :: ".$request->getCallbackUrl();
        }elseif ($request instanceof ConfirmPaymentRequest){
            $str=$str." >>  ConfirmPayment Request >> " ;
            $str=$str." >>  Token :: ".$request->getToken();
            $str=$str." >>  Status :: ".$request->getStatus();
            $str=$str." >>  RRN :: ".$request->getRRN();
        }elseif ($request instanceof ReverseToken){
            $str=$str." >>  Reversal  Request >> " ;
            $str=$str." >>  Token :: ".$request->getToken();
        }

        return $str;
    }

    /**
     * @param $result
     * @return string
     */
    public function getResultMessage($result){
        $now = new \DateTime();
        $str=$now->format('Y-m-d H:i:s');
        if($result instanceof PayResult){
            $str=$str." >>  Token :: ".$result->getToken();
            $str=$str." >>  Status :: ".$result->getStatus();
            $str=$str." >>  Message :: ".$result->getMessage();
        }elseif ($result instanceof ConfirmResult){
            $str=$str." >>  Token :: ".$result->getToken();
            $str=$str." >>  Status :: ".$result->getStatus();
            $str=$str." >>  RRN :: ".$result->getRRN();
            $str=$str." >>  CardNumberMasked :: ".$result->getCardNumberMasked();
            $str=$str." >>  Message :: ".$result->getMessage();
        }
        return $str;
    }
}