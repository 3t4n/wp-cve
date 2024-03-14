<?php
/*
 * Executes all the necessary classes for attributes
 *
 * @since 4.7
 *
 * */

use WebAppick\Attributes\AvailabilityDate;

// Security Check
defined( 'ABSPATH' ) || die();


$feed_settings = get_option( 'woo_feed_settings' );

$availability_date_settings = isset( $feed_settings['woo_feed_identifier']['availability_date'] )
    ? $feed_settings['woo_feed_identifier']['availability_date']
    : 'enable' ;

// Availability Date
if ( $availability_date_settings === 'enable' ) {
    new AvailabilityDate();
}
