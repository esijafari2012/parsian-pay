<?php


namespace Esijafari2012\ParsianPay;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


/**
 * Class PayLogger
 * @package Esijafari2012\ParsianPay
 */
class PayLogger
{

    /**
     * @var Logger|null
     */
    private   $logger;


    /**
     * PayLogger constructor.
     */
    public function __construct()
    {
        $this->logger = null;
    }

    /**
     * @param string $address
     */
    public function create(string $address=''){
        $this->logger = new Logger('parsianPay');
        if(empty($address)) $address=__DIR__.'/logs/parsianPay.log';
        $this->logger->pushHandler(new StreamHandler($address, Logger::DEBUG));
    }



    /**
     * @param string $message
     */
    public function writeWarning(string $message){
        if($this->logger instanceof Logger) $this->logger->warning($message);
    }


    /**
     * @param string $message
     */
    public function writeError(string $message){
        if($this->logger instanceof Logger) $this->logger->error($message);
    }


    /**
     * @param string $message
     */
    public function writeInfo(string $message){
        if($this->logger instanceof Logger) $this->logger->info($message);
    }

    /**
     * @param string $message
     */
    public function writeNotice(string $message){
        if($this->logger instanceof Logger) $this->logger->notice($message);
    }

    /**
     * @param string $message
     */
    public function writeDebug(string $message){
        if($this->logger instanceof Logger) $this->logger->debug($message);
    }

    /**
     * @param string $message
     */
    public function writeAlert(string $message){
        if($this->logger instanceof Logger) $this->logger->alert($message);
    }

    /**
     * @param string $message
     */
    public function writeCritical(string $message){
        if($this->logger instanceof Logger) $this->logger->critical($message);
    }

    /**
     * @param string $message
     */
    public function writeEmergency(string $message){
        if($this->logger instanceof Logger) $this->logger->emergency($message);
    }

}