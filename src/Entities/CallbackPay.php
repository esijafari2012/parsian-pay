<?php


namespace Esijafari2012\ParsianPay\Entities;


/**
 * Class CallbackPay
 * @package Esijafari2012\ParsianPay\Entities
 */
class CallbackPay
{

    /**
     * @var string
     */
    public $pin;

    /**
     * @var int
     */
    public $token;

    /**
     * @var int
     */
    public $status;

    /**
     * @var int
     */
    public $RRN;

    /**
     * @return string
     */
    public function getPin(): string
    {
        return $this->pin;
    }

    /**
     * @param string $pin
     */
    public function setPin(string $pin)
    {
        $this->pin = $pin;
    }

    /**
     * @return int
     */
    public function getToken(): int
    {
        return $this->token;
    }

    /**
     * @param int $token
     */
    public function setToken(int $token)
    {
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getRRN(): int
    {
        return $this->RRN;
    }

    /**
     * @param int $RRN
     */
    public function setRRN(int $RRN)
    {
        $this->RRN = $RRN;
    }



}