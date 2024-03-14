<?php
/**
 * Template Create Plugin.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// It adds the section for creating a new plugin.
function eos_dp_create_plugin_callback() {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-plugin-factory.php';
	$plugin_factory = new FDP_Plugin_Factory( 'create-plugin' );
}
