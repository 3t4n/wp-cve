<?php
/*
Plugin Name: wp-pano
Plugin URI: wp-pano.yuzhakov.org
Description:  The content manager for the krpano Panorama Viewer
Author: Alexey Yuzhakov
Version: 1.17
Author URI: https://www.facebook.com/a.m.yuzhakov
*/

if (!function_exists('add_action')) die('Access denied');

define('WPPANO_VERSION', '1.17');
define('WPPANO_MIN_WP_VERSION', '4');
define('SITE_HOMEPATH', addslashes(parse_url (get_site_url(), PHP_URL_PATH) . '/'));
//define('SITE_HOMEPATH', addslashes(substr(realpath(ABSPATH), strlen(realpath($_SERVER['DOCUMENT_ROOT']))+1) . '/'));
define('WPPANO_BASEFOLDER', '/' . dirname( plugin_basename(__FILE__)));		//	/wp-pano
define('WPPANO_URL', WP_PLUGIN_URL . WPPANO_BASEFOLDER);		//	http://yourdomaindotcom/wp-content/plugins/wp-pano
define('WPPANO_BASEFILE', __FILE__);	//   /var/www/yourdomaindotcom/wp-content/plugins/wp-pano/wp-pano.php
define('WPPANO_BASEDIR', __DIR__);		//   /var/www/yourdomaindotcom/wp-content/plugins/wp-pano
define('WPPANOPATH_PLUGIN', wp_normalize_path(substr(WPPANO_BASEDIR, strpos(WPPANO_BASEDIR, "wp-content"))));
define('WPPANOPATH_THEME', wp_normalize_path(substr(get_template_directory(), strpos(get_template_directory(), "wp-content"))));

include_once('inc/db.php');
include_once('inc/front.php');
if( is_admin() ) include_once('inc/admin.php');

add_action( 'wp_enqueue_scripts', 'wppano_scripts_method' );  //frontend scripts
function wppano_scripts_method() {
	wp_enqueue_style( 'dashicons' );
	if( file_exists(get_template_directory() . '/wp-pano/style.css')) 
		wp_register_style( 'wppano_style', get_template_directory_uri() . '/wp-pano/style.css' );
	else
		wp_register_style( 'wppano_style', WPPANO_URL . '/style.css' );
    wp_enqueue_style( 'wppano_style' );
	wp_register_script( "wppano_front_script", WPPANO_URL . '/js/front.js', array('jquery') );
	wp_localize_script( 'wppano_front_script', 'ajax', 
		array( 
			'url' => admin_url( 'admin-ajax.php' ), 
			'nonce' => wp_create_nonce('wppano-nonce')
		)
	);	
	wp_enqueue_script('wppano_front_script');	
}

add_action('wp_head','wp_pano_hook_js');

function wp_pano_hook_js() {
	ob_start();
	require_once('inc/view/wp-pano-head.php');
	$output = ob_get_clean();
	echo $output;
}

add_action( 'admin_enqueue_scripts', 'wppano_admin_scripts_method' );  //admin scripts
function wppano_admin_scripts_method() {
    wp_register_style( 'wppano_prefix_style', WPPANO_URL.'/style-admin.css' );
    wp_enqueue_style( 'wppano_prefix_style' );	
	wp_register_script( "wppano_front_script", WPPANO_URL.'/js/front.js', array('jquery') );
	wp_localize_script( 'wppano_front_script', 'ajax', 
		array( 
			'url' => admin_url( 'admin-ajax.php' ), 
			'nonce' => wp_create_nonce('wppano-nonce')
		)
	);	
	wp_enqueue_script('wppano_front_script');	
} 

?>