<?php

namespace XCurrency\App\Providers;

use XCurrency\WpMVC\Contracts\Provider;

class CompatibilityServiceProvider implements Provider {
    public function boot() {
        /**
         * ShopEngine Compatibility
         */
        add_filter(
            'shopengine_filter_price_range', function ( $ranges ) {
                return array_map(
                    function ( $range ) {
                        return x_currency_exchange( $range );
                    }, $ranges 
                );
            } 
        );

        add_filter(
            'shopengine_currency_exchange_rate', function () {
                return x_currency_selected()->rate;
            } 
        );

        /**
         * Woocommerce Subscriptions Compatibility
         */
        add_filter(
            'woocommerce_subscriptions_product_sign_up_fee', function ( $price ) {
                return x_currency_exchange( $price );
            } 
        );
    }
}