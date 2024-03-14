<?php

namespace XCurrency\App\RateProvider;

abstract class ProviderBase {
    abstract public function get_url();

    /**
     * @param $base_currency
     */
    abstract public function get_rates( $api_token );
}
