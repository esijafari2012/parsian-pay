<?php


namespace Esijafari2012\ParsianPay\Entities;


use Esijafari2012\ParsianPay\Utils;

/**
 * Class PayResult
 * @package Esijafari2012\ParsianPay\Entities
 */
class PayResult
{

    /**
     * PayResult constructor.
     * @param array $result
     */
    public function __construct(array $result)
    {
        $result = Utils::arrayToLower($result);
        $this->Status = Utils::value($result, 'Status');
        $this->Token = Utils::value($result, 'Token' );
        $this->Message = Utils::value($result, 'Message');
    }


    /**
     * @var int
     */
    public $Status;

    /**
     * @var int|null
     */
    public $Token;

    /**
     * @var string|null
     */
    public $Message;

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->Status;
    }

    /**
     * @param int $Status
     */
    public function setStatus(int $Status): void
    {
        $this->Status = $Status;
    }

    /**
     * @return int|null
     */
    public function getToken(): ?int
    {
        return $this->Token;
    }

    /**
     * @param int|null $Token
     */
    public function setToken(?int $Token): void
    {
        $this->Token = $Token;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->Message;
    }

    /**
     * @param string|null $Message
     */
    public function setMessage(?string $Message): void
    {
        $this->Message = $Message;
    }



}