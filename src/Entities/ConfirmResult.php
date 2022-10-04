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
     * @var int
     */
    public $Token;

    /**
     * @var string
     */
    public $Message;

    /**
     * @var int
     */
    public $RRN;

    /**
     * @var string
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
    public function setStatus(int $Status)
    {
        $this->Status = $Status;
    }

    /**
     * @return int
     */
    public function getToken(): int
    {
        return $this->Token;
    }

    /**
     * @param int $Token
     */
    public function setToken(int $Token)
    {
        $this->Token = $Token;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->Message;
    }

    /**
     * @param string $Message
     */
    public function setMessage(string $Message)
    {
        $this->Message = $Message;
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

    /**
     * @return string
     */
    public function getCardNumberMasked(): string
    {
        return $this->CardNumberMasked;
    }

    /**
     * @param string $CardNumberMasked
     */
    public function setCardNumberMasked(string $CardNumberMasked)
    {
        $this->CardNumberMasked = $CardNumberMasked;
    }



}