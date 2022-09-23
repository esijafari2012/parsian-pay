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
    private   $log;


    /**
     * PayLogger constructor.
     */
    public function __construct()
    {
        $this->log = new Logger('parsianPay');
        $this->log->pushHandler(new StreamHandler(__DIR__.'/logs/parsianPay.log', Logger::DEBUG));
    }


    /**
     * @param string $message
     */
    public function writeWarning(string $message){
        $this->log->warning($message);
    }


    /**
     * @param string $message
     */
    public function writeError(string $message){
        $this->log->error($message);
    }


    /**
     * @param string $message
     */
    public function writeInfo(string $message){
        $this->log->info($message);
    }

    /**
     * @param string $message
     */
    public function writeNotice(string $message){
        $this->log->notice($message);
    }

    /**
     * @param string $message
     */
    public function writeDebug(string $message){
        $this->log->debug($message);
    }

    /**
     * @param string $message
     */
    public function writeAlert(string $message){
        $this->log->alert($message);
    }

    /**
     * @param string $message
     */
    public function writeCritical(string $message){
        $this->log->critical($message);
    }

    /**
     * @param string $message
     */
    public function writeEmergency(string $message){
        $this->log->emergency($message);
    }

}