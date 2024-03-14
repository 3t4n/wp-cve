<?php

namespace EasyBooking;

/**
*
* All functions related to WooCommerce Product Add-Ons.
* @version 3.1.7
*
**/

defined( 'ABSPATH' ) || exit;

class Pao_Functions {

	/**
	*
	* Get a simplified array of product add-ons.
	* @param WC_Product | WC_Product_Variation - $product
	* @return array - $addons
	*
	**/
	public static function get_product_addons( $_product ) {

		// Tweak for variations because PAO sometimes names addons with parent product ID and sometimes with variation ID.
		$_product_id = $_product->is_type( 'variation' ) ? $_product->get_parent_id() : $_product->get_id();

	    // Get product add-ons
	    $product_addons = \WC_Product_Addons_Helper::get_product_addons( $_product_id );

	    $addons = array();

	    if ( ! empty( $product_addons ) ) foreach ( $product_addons as $index => $addon ) {

	        // PAO 3.0.0 uses 'field_name'
	        $field_name = isset( $addon['field_name'] ) ? $addon['field_name'] : $addon['field-name'];

	        if ( $addon['type'] === 'multiple_choice' || $addon['type'] === 'checkbox' ) {

	            foreach ( $addon['options'] as $index => $option ) {
	 
	                $addons[$field_name][$index] = array(
	                    'multiply' => isset( $option['multiply'] ) ? $option['multiply'] : 0,
	                    'type'     => isset( $option['price_type'] ) ? $option['price_type'] : 'flat_fee'
	                );
	 
	            }

	        } else {

	            $addons[$field_name][] = array(
	                'multiply' => isset( $addon['multiply_by_booking_duration'] ) ? $addon['multiply_by_booking_duration'] : 0,
	                'type'     => isset( $addon['price_type'] ) ? $addon['price_type'] : 'flat_fee'
	            );

	        }

	    }

	    return $addons;

	}

	/**
	*
	* Get total additional cost from selected product Add-Ons.
	* @param WC_Product | WC_Product_Variation - $_product - Product or variation object
	* @param int - $duration
	* @param float - $price
	* @return float - $addons_cost
	*
	**/
	public static function get_selected_addons_cost( $_product, $data, $price ) {

	    // Get additional costs
	    $selected_addons = isset( $_POST['additional_cost'] ) ? $_POST['additional_cost'] : array();     

	    // Get product addons
	    $product_addons = self::get_product_addons( $_product );

	    if ( ! $product_addons || empty( $product_addons ) || empty( $selected_addons ) ) {
	        return false;
	    }

	    $addons_cost = 0;
	    foreach ( $selected_addons as $addon_field_name => $addon ) {

	        if ( ! is_array( $addon ) ) {
	            continue;
	        }

	        // Sanitize
	        $addon = array_map( 'floatval', $addon );

	        foreach ( $addon as $index => $cost ) {

	            if ( isset( $product_addons[$addon_field_name] ) ) {

	                // Multiply addon cost by booking duration?
	                $mutltiply  = isset( $product_addons[$addon_field_name][$index]['multiply'] ) ? absint( $product_addons[$addon_field_name][$index]['multiply'] ) : 0;
	                
	                // Get addon type (percentage base or flat fee)
	                $addon_type = $product_addons[$addon_field_name][$index]['type'];

	                // Calc addon cost
	                $cost = self::calc_addon_cost( $cost, $addon_type, $price, $data['duration'], $data['quantity'], $mutltiply );

	                // Store total addons costs for each product ID
	                $addons_cost += $cost;

	            }

	        }

	    }

	    return apply_filters( 'easy_booking_pao_total_addons_cost', floatval( $addons_cost ), $_product );
	    
	}

	/**
	*
	* Calculate add-on cost after selecting dates.
	* @param float - $addon_cost
	* @param str - $addon_type
	* @param float - $price
	* @param int - $duration
	* @param int - $quantity
	* @param bool - $multiply
	* @return float - $addon_cost
	*
	**/
	public static function calc_addon_cost( $addon_cost, $addon_type, $price, $duration, $quantity, $multiply ) {

	    // Backward compatibility - Pass true to filter to multiply additional costs by booking duration (default: false)
	    if ( ! $multiply && true === apply_filters( 'easy_booking_multiply_additional_costs', false ) ) {
	        $multiply = 1;
	    }

	    // Calculate percentage based addon cost.
	    if ( $addon_type === 'percentage_based' ) {
	        $addon_cost = ( $price * $addon_cost ) / 100;
	    }

	    // Flat fees are only applied one, so we need to divide total fee by number of items.
	    if ( $addon_type === 'flat_fee' ) {
	    	$addon_cost /= $quantity;
	    }

	    // Maybe multiply by booking duration.
	    if ( $multiply && $addon_type !== 'percentage_based' ) {
	        $addon_cost *= $duration;
	    }

	    return apply_filters( 'easy_booking_pao_addon_cost', floatval( $addon_cost ), $duration, $multiply );

	}

}