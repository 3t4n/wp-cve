<?php
/**
* Fired when the plugin is uninstalled.
*
* @package Swiper_Js_Slider
* @author M Faraz Ali <mfarazaly@gmail.com>
* @license GPL-2.0+
* @link http://mfarazali.wordpress.coom
* @copyright 2019 M Faraz Ali ($p33dy)
*/

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {	exit;}
global $wpdb;

$swiper_slide = get_posts( array( 'post_type' => 'swiper_js_slides', 'numberposts' => -1 ) );
foreach( $swiper_slide as $slides ) {
	wp_delete_post( $slides->ID, true );
}

foreach ( wp_load_alloptions() as $option => $value ) {
    if ( strpos( $option, '_s_s_m_' ) === 0 ) {
        delete_option( $option );
    }
}