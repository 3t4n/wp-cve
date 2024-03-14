<?php
/**
 * Template Backend Singles.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for backend singles.
function eos_dp_admin_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-backend-singles.php';
	$page = new FDP_Backend_Singles_Page( 'eos_dp_admin' );
	return;
}
