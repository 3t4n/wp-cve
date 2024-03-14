<?php

namespace AForms\Domain;

trait Lib 
{
    public function normalizePrice($rule, $price) 
    {
        $price1 = $price * pow(10, $rule->taxPrecision);
        switch ($rule->taxNormalizer) {
            case 'floor': 
                $price2 = intval(floor($price1));
                break;
            case 'ceil': 
                $price2 = intval(ceil($price1));
                break;
            case 'round': 
                $price2 = intval(round($price1));
                break;
            case 'trunc': 
                $sign = ($price1 < 0) ? -1 : 1;
                $price2 = $sign * intval(floor(abs($price1)));
                break;
        }
        $price3 = $price2 / pow(10, $rule->taxPrecision);
        //var_dump([$price, $price1, $price2, $price3]);
        return $price3;
    }

    public function trunc($x) 
    {
        return ($x < 0) ? ceil($x) : floor($x);
    }

    public function showPrice($currency, $price) 
    {
        $priceStr = number_format($price, $currency->taxPrecision, $currency->decPoint, $currency->thousandsSep);
        return $currency->pricePrefix.$priceStr.$currency->priceSuffix;
    }

    public function showNumber($currency, $price) 
    {
        return number_format($price, $currency->taxPrecision, $currency->decPoint, $currency->thousandsSep);
    }

    public function showNumberAP($currency, $price) 
    {
        $fs = explode(".", "".$price);
        $dec = number_format($fs[0], 0, $currency->decPoint, $currency->thousandsSep);
        return (count($fs) == 2) ? ($dec . '.' . $fs[1]) : $dec;
    }
}