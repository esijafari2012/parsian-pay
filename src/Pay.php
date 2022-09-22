<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\PeyResult;
use Esijafari2012\ParsianPay\Entities\PinPayment;


/**
 * Class Pay
 * @package Esijafari2012\ParsianPay
 */
class Pay extends ParsianIPG
{

    /**
     * Pay constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
    }


    /**
     * @param PinPayment $req
     * @return array
     * @throws ParsianErrorException
     */
    protected function sendPayRequest(PinPayment $req){

        $parameters = [
            'LoginAccount' => $req->getPin(),
            'Amount' =>  $req->getPin(),
            'OrderId' => $req->getOrderId(),
            'CallBackUrl' => $req->getCallbackUrl(),
            'AdditionalData'=> $req->getAdditionalData()
        ];

        $result = $this->sendRequest($this->sale_url,'SalePayment',$parameters);

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
     * @param int $orderId
     * @param int $amount
     * @param string $callbackUrl
     * @param string $additionalData
     * @return PeyResult
     */
    public function payment(int $orderId,int $amount,string $callbackUrl,string $additionalData) {
        $req = new PinPayment();
        $req->setAmount($amount);
        $req->setOrderId($orderId);
        $req->setPin($this->pin);
        $req->setCallbackUrl($callbackUrl);
        $req->setAdditionalData($additionalData);

        $pres=null;

        try {
            $res=$this->sendPayRequest($req);
            $pres=new PeyResult( $res);
            if(($res['Status']==0)&&($res['Token']>0)){
                $Token = $res['Token'];
                if(!empty($Token)){
                    header('LOCATION: '.$this->gate_url . $Token);
                    exit;
                }
            }
        } catch (ParsianErrorException $e) {
            $pres=new PeyResult( array(
                'Status' => $e->getCode(),
                'Token' => 0,
                'Message' => $e->getMessage()
            ));
        } catch (\Exception $e) {
            $pres=new PeyResult(array(
                'Status' => $e->getCode(),
                'Token' => 0,
                'Message' => $e->getMessage()
            ));
        }

        return $pres;
    }

}