<?php

namespace XCurrency\App\RateProvider;
use Exception;

class FixerApiLayer extends ProviderBase {
    public function get_url() {
        return 'https://api.apilayer.com/fixer/latest?base=USD&apikey=';
    }

    /**
     * @param $api_token
     * @return mixed
     */
    public function get_rates( $api_token ) {
        $response        = wp_remote_get( $this->get_url() . $api_token );
        $response_body   = wp_remote_retrieve_body( $response );
        $needed_response = json_decode( $response_body, true );

        if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            throw new Exception( ! empty( $needed_response['message'] ) ? $needed_response['message'] :  __( 'Something was wrong', 'x-currency' ) );
        }

        return $needed_response;
    }
}
