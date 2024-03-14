<?php
/**
 * Plugin Name: Second Street
 * Description: Plugin will allow Second Street Affiliates to embed a Second Street Promotion within their WordPress site(s).
 * Version: 3.1.12
 * Author: Second Street
 * Author URI: http://secondstreet.com
 * License: GPL2
 */

/*  Copyright 2022  Second Street (email : wordpressdevelopment@uplandsoftware.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/

// Blocks direct access to plugin
defined( 'ABSPATH' ) or die( "Access Forbidden" );

defined( 'ABSPATH' ) or die( "Access Forbidden" ); // Blocks direct access to plugin

// Define Second Street Plugin
define( 'SECONDSTREET_PLUGIN_VERSION', '1.0' );
define( 'SECONDSTREET_PLUGIN__MINIMUM_WP_VERSION', '3.1' );
define( 'SECONDSTREET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SECONDSTREET_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// [ss-promo] Code
function ss_promo_func( $atts, $content = null ) {
	$a = shortcode_atts( array (
			'op_id' => '',
			'op_guid' => '',
			'routing' => '',
			'dev' => '',
			'top_offset' => '0',
			'bottom_offset' => '0'
		), $atts );

	$ss_script_url_prefix = 'https://embed';
	$ss_script_url_suffix = '.secondstreetapp.com/Scripts/dist/embed.js';

	if ( $a['dev'] === 'true' ) {
		return '<script src="' . esc_url( $ss_script_url_prefix . 'dev' . $ss_script_url_suffix ) . '" data-ss-embed="promotion" data-opguid="' . esc_attr( $a['op_guid'] ) . '" data-routing="' . esc_attr( $a['routing'] ) . '" data-top-offset="' . esc_attr( $a['top_offset'] ) . '" data-bottom-offset="' . esc_attr( $a['bottom_offset'] ) . '"></script>';
	} else {
		return '<script src="' . esc_url( $ss_script_url_prefix . '-' . $a['op_id'] . $ss_script_url_suffix ) . '" data-ss-embed="promotion" data-opguid="' . esc_attr( $a['op_guid'] ) . '" data-routing="' . esc_attr( $a['routing'] ) . '" data-top-offset="' . esc_attr( $a['top_offset'] ) . '" data-bottom-offset="' . esc_attr( $a['bottom_offset'] ) . '"></script>';
	}
}

// [ss-signup] Code
function ss_signup_func( $atts, $content = null ) {
	$a = shortcode_atts( array (
			'design_id' => ''
		), $atts );

	$ss_script_url = 'https://embed.secondstreetapp.com/Scripts/dist/optin.js';

	return '<script src="' . esc_url( $ss_script_url ) . '" data-ss-embed="optin" data-design-id="' . esc_attr( $a['design_id'] ) . '"></script>';

}

// [ss-contest] Code
function ss_contest_func( $atts, $content = null ) {
	$a = shortcode_atts( array (
			'contest_url' => '',
			'contest_folder' => '',
			'routing' => '',
			'contest_id' => ''
	), $atts );

	$ss_script_url =  $a['contest_url'] . '/' . 'shared/embedcode/embed.js';

	if (esc_attr( $a['contest_id'] ) != '') { //Old Engine contests will pass contest_id
		return '<script type="text/javascript" src="' . esc_attr( $a['contest_url'] ) . '/shared/embedcode/talker-v1.0.0.js"></script><script src="' . esc_url( $ss_script_url ) . '" data-ss-embed="contest" data-routing="' . esc_attr( $a['routing'] ) . '" data-contest-id="' . esc_attr( $a['contest_id'] ) . '"></script>';
	} else {
		return '<script type="text/javascript" src="' . esc_attr( $a['contest_url'] ) . '/shared/embedcode/talker-v1.0.0.js"></script><script src="' . esc_url( $ss_script_url ) . '" data-ss-embed="contest" data-routing="' . esc_attr( $a['routing'] ) . '"></script>';
	}

}

// [ss-feed] Code
function ss_feed_func( $atts, $content = null ) {
	if (empty($atts['organization_id'])) {
		return 'Error: No organization_id parameter defined on the shortcode.';
	}

	$a = shortcode_atts( array (
		'organization_id' => '',
		'dev' => ''
	), $atts );

	$ss_script_url_prefix = 'https://o-' . $a['organization_id'];
	$ss_script_url_suffix = '.secondstreetapp.com/Scripts/dist/feed.js';

	if ( $a['dev'] === 'true' ) {
		return '<script src="' . esc_url( $ss_script_url_prefix . '.dev' . $ss_script_url_suffix ) . '" data-ss-embed="feed" data-organization-id="' . esc_attr( $a['organization_id'] ) . '"></script>';
	} else {
		return '<script src="' . esc_url( $ss_script_url_prefix . $ss_script_url_suffix ) . '" data-ss-embed="feed" data-organization-id="' . esc_attr( $a['organization_id'] ) . '"></script>';
	}
}

// [ss-preferences] Code
function ss_preferences_func( $atts, $content = null ) {
	if (empty($atts['organization_id'])) {
		return 'Error: No organization_id parameter defined on the shortcode.';
	}

	$a = shortcode_atts( array (
		'organization_id' => '',
	), $atts );

	$ss_script_url = 'https://embed.secondstreetapp.com/Scripts/dist/preferences.js';

	return '<script src="' . esc_url( $ss_script_url ) . '" data-ss-embed="preferences" data-organization-id="' . esc_attr( $a['organization_id'] ) . '"></script>';
}

add_shortcode( 'ss-promo', 'ss_promo_func' );
add_shortcode( 'ss-signup', 'ss_signup_func' );
add_shortcode( 'ss-contest', 'ss_contest_func' );
add_shortcode( 'ss-feed', 'ss_feed_func' );
add_shortcode( 'ss-preferences', 'ss_preferences_func' );
