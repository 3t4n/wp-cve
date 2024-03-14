<?php

use XCurrency\App\RateProvider\CurrencyFreaks;
use XCurrency\App\RateProvider\Fixer;
use XCurrency\App\RateProvider\FixerApiLayer;
use XCurrency\App\RateProvider\OpenExchangeRates;

return [
    'fixer'             => [
        'label' => 'Fixer',
        'class' => Fixer::class
    ],
    'fixer-api-layer'   => [
        'label' => 'Fixer Api Layer',
        'class' => FixerApiLayer::class
    ],
    'currencyfreaks'    => [
        'label' => 'CurrencyFreaks',
        'class' => CurrencyFreaks::class
    ],
    'openexchangerates' => [
        'label' => 'OpenExchangeRates',
        'class' => OpenExchangeRates::class
    ]
];