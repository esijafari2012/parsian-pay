<?php


namespace Esijafari2012\ParsianPay\Entities;


/**
 * Class ReversalResult
 * @package Esijafari2012\ParsianPay\Entities
 */
class ReversalResult extends PayResult
{

    /**
     * ReversalResult constructor.
     * @param array $result
     */
    public function __construct(array $result)
    {
        parent::__construct($result);

    }
}