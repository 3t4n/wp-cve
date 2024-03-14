<?php
/*
Plugin Name: Administrator Z
Description: <a href="https://webkhoinghiep.net">Webkhoinghiep.net</a> Theme WP chuẩn Seo - Dễ dàng cài đặt - GIao diện kéo thả - Dễ tùy biến và chỉnh sửa. Hỗ trợ cài đặt - 5p có ngay website - Có video Hướng dẫn sử dụng - Dễ dàng thao tác.
Version: 2024.03.12
Author: Quyle91
Author URI: https://quyle91.github.io/
License: GPLv2 or later
Text Domain: administrator-z
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('ADMINZ',true);
define('ADMINZ_DIR', plugin_dir_path( __FILE__ ));
define('ADMINZ_BASENAME', plugin_basename(__FILE__));
define('ADMINZ_DIR_URL', plugin_dir_url( __FILE__ ));

require_once( trailingslashit( ADMINZ_DIR ) . 'autoload/autoloader.php' );




new Adminz\Admin\Adminz;
new Adminz\Admin\ADMINZ_PluginOptions;
new Adminz\Admin\ADMINZ_DefaultOptions;
new Adminz\Admin\ADMINZ_Enqueue;
new Adminz\Admin\ADMINZ_ContactGroup;
new Adminz\Admin\ADMINZ_Crawler;
new Adminz\Admin\ADMINZ_Mailer;
new Adminz\Admin\ADMINZ_Security;
new Adminz\Admin\ADMINZ_Icons;
new Adminz\Admin\ADMINZ_OtherTools;
new Adminz\Admin\ADMINZ_Me;



// plugins intergration
add_action('plugins_loaded', function(){
	new Adminz\Admin\ADMINZ_ACF;
	new Adminz\Admin\ADMINZ_CF7;
	new Adminz\Admin\ADMINZ_Flatsome;
	new Adminz\Admin\ADMINZ_Woocommerce;
	new Adminz\Admin\ADMINZ_Elementor;
});


add_action('init',function(){
	load_plugin_textdomain( 'administrator-z', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});

add_filter('body_class',function($classes){
	$classes[] = 'administrator-z';
	return $classes;
},10,1);
