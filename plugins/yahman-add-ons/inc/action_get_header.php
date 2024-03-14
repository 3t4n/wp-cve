<?php
defined( 'ABSPATH' ) || exit;

add_action( 'get_header', function() {

	if (!is_singular() || is_robots() || is_user_logged_in()) return;

	$option = get_option('yahman_addons');

	if(isset($option['pv']['enable'])){
		require_once YAHMAN_ADDONS_DIR . 'inc/page_view.php';
		yahman_addons_page_view( get_the_ID() );
	}

});

