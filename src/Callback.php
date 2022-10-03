<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\ConfirmPaymentRequest;
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
     * @param ConfirmPaymentRequest $confirmPaymentRequest
     * @return array
     * @throws ParsianErrorException
     */
    protected function confirmRequest( ConfirmPaymentRequest $confirmPaymentRequest){

        $parameters = [
            'LoginAccount' =>  $confirmPaymentRequest->getPin(),
            'Token' => $confirmPaymentRequest->getToken()
        ];

        $result = $this->sendRequest($this->confirm_url,'ConfirmPayment',$parameters);


        return  [
            'Status' => $result['ConfirmPaymentResult']['Status'] ?? -123456789,
            'Token' => $result['ConfirmPaymentResult']['Token'],
            'Message' => $result['ConfirmPaymentResult']['Message']  ,
            'RRN' => $result['ConfirmPaymentResult']['RRN'],
            'CardNumberMasked' => $result['ConfirmPaymentResult']['CardNumberMasked']
        ] ;
    }


    /**
     * @return ConfirmResult|null
     */
    public   function confirm()
    {
        $b=false;
        $status = null;
        $token = null;
        $RRN = null;

        if(isset($_POST["status"]))  $status = $_POST["status"]  ;
        if(isset($_POST["Token"]))  $token = $_POST["Token"]  ;
        if(isset($_POST["RRN"]))  $RRN = $_POST["RRN"]  ;

        if(isset($_POST["status"])){
            $status =  $_POST["status"];
            if($status==0){
                if(isset($_POST["RRN"])){
                    $RRN = $_POST["RRN"]  ;
                    if($RRN>0){
                        if(isset($_POST["Token"])){
                            $token = $_POST["Token"]  ;
                            if($token>0){
                                $b=true;
                                $confirmPaymentRequest = new ConfirmPaymentRequest();
                                $confirmPaymentRequest->setPin($this->pin);
                                $confirmPaymentRequest->setStatus($status);
                                $confirmPaymentRequest->setRRN($RRN);
                                $confirmPaymentRequest->setToken($token);

                                $this->payLogger->writeInfo($this->getRequestMessage($confirmPaymentRequest));

                                $this->confirmResult = null;

                                try {
                                    $result = $this->confirmRequest($confirmPaymentRequest);
                                    $this->confirmResult = new  ConfirmResult($result);
                                    $this->payLogger->writeInfo($this->getResultMessage($this->confirmResult));
                                } catch (ParsianErrorException $e) {
                                    $this->confirmResult = new  ConfirmResult([
                                        'Status' => $confirmPaymentRequest->getStatus(),
                                        'Token' => $confirmPaymentRequest->getToken(),
                                        'Message' => self::codeToMessage($confirmPaymentRequest->getStatus()),
                                        'RRN' => $confirmPaymentRequest->getRRN(),
                                        'CardNumberMasked' => ''
                                    ]);
                                    $this->payLogger->writeError($this->getResultMessage($this->confirmResult));
                                } catch (\Exception $e) {
                                    $this->confirmResult = new  ConfirmResult([
                                        'Status' => $confirmPaymentRequest->getStatus(),
                                        'Token' => $confirmPaymentRequest->getToken(),
                                        'Message' =>  $e->getMessage(),
                                        'RRN' => $confirmPaymentRequest->getRRN(),
                                        'CardNumberMasked' => ''
                                    ]);
                                    $this->payLogger->writeError($this->getResultMessage($this->confirmResult));
                                }

                            }
                        }
                    }
                }
            }
        }
        if(!$b){
            $this->confirmResult = new  ConfirmResult([
                'Status' => $status,
                'Token' => $token,
                'Message' => self::codeToMessage($status),
                'RRN' => $RRN,
                'CardNumberMasked' => ''
            ]);
            $this->payLogger->writeError($this->getResultMessage($this->confirmResult));

        }

        return $this->confirmResult;
    }
}