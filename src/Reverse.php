<?php


namespace Esijafari2012\ParsianPay;


use Esijafari2012\ParsianPay\Entities\PeyResult;
use Esijafari2012\ParsianPay\Entities\ReverseToken;

/**
 * Class Reverse
 * @package Esijafari2012\ParsianPay
 */
class Reverse  extends ParsianIPG
{

    /**
     * @var PeyResult|null
     */
    private $pr;

    /**
     * Reverse constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
        $this->pr=null;
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
     * @return PeyResult|null
     */
    public   function  reverse(int $token)
    {
        $rvToken=new ReverseToken();
        $rvToken->setPin($this->pin);
        $rvToken->setToken($token);

        $this->plog->writeInfo($this->getRequestMessage($rvToken));
        $this->pr=null;

        try {
            $res=$this->reverseRequest($rvToken);
            $this->pr=new PeyResult( $res);
            $this->plog->writeInfo($this->getRequestMessage($this->pr));
        } catch (ParsianErrorException $e) {
            $this->pr=new PeyResult( [
                'Status' => $e->getCode(),
                'Token' => $rvToken->getToken(),
                'Message' => $e->getMessage(),
            ]) ;
            $this->plog->writeError($this->getRequestMessage($this->pr));
        } catch (\Exception $e) {
            $this->pr=new PeyResult( [
                'Status' => -1,
                'Token' => $rvToken->getToken(),
                'Message' => self::codeToMessage(-1,$e->getMessage()),
            ]) ;
            $this->plog->writeError($this->getRequestMessage($this->pr));
        }

        return  $this->pr;
    }
}