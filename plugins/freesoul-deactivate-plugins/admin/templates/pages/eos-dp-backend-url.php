<?php
/**
 * Template Backend URLs.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivating by admin URL settings page.
function eos_dp_by_admin_url_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-custom-rows.php';
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-backend-urls.php';
	$page = new FDP_Backend_Urls_Page( 'eos_dp_admin_url' );
	return;
}
