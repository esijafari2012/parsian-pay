<?php


namespace Esijafari2012\ParsianPay;

/**
 * Class ParsianErrorException
 * @package Esijafari2012\ParsianPay
 */
class ParsianErrorException extends \Exception
{
    use Errors;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message ;

    /**
     * ParsianErrorException constructor.
     * @param int $code
     * @param string $message
     */
    public function __construct(int $code ,string $message="")
    {
        parent::__construct(self::codeToMessage($code,$message), $code);
    }





}