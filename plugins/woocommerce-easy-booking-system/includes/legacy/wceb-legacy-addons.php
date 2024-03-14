<?php

/**
*
* Functions for old add-ons (before PRO version).
* @version 3.0.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Check if at least on add-on is active.
* @return bool
*
**/
function wceb_addons_installed() {

	$active_plugins = (array) get_option( 'active_plugins', array() );

    if ( is_multisite() ) {
        $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
    }

    $addons = array(
    	'easy-booking-availability-check/easy-booking-availability-check.php',
    	'easy-booking-duration-discounts/easy-booking-duration-discounts.php',
    	'easy-booking-disable-dates/easy-booking-disable-dates.php',
    	'easy-booking-pricing/easy-booking-pricing.php'
    );

    return count( array_intersect( $addons, $active_plugins ) ) > 0;

}