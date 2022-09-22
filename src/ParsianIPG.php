<?php


namespace Esijafari2012\ParsianPay;

use Esijafari2012\ParsianPay\Entities\CallbackPay;
use Esijafari2012\ParsianPay\Entities\PinPayment;
use nusoap_client;


/**
 * Class ParsianIPG
 * @package Esijafari2012\ParsianPay
 */
class ParsianIPG
{
    use Errors;

    /**
     * Url of parsian gateway web service
     *
     * @var string $server_url Url for initializing payment request
     *
     */
    private static $server_url = 'https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?WSDL';

    /**
     * Url of parsian gateway web service
     *
     * @var string $confirm_url Url for confirming transaction
     *
     */
    private static $confirm_url = 'https://pec.shaparak.ir/NewIPGServices/Confirm/ConfirmService.asmx?WSDL';

    /**
     * Address of gate for redirect
     *
     * @var string
     */
    private static $gate_url = 'https://pec.shaparak.ir/NewIPG/?Token=';


    /**
     *
     * @var string
     */
    private static $Encoding           = "UTF-8";


    /**
     * @var string
     */
    public $pin;

    public function __construct(string $pin="")
    {
        $this->pin=$pin;
    }

    /**
     * @return string
     */
    public function getPin(): string
    {
        return $this->pin;
    }

    /**
     * @param string $pin
     */
    public function setPin(string $pin)
    {
        $this->pin = $pin;
    }


    /**
     * @param PinPayment $req
     * @return array
     * @throws ParsianErrorException
     */
    protected function sendPayRequest(PinPayment $req){

        $client = new nusoap_client(self::$SaleServiceAddress, 'wsdl');
        $client->soap_defencoding = self::$Encoding;
        $client->decode_utf8 = FALSE;
        $err = $client->getError();
        if ($err) {
            throw new ParsianErrorException( -1,$err);
        }

        $parameters = [
            'LoginAccount' => $req->getPin(),
            'Amount' =>  $req->getPin(),
            'OrderId' => $req->getOrderId(),
            'CallBackUrl' => $req->getCallbackUrl(),
            'AdditionalData'=> $req->getAdditionalData()
        ];

        $result = $client->call('SalePayment', ['requestData' => $parameters]);
        $err = $client->getError();
        if ($err) {
            throw new ParsianErrorException( -1,$err);
        } else {
            $Token = $result['SalePaymentRequestResult']['Token'];
            $Status = $result['SalePaymentRequestResult']['Status'];
            $Message = $result['SalePaymentRequestResult']['Message'];

            if(($Status==0)&&($Token>0)){
                return array(
                    'Status' => $Status,
                    'Token' => $Token,
                    'Message' => $Message
                );
            }else{
                throw new ParsianErrorException( $Status,$Message);
            }
        }
    }


    /**
     * @param int $orderId
     * @param int $amount
     * @param string $callbackUrl
     * @param string $additionalData
     * @return array
     */
    public function startPayment(int $orderId,int $amount,string $callbackUrl,string $additionalData) {
        $req = new PinPayment();
        $req->setAmount($amount);
        $req->setOrderId($orderId);
        $req->setPin($this->pin);
        $req->setCallbackUrl($callbackUrl);
        $req->setAdditionalData($additionalData);

        try {
            $res=$this->sendPayRequest($req);
            if(($res['Status']==0)&&($res['Token']>0)){
                $Token = $res['Token'];
                if(!empty($Token)){
                    header('LOCATION: '.self::$gate_url . $Token);
                    exit;
                }
            }
            return $res;
        } catch (ParsianErrorException $e) {
            return array(
                'Status' => $e->getCode(),
                'Token' => 0,
                'Message' => $e->getMessage()
            );
        } catch (\Exception $e) {
            return array(
                'Status' => $e->getCode(),
                'Token' => 0,
                'Message' => $e->getMessage()
            );
        }
    }


    /**
     * @param CallbackPay $cbPay
     * @return array
     * @throws ParsianErrorException
     */
    protected function callbackRequest( CallbackPay $cbPay){

        if (empty($cbPay->getToken()) or !is_numeric($cbPay->getStatus())) {
            throw new ParsianErrorException( -3);
        }
        if ($cbPay->getStatus() != 0 || !$cbPay->getRRN()) {
            throw new ParsianErrorException($cbPay->getStatus());
        }

        if ($cbPay->getRRN() > 0 and $cbPay->getStatus() == 0) {

            $client = new nusoap_client(self::$ConfirmService, 'wsdl');
            $client->soap_defencoding = self::$Encoding;
            $client->decode_utf8 = FALSE;
            $err = $client->getError();
            if ($err) {
                throw new ParsianErrorException( -1,$err);
            }

            $parameters = [
                'LoginAccount' =>  $cbPay->getPin(),
                'Token' => $cbPay->getToken()
            ];

            $result = $client->call('ConfirmPayment', ['requestData' => $parameters]);
            if ($client->fault) {
                $err = $client->getError();
                throw new ParsianErrorException( -1,$err);
            } else {

                // update database

                return  [
                    'Status' => $result['ConfirmPaymentResult']['Status'] ?? -123456789,
                    'Token' => $result['ConfirmPaymentResult']['Token'],
                    'Message' => $result['ConfirmPaymentResult']['Message']  ,
                    'RRN' => $result['ConfirmPaymentResult']['RRN'],
                    'CardNumberMasked' => $result['ConfirmPaymentResult']['CardNumberMasked']
                ] ;
            }
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
    public   function callback()
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
            $res=$this->callbackRequest($cbPay);
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