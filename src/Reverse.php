<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\ReverseToken;

/**
 * Class Reverse
 * @package Esijafari2012\ParsianPay
 */
class Reverse  extends ParsianIPG
{

    /**
     * Reverse constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
    }


    /**
     * @param ReverseToken $rvToken
     * @return array
     * @throws ParsianErrorException
     */
    protected function reverseRequest( ReverseToken $rvToken){

        if ($rvToken->getToken() <= 0) {
            throw new ParsianErrorException( -2);
        }

        $parameters = [
            'LoginAccount' =>  $rvToken->getPin(),
            'Token' => $rvToken->getToken()
        ];
        $result = $this->sendRequest($this->reverse_url,'ReversalRequest',$parameters);

        $status = -1;
        if(isset($result['ReversalRequestResult']['Status'])){
            if(!empty($result['ReversalRequestResult']['Status']))
                $status = $result['ReversalRequestResult']['Status'];
        }

        if($status!= 0){
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
     * @return array
     */
    public   function  reverse(int $token)
    {
        $rvToken=new ReverseToken();
        $rvToken->setPin($this->pin);
        $rvToken->setToken($token);

        try {
            $res=$this->reverseRequest($rvToken);
            return  $res;
        } catch (ParsianErrorException $e) {
            return [
                'Status' => $e->getCode(),
                'Token' => $rvToken->getToken(),
                'Message' => $e->getMessage(),
            ] ;

        } catch (\Exception $e) {
            return [
                'Status' => -1,
                'Token' => $rvToken->getToken(),
                'Message' => self::codeToMessage(-1,$e->getMessage()),
            ] ;
        }
    }
}