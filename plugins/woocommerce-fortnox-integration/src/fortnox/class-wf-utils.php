<?php

namespace src\fortnox;

use Automattic\WooCommerce\Utilities\OrderUtil;

if ( !defined( 'ABSPATH' ) ) die();

class WF_Utils
{

    /**
     * Returns tax rates
     * @param \WC_Product $product
     * @param $country_code
     * @return int
     */
    public static function get_wc_tax_rate( $product, $country_code ){

        if ( empty( $country_code ) ){
            $country_code = 'SE';
        }

        foreach( \WC_Tax::get_rates_for_tax_class( $product->get_tax_class() ) as $tax_rate ){
            if ( $country_code == $tax_rate->tax_rate_country ){
                return $tax_rate->tax_rate;
            }
        }

        foreach( \WC_Tax::get_rates_for_tax_class( $product->get_tax_class() ) as $tax_rate ){

            if( '' == $tax_rate->tax_rate_country ){
                return $tax_rate->tax_rate;
            }
        }
        return 0;
    }

    /** Filter a shipping zone, returns zone_id if country in is in zone
     * @param $zone
     * @param $shipping_country
     * @return mixed
     */
    private static function filter_zone( $zone, $shipping_country ){
        return array_reduce( $zone['zone_locations'], function( $zone_id, $zone ) use ( $shipping_country ) {

            if( $shipping_country == $zone->code ){
                return $zone['zone_id'];
            }
            return $zone_id;
        });
    }

    /** Filter a shipping zone, returns zone_id if country in is in zone
     * @param $zone
     * @return array
     */
    private static function get_zone_codes( $zone_locations ){
        return array_map( function( $zone_location ) {
            return $zone_location->code ;
        }, $zone_locations );
    }

    public static function is_zone_countries_only( $zone ) {
        foreach ( $zone['zone_locations'] as $zone_location ){
            if ( $zone_location->type != 'country' ){
                return false;
            }
        }
        return true;
    }

    /** Filter a shipping zone, returns zone_id if country in is in zone
     * @param $shipping_country
     * @return mixed
     */
    public static function get_zone_id( $shipping_country, $shipping_zipcode ){
        fortnox_write_log( $shipping_zipcode );

        $matching_func = function ( $zone ) use ( $shipping_country )  {
            if ( in_array( $shipping_country, self::get_zone_codes( $zone['zone_locations'] ) ) )  {
                return $zone;
            }
        };

        $matching_zones = array_filter( \WC_Shipping_Zones::get_zones(), $matching_func );

        $fallback_zone = false;
        foreach ( $matching_zones as $matching_zone ){
            fortnox_write_log($matching_zone['zone_locations']);
            if ( self::is_zone_countries_only( $matching_zone )){
                $fallback_zone = $matching_zone;
            }
            else{
                foreach ( $matching_zone['zone_locations'] as $zone_location ){
                    if( $zone_location->type == 'country' ){
                        continue;
                    }
                    if( intval(trim($shipping_zipcode) ) == intval( $zone_location->code ) ){
                        fortnox_write_log("setting fallback zone " . $zone_location->code);
                        $fallback_zone = $matching_zone;
                        break 2;
                    }
                }
            }
        }

        if ( $fallback_zone ){
            return $fallback_zone['zone_id'];
        }
    }

    /**
     * @wrike https://www.wrike.com/open.htm?id=807478538 - with validation over VAT number
     *
     * @param int $order_id
     * @return array | bool
     */
    public static function get_vat_number( $order_id ){

        $vat_number = str_replace( ' ', '', self::get_order_meta_compat( $order_id, '_vat_number' ) );

        // Set customer VAT type based on country
        if ( ! empty( $vat_number ) ) {
            return $vat_number;
        }

        $vat_number = str_replace( ' ', '', self::get_order_meta_compat( $order_id, '_billing_vat_number' ) );
        fortnox_write_log( $vat_number );
        // Set customer VAT type based on country
        if ( ! empty( $vat_number ) ) {
            return $vat_number;
        }

        $vat_number = str_replace( ' ', '', self::get_order_meta_compat( $order_id, apply_filters( 'wf_eu_vat_meta_key', '__'  ) ) );

        // Set customer VAT type based on country
        if ( ! empty( $vat_number ) ) {
            return $vat_number;
        }

        return str_replace( ' ', '', apply_filters( 'wf_eu_vat_number', false, $order_id ) );
    }

    public static function vat_number_is_valid( $order ){
        return apply_filters( 'wf_eu_vat_number_is_valid', wc_string_to_bool( self::get_order_meta_compat( $order->get_id(), '_vat_number_is_valid' ) ), $order );
    }

    /** Fetches meta data from order. If HPOS is not available then reads from postmeta table
     * @param $wc_order_id
     * @param $meta_key
     * @return array|mixed|string
     */
    public static function get_order_meta_compat( $wc_order_id, $meta_key ){

        if( 0 === $wc_order_id ){
            return;
        }

        if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
            fortnox_write_log("get_order_meta_compat" . $wc_order_id);
            $wc_order = wc_get_order( $wc_order_id );
            if( ! $wc_order ){
                return;
            }
            return $wc_order->get_meta( $meta_key );
        } else {
            return get_post_meta( $wc_order_id, $meta_key, true );
        }
    }

    /**
     * @param $order_id
     * @return \stdClass|\WC_Order[]
     */
    public static function get_refunds( $order_id ){
        return wc_get_orders(
            array(
                'type'   => 'shop_order_refund',
                'parent' => intval( $order_id ),
                'return' => 'ids',
                'limit'  => -1,
            )
        );
    }

    public static function maybe_mail_error( $order_id, $error_message )
    {

        if ( get_option( 'fortnox_email_synchronization_errors' ) ){

            $message = __("Something went wrong when synchronizing order ID: {$order_id} to Fortnox.\n Error message: \n", WF_Plugin::TEXTDOMAIN);
            $message .= $error_message;
            $to = get_option('admin_email');
            $subject = __("Fortnox error", WF_Plugin::TEXTDOMAIN);
            $headers = 'From: ' . $to . "\r\n" .
                "Reply-To: <>\r\n";

            return wp_mail($to, $subject, strip_tags($message), $headers);
        }
    }
}
