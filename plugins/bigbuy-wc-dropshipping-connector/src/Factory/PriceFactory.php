<?php

namespace WcMipConnector\Factory;

use WcMipConnector\Model\Price;

defined('ABSPATH') || exit;

class PriceFactory
{
    /**
     * @param $currency
     * @param float $quantity
     * @return Price
     */
    public static function create($currency, $quantity = 0.0): Price
    {
        $price = new Price();
        $price->CurrencyCode = $currency;
        $price->Amount = $quantity;

        return $price;
    }
}