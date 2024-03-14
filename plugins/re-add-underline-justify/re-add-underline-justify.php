<?php
/*
 * Plugin Name: Re-add text underline and justify
 * Plugin URI: https://www.b-website.com/re-add-text-underline-and-justify
 * Description: Re-adds the Editor text underline & justify buttons in the WYSIWYG removed in WordPress 4.7. Works with Classic Editor, ACF and Gutenberg.
 * Author: Brice Capobianco
 * Version: 0.4.1
 * Author URI: https://www.b-website.com/
 * Domain Path: /langs
 * Text Domain: re-add-underline-justify
 */

/*  Copyright 2019  Brice CAPOBIANCO  (contact : http:// b-website.com/contact)

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
*/


/*
 * SECURITY : Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access not allowed!' );
}


/*
 * Add custom meta link on plugin list page
 */
add_filter( 'plugin_row_meta', 'ratb_meta_links', 10, 2 );
function ratb_meta_links( $links, $file ) {

	if ( $file === 're-add-underline-justify/re-add-underline-justify.php' ) {
		$paypal_donate = 'https://www.paypal.me/BriceCapobianco';
		$links[]       = '<a href="https://www.b-website.com/category/plugins" target="_blank" title="' . __( 'More b*web Plugins', 'simple-revisions-delete' ) . '">' . __( 'More b*web Plugins', 'simple-revisions-delete' ) . '</a>';
		$links[]       = '<a href="' . $paypal_donate . '" target="_blank" title="' . __( 'Donate to this plugin &#187;' ) . '"><strong>' . __( 'Donate to this plugin &#187;' ) . '</strong></a>';
	}
	return $links;

}


/*
 * Load plugin textdomain
 */
add_action( 'init', 'ratb_load_textdomain' );
function ratb_load_textdomain() {

	$path = dirname( plugin_basename( __FILE__ ) ) . '/langs/';
	load_plugin_textdomain( 're-add-underline-justify', false, $path );

}


/*
 * Remove plugin settings from DB on plugin deletion
 */
function ratb_uninstall() {

	// Remove option from DB
	delete_option( 'ratb_options' );

}


/*
 * Hooks for install & uninstall
 */
register_activation_hook( __FILE__, 'ratb_activation' );
function ratb_activation() {

	register_uninstall_hook( __FILE__, 'ratb_uninstall' );

}


/*
 * Register the new setting on the Wrinting screen
 */
add_action( 'admin_init', 'ratb_admin_init' );
function ratb_admin_init() {

	register_setting(
		'writing',                                          // settings page
		'ratb_options'                                      // option name
	);
	add_settings_field(
		'ratb_mce_style',                                   // id
		__( 'Editor style', 're-add-underline-justify' ),   // setting title
		'ratb_setting_input',                               // display callback
		'writing',                                          // settings page
		'default'                                           // settings section
	);

}


/*
 * Display the select on the Wrinting screen
 */
function ratb_setting_input() {

	//Retrieve the option value
	$options = get_option( 'ratb_options' );

	//Default value
	if ( empty( $options ) ) {
		$options['ratb_mce_style'] = 2;
	}

	// The option "Re-add underline & justify + rearrange" has been deprecated in 0.2
	// So we replace option 3 with 2 if the former was selected.
	if ( ! empty( $options ) && $options['ratb_mce_style'] == 3 ) {
		$options['ratb_mce_style'] = 2;
	}

	// Output the field
	echo '	
	<select id="ratb_mce_style" name="ratb_options[ratb_mce_style]">
		<option value="1"' . selected( $options['ratb_mce_style'], 1, false ) . '>' . __( 'Without underline & justify buttons', 're-add-underline-justify' ) . '</option>
		<option value="2"' . selected( $options['ratb_mce_style'], 2, false ) . '>' . __( 'Default - Re-add underline & justify buttons', 're-add-underline-justify' ) . '</option>
		<option value="4"' . selected( $options['ratb_mce_style'], 4, false ) . '>' . __( 'Re-add justify only', 're-add-underline-justify' ) . '</option>
	</select>';

}


/*
 * Update tinyMCE buttons lines
 */
add_action( 'admin_init', 'ratb_buttons_lines_tiny_mce' );
function ratb_buttons_lines_tiny_mce() {

	//Retrieve the option value
	$options = get_option( 'ratb_options' );

	// Conditionnal MCE display
	if ( ! isset( $options['ratb_mce_style'] ) || isset( $options['ratb_mce_style'] ) && ( $options['ratb_mce_style'] == 2 || $options['ratb_mce_style'] == 3 ) ) {

		// The option "Re-add underline & justify + rearrange" has been deprecated in 0.2
			// So we replace option 3 with 2 if the former was selected.
		add_filter( 'mce_buttons', 'ratb_tiny_mce_buttons_justify', 5 );
		add_filter( 'mce_buttons_2', 'ratb_tiny_mce_buttons_underline', 5 );

	} elseif ( isset( $options['ratb_mce_style'] ) && $options['ratb_mce_style'] == 4 ) {

		add_filter( 'mce_buttons', 'ratb_tiny_mce_buttons_justify', 5 );

	}
	//Else, do nothing... use the default editor style

}


/*
 * First editor row buttons - Re-add underline
 */
function ratb_tiny_mce_buttons_underline( $buttons_array ) {

	if ( ! in_array( 'underline', $buttons_array ) ) {
		$inserted = array( 'underline' );
		// We add the button at the begining of the second line
		array_splice( $buttons_array, 0, 0, $inserted );
	}

	return $buttons_array;

}

/*
 * First editor row buttons - Re-add justify
 */
function ratb_tiny_mce_buttons_justify( $buttons_array ) {

	if ( ! in_array( 'alignjustify', $buttons_array ) && in_array( 'alignright', $buttons_array ) ) {
		$key      = array_search( 'alignright', $buttons_array );
		$inserted = array( 'alignjustify' );
		array_splice( $buttons_array, $key + 1, 0, $inserted );
	}

	return $buttons_array;

}
