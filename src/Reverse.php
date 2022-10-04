<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\ReversalRequest;
use Esijafari2012\ParsianPay\Entities\ReversalResult;

/**
 * Class Reverse
 * @package Esijafari2012\ParsianPay
 */
class Reverse  extends ParsianIPG
{

    /**
     * @var ReversalResult|null
     */
    private $reversalResult;

    /**
     * Reverse constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
        $this->reversalResult=null;
    }


    /**
     * @param ReversalRequest $reversalRequest
     * @return array|false|null[]
     * @throws ParsianErrorException
     */
    protected function reverseRequest( ReversalRequest $reversalRequest){

        if ($reversalRequest->getToken() <= 0) {
            //throw new ParsianErrorException( -2);
            return false;
        }

        $parameters = [
            'LoginAccount' =>  $reversalRequest->getPin(),
            'Token' => $reversalRequest->getToken()
        ];
        $result = $this->sendRequest($this->reverse_url,'ReversalRequest',$parameters);

        $status = null;
        if(isset($result['ReversalRequestResult']['Status'])){
            $status = $result['ReversalRequestResult']['Status'];
        }

        if($status!= '0'){
            //throw new ParsianErrorException( $status);
            if(isset($result['ReversalRequestResult']['Token']))
                $token = $result['ReversalRequestResult']['Token']  ;
            else
                $token = null;
            if(isset($result['ReversalRequestResult']['Message']))
                $Message = $result['ReversalRequestResult']['Message'] ;
            else
                $Message = null;
            return [
                'Status' => $status,
                'Token' => $token,
                'Message' => $Message,
            ];
        }else {
            // update database
            return [
                'Status' => $status,
                'Token' => $result['ReversalRequestResult']['Token'],
                'Message' => $result['ReversalRequestResult']['Message'],
            ];
        }
    }


    /**
     * @param int $token
     * @return ReversalResult|false|null
     */
    public   function  reverse(int $token)
    {
        $reversalRequest=new ReversalRequest();
        $reversalRequest->setPin($this->pin);
        $reversalRequest->setToken($token);

        $this->payLogger->writeInfo($this->getRequestMessage($reversalRequest));
        $this->reversalResult=null;

        try {
            $result=$this->reverseRequest($reversalRequest);
            if($result!=false) {
                $this->reversalResult = new ReversalResult($result);
                $this->payLogger->writeInfo($this->getResultMessage($this->reversalResult));
            }return  false;
        } catch (ParsianErrorException $e) {
            $this->reversalResult=new ReversalResult( [
                'Status' => $e->getCode(),
                'Token' => $reversalRequest->getToken(),
                'Message' => $e->getMessage(),
            ]) ;
            $this->payLogger->writeError($this->getResultMessage($this->reversalResult));
        } catch (\Exception $e) {
            $this->reversalResult=new ReversalResult( [
                'Status' =>  $e->getCode(),
                'Token' => $reversalRequest->getToken(),
                'Message' => self::codeToMessage( $e->getCode(),$e->getMessage()),
            ]) ;
            $this->payLogger->writeError($this->getResultMessage($this->reversalResult));
        }

        return  $this->reversalResult;
    }
}