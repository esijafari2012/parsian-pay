<?php


namespace Esijafari2012\ParsianPay;

use Esijafari2012\ParsianPay\Entities\PinPayment;
use nusoap_client;


/**
 * Class ParsianIPG
 * @package Esijafari2012\ParsianPay
 */
class ParsianIPG
{

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
     * @return string
     */
    protected function sendPayRequest(PinPayment $req){
        $client = new nusoap_client(self::$SaleServiceAddress, 'wsdl');
        $client->soap_defencoding = self::$Encoding;
        $client->decode_utf8 = FALSE;
        $err = $client->getError();
        if ($err) {
            throw new ParsianErrorException($errorMessage, $status);
            return self::response(-1, $err, []);
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
            return self::response(-1, $err, []);
        } else {
            $Token = $result['SalePaymentRequestResult']['Token'];
            $Status = $result['SalePaymentRequestResult']['Status'];
            $Message = $result['SalePaymentRequestResult']['Message'];

            // insert into database

            return self::response($Status, $Message,[
                'Status' => $Status,
                'Token' => $Token,
                'Message' => $Message
            ]);
        }

    }


    /**
     * @param int $orderId
     * @param int $amount
     * @param string $callbackUrl
     * @param string $additionalData
     */
    public function startPayment(int $orderId,int $amount,string $callbackUrl,string $additionalData) {
        $req = new PinPayment();
        $req->setAmount($amount);
        $req->setOrderId($orderId);
        $req->setPin($this->pin);
        $req->setCallbackUrl($callbackUrl);
        $req->setAdditionalData($additionalData);
        $req->setAuthority(0);
        $req->setStatus(1);
        try {
            $res=$this->sendPayRequest($req);
            $getPay  = json_decode($res);
            $Code    = $getPay->responseCode ?? -1;
            $Message = $getPay->responseMessage ?? 'Error';

            if($Code == 0){
                $Token = $getPay->responseItems->Token ?? '';
                if(!empty($Token)){
                    header('LOCATION: '.self::$gate_url . $Token);
                    exit;
                }
            }
        } catch (Exception $e) {

        }
    }


    /**
     * call after bank callback for verify
     * @param $RRN -> reference number
     * @param $Token
     * @param $Status
     * @return string
     */
    public   function callback($RRN, $Token, $Status)
    {
        if ($RRN > 0 and $Status == 0) {

            $client = new nusoap_client(self::$ConfirmService, 'wsdl');
            $client->soap_defencoding = self::$Encoding;
            $client->decode_utf8 = FALSE;
            $err = $client->getError();
            if ($err) {
                return self::response(-1, $err, []);
            }

            $parameters = [
                'LoginAccount' =>  $this->pin,
                'Token' => $Token
            ];

            $result = $client->call('ConfirmPayment', ['requestData' => $parameters]);
            if ($client->fault) {
                $err = $client->getError();
                return self::response(-1, $err, []);
            } else {

                // update database

                return self::response($Status, self::errors($Status), [
                    'Status' => $result['ConfirmPaymentResult']['Status'] ?? -123456789,
                    'Token' => $result['ConfirmPaymentResult']['Token'],
                    'Message' => $result['ConfirmPaymentResult']['Message'] ?? self::errors($Status),
                    'RRN' => $result['ConfirmPaymentResult']['RRN'],
                    'CardNumberMasked' => $result['ConfirmPaymentResult']['CardNumberMasked']
                ]);
            }
        } else {

            // update database

            return self::response($Status, self::errors($Status), [
                'Status' => $Status,
                'Token' => $Token,
                'Message' => self::errors($Status),
                'RRN' => $RRN,
                'CardNumberMasked' => ''
            ]);
        }

    }







    /**
     * generate output
     * @param int $Status
     * @param string $Message
     * @param array $Items
     * @return string
     */
    private static function response($Status = -1, $Message = '', $Items = []){
        $data = [
            'responseCode' => $Status,
            'responseMessage' => $Message,
            'responseItems' => $Items
        ];
        return json_encode($data);
    }
}