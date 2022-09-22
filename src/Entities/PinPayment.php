<?php


namespace Esijafari2012\ParsianPay\Entities;

/**
 * Class PinPayment
 * @package Esijafari2012\ParsianPay\Entities
 */
class PinPayment
{
    /**
     * @var string
     */
    public $pin;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var int
     */
    public $orderId;

    /**
     * @var string
     */
    public $callbackUrl;

    /**
     * @var string|null
     */
    public $additionalData;

    /**
     * @var mixed
     */
    public $authority; // long

    /**
     * @var mixed
     */
    public $status;

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
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    /**
     * @param string $callbackUrl
     */
    public function setCallbackUrl(string $callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * @return mixed
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * @param mixed $authority
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getAdditionalData(): string
    {
        return $this->additionalData;
    }

    /**
     * @param string|null $additionalData
     */
    public function setAdditionalData(string $additionalData)
    {
        $this->additionalData = $additionalData;
    }




}