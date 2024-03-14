<?php

namespace XCurrency\App\RateProvider;
use Exception;

class OpenExchangeRates extends ProviderBase {
    public function get_url() {
        return 'https://openexchangerates.org/api/latest.json?app_id=';
    }

    /**
     * @return mixed
     */
    public function get_rates( $api_token ) {
        $response        = wp_remote_get( $this->get_url() . $api_token );
        $response_body   = wp_remote_retrieve_body( $response );
        $needed_response = json_decode( $response_body, true );

        if ( isset( $needed_response['description'] ) ) {
            throw new Exception( $needed_response['description'] );
        }
        return $needed_response;
    }
}
