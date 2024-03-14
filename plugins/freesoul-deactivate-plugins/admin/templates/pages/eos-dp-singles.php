<?php
/**
 * Template Singles.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback function for the plugin settings page.
function eos_dp_options_page_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-frontend-singles.php';
	$page = new FDP_Frontend_Singles( 'eos_dp_menu' );
	return;
}
