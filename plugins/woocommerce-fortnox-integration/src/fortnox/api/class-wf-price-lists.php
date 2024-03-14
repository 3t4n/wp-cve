<?php

namespace src\fortnox\api;

if ( !defined( 'ABSPATH' ) ) die();

use Exception;

class WF_Price_Lists
{

    /** Fetches all price lists from Fortnox
     * @return array
     * @throws \Exception
     */
    public static function get_price_lists()
    {
        $response = WF_Request::get( '/pricelists' );

        if ( $response->PriceLists ) {

            $func = function ( $price_list ) {
                return $price_list->Code;
            };

            $price_lists = array_map( $func, $response->PriceLists );
            update_option( 'fortnox_price_lists', $price_lists );
            return $price_lists;

        }
        return [];
    }
}