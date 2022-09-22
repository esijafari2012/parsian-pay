<?php


namespace Esijafari2012\ParsianPay\Entities;

/**
 * Class ReverseToken
 * @package Esijafari2012\ParsianPay\Entities
 */
class ReverseToken
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


}