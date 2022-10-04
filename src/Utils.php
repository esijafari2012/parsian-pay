<?php


namespace Esijafari2012\ParsianPay;

/**
 * Class Utils
 * @package Esijafari2012\ParsianPay
 */
class Utils
{

    /**
     * Return value if it's set otherwise return default value
     *
     * @param array $data
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    public static function value(array $data, $key, $default = null)
    {
        $key=strtolower($key);
        return isset($data[$key]) ? $data[$key] : $default;
    }


    /**
     * @param $data
     * @return array
     */
    public static function arrayToLower( $data)
    {
        $result=array();
        if(is_array($data)){
            foreach ($data as $key=>$item){
                $key=strtolower($key);
                $result[$key]=$item;
            }
        }
        return $result;
    }
}