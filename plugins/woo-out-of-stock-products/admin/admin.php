<?php
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
//Hey there guy.

//include_once('admin/woo-settings-tab.php');
//include_once('admin/class-outofstock-settings.php');
include_once('woo-settings-tab.php');
include_once('class-outofstock-settings.php');


add_action( 'init', 'register_admin_scripts' );

function register_admin_scripts() {
	//wp_register_script( 'admin_outofstock_js', plugins_url('/inc/admin-outofstock.js', __FILE__), array('jquery'));
	wp_register_style( 'admin_outofstock_css', plugins_url('/inc/admin-outofstock.css', __FILE__));
	//wp_enqueue_script( 'admin_outofstock_js' );
	wp_enqueue_style( 'admin_outofstock_css' );
}