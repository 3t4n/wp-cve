<?php

use XCurrency\WpMVC\Enqueue\Enqueue;

$version = x_currency_version();
/**
 * Load web components 
 */ 
Enqueue::script( 'x-currency-switcher', 'js/switcher.js' );
Enqueue::script( 'x-currency-shortcode-default', 'js/shortcode-default.js', ['x-currency-switcher'] );
Enqueue::script( 'x-currency-sticky-default', 'js/sticky-default.js', ['x-currency-switcher'] );

$base_currency = x_currency_base()->id;

wp_localize_script(
    'x-currency-switcher', 'x_currency', [
        'version'       => $version,
        'media_url'     => x_currency_url( 'media/' ),
        'base_currency' => $base_currency,
        'isPro'         => 'no'
    ] 
);