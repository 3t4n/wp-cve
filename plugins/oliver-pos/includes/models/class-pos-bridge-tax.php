<?php
namespace bridge_models;
defined( 'ABSPATH' ) || exit;

use WC_Tax;
/**
 * This class contain operation's related to tax'es
 */
class Pos_Bridge_Tax
{

    function __construct()
    {
        # code...
    }

    /**
     * Get all taxes
     * @return array Return tax array on success || Error message
     */
    public function oliver_pos_get_taxes() {
        global $wpdb;
        $taxes = array();
        $get_tax = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates");

        if (! empty($get_tax)) {
            foreach ($get_tax as $key => $tax) {
                $id = (int) $tax->tax_rate_id;
                array_push($taxes, $this->oliver_pos_tax( $id ));
            }

            if (!empty($taxes)) {
                return $taxes;
            }
        }
	    return oliver_pos_api_response('No Data found', -1);
    }


    /**
     * Get the tax for the given tax id
     * @param int $id tax id
     * @return array Return tax details || Error  message
     */
    public function oliver_pos_get_tax( $id ) {
        if ( !is_null($id) && is_integer($id) ) {
            $tax = $this->oliver_pos_tax( $id );

            if (! empty($tax)) {
                return $tax;
            }
	        return oliver_pos_api_response('No Data found', -1);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Get the tax for the given tax id
     * @param int $id tax id
     * @return array Return tax details || Error  message
     */
    public function oliver_pos_tax( $id )
    {
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates where tax_rate_id = %d";
		$tax = $wpdb->get_results($wpdb->prepare( $query, $id) );
        if (!empty($tax)) {
            $id = (int) $tax->tax_rate_id;
            return array(
                'id' => $id,
                'country' => $tax->tax_rate_country,
                'state' => $tax->tax_rate_state,
                'city' => $this->oliver_pos_get_tax_city( $id ),
                'postcode' => $this->oliver_pos_get_tax_postcode( $id ),
                'tax_rate' => WC_Tax::get_rate_percent( $id ),
                'tax_rate_name' => WC_Tax::get_rate_label( $id ),
                'tax_class' => $tax->tax_rate_class,
                'tax_priority' => $tax->tax_rate_priority, // since version 2.1.2.2
                'tax_shipping' => $tax->tax_rate_shipping,
                'tax_compound' => $tax->tax_rate_compound,
            );
        }
    }

    /**
     * Get tax by location
     * @param string $location location code
     * @return array Return tax details || Error  message
     */
    public function oliver_pos_get_tax_by_location($location)
    {
        if (empty($location)) {
	        return oliver_pos_api_response('No data found', -1);
        }

        $rates = WC_Tax::find_rates( $location );
    }

    /**
     * Get tax city by tax id
     * @param int $id tax id
     * @return string Return tax city name
     */
    public function oliver_pos_get_tax_city( $id )
    {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT location_code FROM {$wpdb->prefix}woocommerce_tax_rate_locations WHERE tax_rate_id = %d AND location_type = 'city'", $id);
        $city = $wpdb->get_var( $sql );
        return $city;
    }

    /**
     * Get tax postcode by tax id
     * @param int $id tax id
     * @return string Return tax postcode
     */
    public function oliver_pos_get_tax_postcode( $id )
    {
        global $wpdb;
        $sql = $wpdb->prepare("SELECT location_code FROM {$wpdb->prefix}woocommerce_tax_rate_locations WHERE tax_rate_id = %d AND location_type = 'postcode'", $id);
        $postcode = $wpdb->get_var( $sql );
        return $postcode;
    }

    /**
     * Get tax count
     * @return int Return tax count
     */
    public static function oliver_pos_tax_count()
    {
        global $wpdb;
        $get_tax = $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}woocommerce_tax_rates");
        return (int) $get_tax;
    }
}