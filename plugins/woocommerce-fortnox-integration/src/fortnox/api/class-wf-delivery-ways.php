<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;

class WF_Delivery_Ways {

    /** Fetches all delivery ways
     * @return array
     * @throws \Exception
     */
    public static function get_delivery_ways(){
        $response = WF_Request::get( '/wayofdeliveries'  );

        if( $response->WayOfDeliveries ){

            $func = function ( $delivery_way ){
                return $delivery_way->Code;
            };

            $codes = array_map( $func , $response->WayOfDeliveries );
            update_option( 'fortnox_delivery_ways', $codes );
            return $codes;

        }
        return [];
    }
}