<?php
/**
 * Template Post Types.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for deactivate by post type settings page.
function eos_dp_by_post_type_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-post-types.php';
	$page = new FDP_Post_Types_Page( 'eos_dp_by_post_type' );
	return;
}
