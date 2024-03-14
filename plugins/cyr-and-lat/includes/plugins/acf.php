<?php
/**
 * Plugin integration Advanced custom fields
 * Our integration allows translating arbitrary field names in the Advanced custom fields plugin.
 *
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 11.12.2018, Webcraftic
 * @version 1.0
 */

defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

function wbcr_ctlr_acf_scripts() {
	global $pagenow;
	
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	
	$on_acf_edit_page = 'post.php' === $pagenow && isset( $_GET['post'] ) && 'acf-field-group' === get_post_type( $_GET['post'] );
	if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) and $on_acf_edit_page ) {
		$data = "window.cyr_and_lat_dict = " . json_encode( wbcr_ctlr_get_symbols_pack() ) . ";";
		
		wp_enqueue_script( 'wbcr-cyr-to-lat-acf', WCTLR_PLUGIN_URL . '/assets/js/cyr-and-lat-acf.js', array(
			'jquery',
			'acf-field-group'
		) );
		wp_add_inline_script( 'wbcr-cyr-to-lat-acf', $data, 'before' );
	}
}

add_action( 'admin_init', 'wbcr_ctlr_acf_scripts' );