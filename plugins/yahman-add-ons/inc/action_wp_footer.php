<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_footer', 'yahman_addons_wp_footer' );
function yahman_addons_wp_footer(){

	$option = get_option('yahman_addons') ;

	
	if(isset($option['json']['breadcrumblist'])){
		require_once YAHMAN_ADDONS_DIR . 'inc/json_breadcrumb.php';
		yahman_addons_json_breadcrumb();
	}

	
	if(isset($option['json']['page'])){
		require_once YAHMAN_ADDONS_DIR . 'inc/json_structured_data_page.php';
		yahman_addons_json_structured_data_page();
	}

}
