<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\PayResult;
use Esijafari2012\ParsianPay\Entities\ReverseToken;

/**
 * Class Reverse
 * @package Esijafari2012\ParsianPay
 */
class Reverse  extends ParsianIPG
{

    /**
     * @var PayResult|null
     */
    private $payResult;

    /**
     * Reverse constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
        $this->payResult=null;
    }


    /**
     * @param ReverseToken $rvToken
     * @return array
     * @throws ParsianErrorException
     */
    protected function reverseRequest( ReverseToken $rvToken){

        if ($rvToken->getToken() <= 0) {
            //throw new ParsianErrorException( -2);
            return false;
        }

        $parameters = [
            'LoginAccount' =>  $rvToken->getPin(),
            'Token' => $rvToken->getToken()
        ];
        $result = $this->sendRequest($this->reverse_url,'ReversalRequest',$parameters);

        $status = null;
        if(isset($result['ReversalRequestResult']['Status'])){
            $status = $result['ReversalRequestResult']['Status'];
        }

        if($status!= '0'){
            throw new ParsianErrorException( $status);
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
     * @return PayResult|null
     */
    public   function  reverse(int $token)
    {
        $rvToken=new ReverseToken();
        $rvToken->setPin($this->pin);
        $rvToken->setToken($token);

        $this->payLogger->writeInfo($this->getRequestMessage($rvToken));
        $this->payResult=null;

        try {
            $result=$this->reverseRequest($rvToken);
            $this->payResult=new PayResult( $result);
            $this->payLogger->writeInfo($this->getResultMessage($this->payResult));
        } catch (ParsianErrorException $e) {
            $this->payResult=new PayResult( [
                'Status' => $e->getCode(),
                'Token' => $rvToken->getToken(),
                'Message' => $e->getMessage(),
            ]) ;
            $this->payLogger->writeError($this->getResultMessage($this->payResult));
        } catch (\Exception $e) {
            $this->payResult=new PayResult( [
                'Status' => -1,
                'Token' => $rvToken->getToken(),
                'Message' => self::codeToMessage(-1,$e->getMessage()),
            ]) ;
            $this->payLogger->writeError($this->getResultMessage($this->payResult));
        }

        return  $this->payResult;
    }
}