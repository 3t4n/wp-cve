<?php

namespace HQRentalsPlugin\HQRentalsHelpers;

class HQRentalsDataFilter
{
    /*
     * Check is Data its a Wordpress Post
     */
    public function isPost($data)
    {
        return gettype($data) == 'object' and $this->getClassName($data) == 'WP_Post';
    }

    /*
     * Check if its an id
     */
    public function isId($data)
    {
        return gettype($data) == 'integer' or gettype($data) == 'string';
    }

    /*
     * Retrieve Class Name
     */
    public function getClassName($object)
    {
        return get_class($object);
    }
    public function formatPriceObject($price)
    {
        $obj = new \stdClass();
        if ($price) {
            $obj->currency = ($price->currency) ? $price->currency : '';
            $obj->currency_icon = ($price->currency_icon) ? $price->currency_icon : '';
            $obj->amount = ($price->amount) ? $price->amount : '';
            $obj->usd_amount = ($price->usd_amount) ? $price->usd_amount : '';
            $obj->amount_for_display = ($price->amount_for_display) ? $price->amount_for_display : '';
        } else {
            $obj->currency = '';
            $obj->currency_icon = '';
            $obj->amount = '';
            $obj->usd_amount = '';
            $obj->amount_for_display = '';
        }
        return $obj;
    }
}
