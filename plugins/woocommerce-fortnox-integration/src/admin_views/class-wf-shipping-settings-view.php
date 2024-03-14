<?php

namespace src\admin_views;

if ( !defined( 'ABSPATH' ) ) die();

use src\wetail\admin\WF_Admin_Settings;
use src\fortnox\WF_Plugin;


class WF_Shipping_Settings_View{

    /**
     * Adds all required setting fields for Shipping Settings View
     */
    public static function add_settings(){

        $page = "fortnox";

        WF_Admin_Settings::add_tab( [
            'page' => $page,
            'name' => "shipping",
            'title' => __( "Shipping", WF_Plugin::TEXTDOMAIN ),
        ] );

        WF_Admin_Settings::add_section( [
            'page' => $page,
            'tab' => "shipping",
            'name' => "general-shipping",
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "shipping",
            'section' => "general-shipping",
            'name' => "fortnox_shipping_product_sku",
            'short' => true,
            'title' => __( "Shipping product SKU", WF_Plugin::TEXTDOMAIN ),
            'tooltip' => __( "This settings is recommended if you're selling products with other VAT rate than 25%. Create an unpublished product with 25% VAT rate and specify its SKU here.", WF_Plugin::TEXTDOMAIN )
        ] );

        WF_Admin_Settings::add_field( [
            'page' => $page,
            'tab' => "shipping",
            'section' => "general-shipping",
            'name' => "fortnox_shipping_product_sku_non_eu",
            'short' => true,
            'title' => __( "Shipping product SKU for countries outside EU", WF_Plugin::TEXTDOMAIN ),
            'tooltip' => __( "This settings is recommended if you're selling products to countries outside EU. Create an unpublished product with 0% VAT rate and specify its SKU here.", WF_Plugin::TEXTDOMAIN )
        ] );

        WF_Admin_Settings::add_section( [
            'page' => $page,
            'tab' => "shipping",
            'name' => "advanced-shipping",
        ] );

        foreach( self::get_shipping_zones() as $zone ) {

        	$zone_title = ($zone['id'] != 0) ? __( 'Shipping zone:', WF_Plugin::TEXTDOMAIN ) . ' ' . $zone['zone_name'] : $zone['zone_name'];

            WF_Admin_Settings::add_section( [
                'page' => $page,
                'tab' => "shipping",
                'name' => "advanced-shipping-zone" . $zone['zone_name'],
                'title' => $zone_title,
            ] );

            foreach( $zone['shipping_methods'] as $shipping_method ) {
                WF_Admin_Settings::add_field( [
                    'page'          => $page,
                    'tab'           => "shipping",
                    'section'       => "advanced-shipping-zone" . $zone['zone_name'],
                    'name'          => "fortnox_shipping_code_{$shipping_method->id}:{$shipping_method->instance_id}:{$zone['zone_id']}",
                    'title'         => __( 'Shipping method:', WF_Plugin::TEXTDOMAIN ) . ' ' . $shipping_method->title,
                    'tooltip'   => sprintf( __( "Code for '%s' shipping method from %s in Fortnox.", WF_Plugin::TEXTDOMAIN ),
                        $shipping_method->method_title, $zone['zone_name'] ),
                    'type' => "dropdown",
                    'short' => true,
                    'options' => self::get_delivery_ways(),
                ] );
            }
        }
    }

    /** Returns Fortnox delivery terms from database
     * @return array
     */
    private static function get_delivery_ways(){
        $fortnox_delivery_ways = get_option( 'fortnox_delivery_ways' );

    	if ( $fortnox_delivery_ways ) {
            $delivery_ways = array_map( function( $fortnox_delivery_way ){
			    return [
				    "value" => $fortnox_delivery_way,
				    "label" => $fortnox_delivery_way
			    ];
		    }, $fortnox_delivery_ways );

		    array_unshift( $delivery_ways, [
			    "value" => "",
			    "label" => __( "Please select...", WF_Plugin::TEXTDOMAIN )
		    ]);
	    } else {
            $delivery_ways = array(array(
			    "value" => "",
			    "label" => __( "No delivery ways available in Fortnox", WF_Plugin::TEXTDOMAIN )
		    ));
	    }

        return $delivery_ways;
    }

    /** Returns reformatted shipping zones
     * @return array
     */
    private static function get_shipping_zones(){
        $shipping_zones = \WC_Shipping_Zones::get_zones();
        $default_zone = \WC_Shipping_Zones::get_zone(0);
        $default_zone_array = $default_zone->get_data();
        $default_zone_array['zone_id'] = 0;
        $default_zone_array['shipping_methods'] = $default_zone->get_shipping_methods();

        $shipping_zones[] = $default_zone_array;

        return $shipping_zones;
    }
}
