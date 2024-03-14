<?php
defined( 'ABSPATH' ) || exit;

if ( is_admin() ) {

	include_once __DIR__ . '/inc/assets.class.php';
	include_once __DIR__ . '/inc/cmb2-tabs.class.php';

	// connection css and js
	new cmb2_XL_TABS_Assets();

	// run global hooks
	cmb2_XL_Tabs::get_instance();
}
