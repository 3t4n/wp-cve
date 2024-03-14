<?php
/**
 * Template Hireus.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for Hiring Us page.
function eos_dp_hireus_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-hireus.php';
	$page = new FDP_HireUs_Page( 'eos_dp_by_archive' );
	return;
}
