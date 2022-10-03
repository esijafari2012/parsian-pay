<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\PayResult;
use Esijafari2012\ParsianPay\Entities\PinPayment;


/**
 * Class Pay
 * @package Esijafari2012\ParsianPay
 */
class Pay extends ParsianIPG
{

    /**
     * @var PayResult|null
     */
    private $payResult;


    /**
     * Pay constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
        $this->payResult=null;
    }


    /**
     * @param PinPayment $req
     * @return array
     * @throws ParsianErrorException
     */
    protected function sendPayRequest(PinPayment $req){

        $parameters = [
            'LoginAccount' => $req->getPin(),
            'Amount' => $req->getAmount(),
            'OrderId' => $req->getOrderId(),
            'CallBackUrl' => $req->getCallbackUrl(),
            'AdditionalData'=> $req->getAdditionalData()
        ];

        $result = $this->sendRequest($this->sale_url,'SalePaymentRequest',$parameters);

        $Token = $result['SalePaymentRequestResult']['Token'];
        $Status = $result['SalePaymentRequestResult']['Status'];
        $Message = $result['SalePaymentRequestResult']['Message'];

        if(($Status==0)&&($Token>0)){
            // update database
            return array(
                'Status' => $Status,
                'Token' => $Token,
                'Message' => $Message
            );
        }else{
            throw new ParsianErrorException( $Status,$Message);
        }

    }


    /**
     * @param $orderId
     * @param $amount
     * @param string $callbackUrl
     * @param string $additionalData
     * @return PayResult|null
     */
    public function payment( $orderId, $amount,string $callbackUrl,string $additionalData="") {
        $req = new PinPayment();
        $req->setAmount($amount);
        $req->setOrderId($orderId);
        $req->setPin($this->pin);
        $req->setCallbackUrl($callbackUrl);
        $req->setAdditionalData($additionalData);

        $this->payLogger->writeInfo($this->getRequestMessage($req));

        $this->payResult=null;

        try {
            $res=$this->sendPayRequest($req);
            $this->payResult=new PayResult( $res);
            $this->payLogger->writeInfo($this->getResultMessage($this->payResult));
        } catch (ParsianErrorException $e) {
            $this->payResult=new PayResult( array(
                'Status' => $e->getCode(),
                'Token' => 0,
                'Message' => $e->getMessage()
            ));
            $this->payLogger->writeError($this->getResultMessage($this->payResult));
        } catch (\Exception $e) {
            $this->payResult=new PayResult(array(
                'Status' => $e->getCode(),
                'Token' => 0,
                'Message' => $e->getMessage()
            ));
            $this->payLogger->writeError($this->getResultMessage($this->payResult));
        }


        return  $this->payResult;
    }

    /**
     * @return false
     */
    public function redirect() {
        if( $this->payResult instanceof PayResult){
            if(($this->payResult->getStatus()==0)&&( $this->payResult->getToken()>0)){
                $Token =  $this->payResult->getToken();
                if(!empty($Token)){
                    header('LOCATION: '.$this->gate_url . $Token);
                    exit;
                }
            }
        }
        return false;
    }
}