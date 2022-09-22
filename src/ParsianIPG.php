<?php


namespace Esijafari2012\ParsianPay;


use nusoap_client;


/**
 * Class ParsianIPG
 * @package Esijafari2012\ParsianPay
 */
class ParsianIPG extends ParsianRequest
{

    /**
     * ParsianIPG constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        parent::__construct($pin);
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


}