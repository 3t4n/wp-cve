<?php

namespace HQRentalsPlugin\HQRentalsHelpers;

use HQRentalsPlugin\HQRentalsQueries\HQRentalsDBQueriesCarRentalSetting;
use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;

class HQRentalsCurrencyHelper
{
    private static $currencies = [
        'usd'   => [
            'symbol' => '$',
        ],
        'eur'   =>  [
            'symbol' => '€'
        ]
    ];

    public static function getCurrencySymbol()
    {
        $query = new HQRentalsDBQueriesCarRentalSetting();
        $currency = $query->getCarRentalSetting('default_currency');
        try {
            return HQRentalsCurrencyHelper::$currencies[$currency->settings]['symbol'];
        } catch (\Throwable $exception) {
            return '$';
        }
    }
}
