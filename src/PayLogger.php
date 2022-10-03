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
     * @var Logger
     */
    private   $logger;


    /**
     * PayLogger constructor.
     */
    public function __construct()
    {
        $this->logger = new Logger('parsianPay');
        $this->logger->pushHandler(new StreamHandler(__DIR__.'/logs/parsianPay.log', Logger::DEBUG));
    }


    /**
     * @param string $message
     */
    public function writeWarning(string $message){
        $this->logger->warning($message);
    }


    /**
     * @param string $message
     */
    public function writeError(string $message){
        $this->logger->error($message);
    }


    /**
     * @param string $message
     */
    public function writeInfo(string $message){
        $this->logger->info($message);
    }

    /**
     * @param string $message
     */
    public function writeNotice(string $message){
        $this->logger->notice($message);
    }

    /**
     * @param string $message
     */
    public function writeDebug(string $message){
        $this->logger->debug($message);
    }

    /**
     * @param string $message
     */
    public function writeAlert(string $message){
        $this->logger->alert($message);
    }

    /**
     * @param string $message
     */
    public function writeCritical(string $message){
        $this->logger->critical($message);
    }

    /**
     * @param string $message
     */
    public function writeEmergency(string $message){
        $this->logger->emergency($message);
    }

}