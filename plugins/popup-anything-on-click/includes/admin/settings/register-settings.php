<?php
/**
 * Register Settings
 *
 * Handles the Admin side setting options functionality of module
 *
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get settings tab
 * 
 * @since 1.4
 */
function popupaoc_settings_tab() {

	// Plugin settings tab
	$sett_tabs = array(
					'general'		=> __( 'General', 'popup-anything-on-click' ),
					'display_rule'	=> __( 'Display Rule', 'popup-anything-on-click' ),
					'integration'	=> __( 'Integration', 'popup-anything-on-click' ).' <span class="paoc-pro-tag">'. esc_html__('PRO','popup-anything-on-click').'</span>',
				);

	return apply_filters( 'popupaoc_settings_tab', (array)$sett_tabs );
}

/**
 * Function to register plugin settings
 * 
 * @since 1.4
 */
function popupaoc_register_settings() {

	// Reset default settings
	if( ! empty( $_POST['popupaoc_reset_settings'] ) && check_admin_referer( 'popupaoc_reset_setting', 'popupaoc_reset_sett_nonce' ) ) {
		popupaoc_default_settings();
	}

	register_setting( 'popupaoc_plugin_options', 'popupaoc_options', 'popupaoc_validate_options' );
}

// Action to register plugin settings
add_action( 'admin_init', 'popupaoc_register_settings' );

/**
 * Validate Settings Options
 * 
 * @since 1.4
 */
function popupaoc_validate_options( $input ) {

	global $popupaoc_options;

	$input = $input ? $input : array();

	// Pull out the tab and section
	if ( isset ( $_POST['_wp_http_referer'] ) ) {
		parse_str( wp_unslash( $_POST['_wp_http_referer'] ), $referrer ); // WPCS: input var ok, CSRF ok, sanitization ok.
	}

	$tab = isset( $referrer['tab'] ) ? popupaoc_clean( $referrer['tab'] ) : 'general';

	// Run a general sanitization for the tab for special fields
	$input = apply_filters( 'popupaoc_sett_sanitize_'.$tab, $input );

	// Run a general sanitization for the custom created tab
	$input = apply_filters( 'popupaoc_sett_sanitize', $input, $tab );

	// Making merge of old and new input values
	$input = array_merge( $popupaoc_options, $input );

	return $input;
}

/**
 * Filter to validate general settings
 * 
 * @since 1.4
 */
function popupaoc_sanitize_general_sett( $input ) {

	$input['enable']		= isset( $input['enable'] )				? 1											: 0;
	$input['cookie_prefix']	= ! empty( $input['cookie_prefix'] )	? popupaoc_clean( $input['cookie_prefix'] )	: 'paoc_popup';
	$input['add_js']		= ! empty( $input['add_js'] )			? popupaoc_clean_number( $input['add_js'] )	: '';

	return $input;
}
add_filter( 'popupaoc_sett_sanitize_general', 'popupaoc_sanitize_general_sett' );

/**
 * Filter to validate display rule settings
 * 
 * @since 1.4
 */
function popupaoc_sanitize_display_rule_sett( $input ) {

	$input['welcome_popup']			= ! empty( $input['welcome_popup'] )		? popupaoc_clean_number( $input['welcome_popup'] )	: '';
	$input['welcome_display_in']	= ! empty( $input['welcome_display_in'] )	? popupaoc_clean( $input['welcome_display_in'] )	: array();

	return $input;
}
add_filter( 'popupaoc_sett_sanitize_display_rule', 'popupaoc_sanitize_display_rule_sett' );