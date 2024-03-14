<?php
/**
 * Template Search.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivatin plugins for the search version.
function eos_dp_search_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-one-column-page.php';
	$page = new Eos_Fdp_One_Column_Page( 'eos_dp_search', esc_attr__( 'Search results page.', 'freesoul-deactivate-plugins' ), 'search' );
	return;
}
