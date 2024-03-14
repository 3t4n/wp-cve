<?php

if ( ! defined( 'ABSPATH' ) ) exit;

	add_action('admin_menu', 'lbx_menu');
	



function lbx_menu()
{
    add_menu_page('Lightbox Pop Settings', 'XYZ Lightbox', 'manage_options', 'lightbox-popup-settings', 'lbx_settings',plugin_dir_url(XYZ_LBX_PLUGIN_FILE).'images/lightbox-popup.png');
	// Add a submenu to the Dashboard:
	$page=add_submenu_page('lightbox-popup-settings', 'Lightbox Pop Settings', 'Lightbox Popup', 'manage_options', 'lightbox-popup-settings' ,'lbx_settings'); // 8 for admin
	add_submenu_page('lightbox-popup-settings','Lightbox Pop - Basic  settings', 'Basic Settings', 'manage_options', 'lightbox-basic-settings', 'lbx_basic_settings');
		add_submenu_page('lightbox-popup-settings', 'Lightbox Popup - About', 'About', 'manage_options', 'lightbox-popup-about' ,'lbx_about'); // 8 for admin
	
	
	add_action( "admin_print_scripts-$page", 'lbx_farbtastic_script' );
	add_action( "admin_print_styles-$page", 'lbx_farbtastic_style' );
	
}

function lbx_basic_settings()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

function lbx_settings()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/lightbox-settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}


function lbx_about()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/about.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}




function lbx_farbtastic_script() 
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('farbtastic');

}

function lbx_farbtastic_style() 
{
	wp_enqueue_style('farbtastic');
}
function xyz_lbx_admin_style()
{
	//require( dirname( __FILE__ ) . '/style.php' );
	wp_enqueue_script('jquery');
	wp_register_style('xyz_lbx_style', plugins_url('lightbox-pop/css/style.css'));
	wp_enqueue_style('xyz_lbx_style');
	wp_register_script( 'xyz_notice_script_lbx', plugins_url('lightbox-pop/js/notice.js') );
	wp_enqueue_script( 'xyz_notice_script_lbx' );
	

}


	
	add_action('admin_enqueue_scripts', 'xyz_lbx_admin_style');



?>
