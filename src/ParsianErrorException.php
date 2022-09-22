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
    protected $code=-100;

    /**
     * @var string
     */
    protected $message = 'خطای بانک.';

    /**
     * ParsianErrorException constructor.
     * @param int $code
     * @param string $message
     */
    public function __construct(int $code = -32768,string $message="")
    {
        parent::__construct(self::codeToMessage($code,$message), $code);
    }





}