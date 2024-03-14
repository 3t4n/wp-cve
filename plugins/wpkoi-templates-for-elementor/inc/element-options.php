<?php
/**
 * Element options for admin.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


add_action( 'admin_init', 'wpkoi_templates_for_elementor_lite_wtfe_submit', 5 );
/**
 * Process our element options.
 */
function wpkoi_templates_for_elementor_lite_wtfe_submit() {
	// Has our button been clicked?
	if ( isset( $_POST[ 'wtfe_submit' ] ) ) {

		// If we're not an administrator, bail.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		$wtfe_element_effects_p		= $_POST['wtfe_element_effects'];

		$wtfe_advanced_headings_p 	= $_POST['wtfe_advanced_headings'];
		$wtfe_countdown_p 			= $_POST['wtfe_countdown'];
		$wtfe_darkmode_p 			= $_POST['wtfe_darkmode'];
		$wtfe_qr_code_p 			= $_POST['wtfe_qr_code'];
		
		$wtfe_element_effects 		= get_option( 'wtfe_element_effects', '' );
		
		$wtfe_advanced_headings 	= get_option( 'wtfe_advanced_headings', '' );
		$wtfe_countdown 			= get_option( 'wtfe_countdown', '' );
		$wtfe_darkmode			 	= get_option( 'wtfe_darkmode', '' );
		$wtfe_qr_code 				= get_option( 'wtfe_qr_code', '' );

		// Still here? Update our option with the new values
		update_option( 'wtfe_element_effects', $wtfe_element_effects_p );
		
		update_option( 'wtfe_advanced_headings', $wtfe_advanced_headings_p );
		update_option( 'wtfe_countdown', $wtfe_countdown_p );
		update_option( 'wtfe_darkmode', $wtfe_darkmode_p );
		update_option( 'wtfe_qr_code', $wtfe_qr_code_p );

		wp_safe_redirect( admin_url( 'admin.php?page=wpkoi-templates-for-elementor/wpkoi-templates.php' ) );
	}
}