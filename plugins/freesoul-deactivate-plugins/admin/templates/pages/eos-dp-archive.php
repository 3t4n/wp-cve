<?php
/**
 * Template Archives.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivate by archive settings page.
function eos_dp_by_archive_callback() {
	wp_nonce_field( 'eos_dp_key', 'eos_dp_key' );
	wp_nonce_field( 'eos_dp_pro_gpsi_test', 'eos_dp_pro_gpsi_test' );
	wp_nonce_field( 'eos_dp_pro_gt_metrix_test', 'eos_dp_pro_gt_metrix_test' );
	if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ) {
		wp_nonce_field( 'eos_dp_pro_auto_settings', 'eos_dp_pro_auto_settings' );
	}
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-archives.php';
	$page = new FDP_Archives_Page( 'eos_dp_by_archive' );
	return;
}
