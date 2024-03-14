<?php
defined( 'ABSPATH' ) || exit;



function yahman_addons_add_menu() {
	$yahman_addons_admin_page = add_options_page(esc_html__('Setting Up YAHMAN Add-ons','yahman-add-ons'),'YAHMAN Add-ons', 'administrator', 'yahman-add-ons','yahman_addons_admin_page');

	
	require_once YAHMAN_ADDONS_DIR . 'inc/enqueue.php';

	add_action( "admin_print_scripts-$yahman_addons_admin_page", 'yahman_addons_admin_page_scripts' );
}
add_action( 'admin_menu', 'yahman_addons_add_menu' );


function yahman_addons_admin_page() {

	if (!current_user_can('manage_options')){
		wp_die( esc_html__('You do not have sufficient permissions to access this page.','yahman-add-ons') );
	}

	require_once YAHMAN_ADDONS_DIR . 'inc/admin_menu.php';

}


load_plugin_textdomain( 'yahman-add-ons', false, dirname( plugin_basename( YAHMAN_ADDONS_PLUGIN_FILE ) ) .'/languages/' );


$option =  get_option('yahman_addons') ;


$profile['user_profile'] = isset($option['profile']['user_profile']) ? true: false;

if($profile['user_profile'])
	require_once YAHMAN_ADDONS_DIR . 'inc/user_profile.php';

$header['meta_description'] = isset($option['header']['meta_description']) ? true: false;
if($header['meta_description']){
	require_once YAHMAN_ADDONS_DIR . 'inc/classes/header_meta.php';

	new YAHMAN_ADDONS_ADD_META_TAGS();
}


require_once YAHMAN_ADDONS_DIR . 'inc/quick_tag.php';



add_action( 'after_switch_theme', function(){
	require_once YAHMAN_ADDONS_DIR . 'inc/action_after_switch_theme.php';
	yahman_addons_after_switch_theme();
}
);
