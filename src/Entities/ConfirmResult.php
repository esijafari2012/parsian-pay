<?php


namespace Esijafari2012\ParsianPay\Entities;


use Esijafari2012\ParsianPay\Utils;

/**
 * Class ConfirmResult
 * @package Esijafari2012\ParsianPay\Entities
 */
class ConfirmResult
{
    /**
     * PeyResult constructor.
     * @param array $result
     */
    public function __construct(array $result)
    {
        $result = Utils::arrayToLower($result);
        $this->Status = Utils::value($result, 'Status' );
        $this->Token = Utils::value($result, 'Token');
        $this->Message = Utils::value($result, 'Message');
        $this->RRN = Utils::value($result, 'RRN');
        $this->CardNumberMasked = Utils::value($result, 'CardNumberMasked');
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
     * @var int|null
     */
    public $RRN;

    /**
     * @var string|null
     */
    public $CardNumberMasked;

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

    /**
     * @return int|null
     */
    public function getRRN(): ?int
    {
        return $this->RRN;
    }

    /**
     * @param int|null $RRN
     */
    public function setRRN(?int $RRN): void
    {
        $this->RRN = $RRN;
    }

    /**
     * @return string|null
     */
    public function getCardNumberMasked(): ?string
    {
        return $this->CardNumberMasked;
    }

    /**
     * @param string|null $CardNumberMasked
     */
    public function setCardNumberMasked(?string $CardNumberMasked): void
    {
        $this->CardNumberMasked = $CardNumberMasked;
    }




}