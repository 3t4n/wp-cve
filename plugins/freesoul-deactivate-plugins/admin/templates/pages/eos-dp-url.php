<?php
/**
 * Template Frontend URLs.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivate by URL settings page.
function eos_dp_by_url_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-custom-rows.php';
	$page = new FDP_Custom_Rows_Page( 'eos_dp_url' );
	return;
}
