<?php

namespace XCurrency\App\RateProvider;

use Exception;

class CurrencyFreaks extends ProviderBase {
    public function get_url() {
        return 'https://api.currencyfreaks.com/latest?apikey=';
    }

    /**
     * @return mixed
     */
    public function get_rates( $api_token ) {
        $response        = wp_remote_get( $this->get_url() . $api_token );
        $response_body   = wp_remote_retrieve_body( $response );
        $needed_response = json_decode( $response_body, true );

        if ( isset( $needed_response['error'] ) ) {
            throw new Exception( ! empty( $needed_response['error']['message'] ) ? $needed_response['error']['message'] : __( 'Something was wrong', 'x-currency' ) );
        }
        return $needed_response;
    }
}
