<?php
/**
 * Template Terms Archives.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivate by archive settings page.
function eos_dp_by_term_archive_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-terms-archives.php';
	$page = new FDP_Terms_Archives_Page( 'eos_dp_by_term_archive' );
	return;
}
