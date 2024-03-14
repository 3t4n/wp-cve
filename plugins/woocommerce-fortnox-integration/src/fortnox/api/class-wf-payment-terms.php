<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;

class WF_Payment_Terms {

    /** Fetches all payment terms from Fortnox
     * @return array
     * @throws \Exception
     */
    public static function get_payment_terms(){
        $response = WF_Request::get( '/termsofpayments'  );

        if( $response->TermsOfPayments ){

            $func = function ( $terms_of_payment ){
                return $terms_of_payment->Code;
            };

            $codes = array_map( $func , $response->TermsOfPayments );
            update_option( 'fortnox_payment_terms', $codes );
            return $codes;

        }
        return [];
    }
}