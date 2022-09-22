<?php


namespace Esijafari2012\ParsianPay;

/**
 * Class ParsianRequest
 * @package Esijafari2012\ParsianPay
 */
class ParsianRequest
{

    use Errors;

    /**
     * Url of parsian gateway web service
     *
     * @var string $server_url Url for initializing payment request
     *
     */
    public   $sale_url = 'https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?WSDL';

    /**
     * Url of parsian gateway web service
     *
     * @var string $confirm_url Url for confirming transaction
     *
     */
    public   $confirm_url = 'https://pec.shaparak.ir/NewIPGServices/Confirm/ConfirmService.asmx?WSDL';

    /**
     * Address of gate for redirect
     *
     * @var string
     */
    public   $gate_url = 'https://pec.shaparak.ir/NewIPG/?Token=';

    /**
     * Url of parsian gateway web service
     *
     * @var string $reverse_url Url for reverse transaction
     *
     */
    public   $reverse_url = 'https://pec.shaparak.ir/NewIPGServices/Reverse/ReversalService.asmx?WSDL';


    /**
     *
     * @var string
     */
    protected   $Encoding    = "UTF-8";


    /**
     * @var string
     */
    public $pin;

    /**
     * ParsianRequest constructor.
     * @param string $pin
     */
    public function __construct(string $pin="")
    {
        $this->pin=$pin;
    }


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
}