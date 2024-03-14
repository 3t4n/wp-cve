<?php

require_once TBLIGHT_PLUGIN_PATH . 'classes/booking.helper.php';

/**
 * Init plugin
 *
 * @return void
 */
function init_tblight() {
	require_once TBLIGHT_PLUGIN_PATH . 'Assets.php';
	new TBLight_Assets();

	if ( is_admin() ) {
		require_once TBLIGHT_PLUGIN_PATH . 'Admin.php';
		TBLight_Admin::get_instance();
	} else {
		require_once TBLIGHT_PLUGIN_PATH . 'Shortcode.php';
		new TBLight_Shortcode();
	}

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		require_once TBLIGHT_PLUGIN_PATH . 'Ajax.php';
		new TBLight_Ajax();
	}
}

function tblight_output_buffer() {
	ob_start();
}
