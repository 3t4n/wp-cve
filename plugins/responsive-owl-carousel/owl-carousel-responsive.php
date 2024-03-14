<?php
/**
 * Plugin Name: Owl carousel responsive
 * Plugin URI: http://www.gopiplus.com/work/2017/11/18/owl-carousel-responsive-wordpress-plugin/
 * Description: This wordpress plugin is using Owl Carousel jQuery script and that lets you create a beautiful responsive carousel slider and its fully customizable carousel.
 * Version: 1.9
 * Author: Gopi Ramasamy
 * Author URI: http://www.gopiplus.com/work/about/
 * Requires at least: 3.4
 * Tested up to: 5.9
 * Text Domain: owl-carousel-responsive
 * Domain Path: /languages/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Copyright (c) 2022 www.gopiplus.com
 */

if ( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']) ) {
	die('You are not allowed to call this page directly.');
}

$owlc_current_folder = dirname(__FILE__);
if(!defined('OWLC_DIR')) define('OWLC_DIR', $owlc_current_folder.DIRECTORY_SEPARATOR);
if(!defined('OWLC_ADMINURL')) define( 'OWLC_ADMINURL', site_url( '/wp-admin/admin.php' ) );
if(!defined('OWLC_URL')) define('OWLC_URL',plugins_url().'/'.strtolower('responsive-owl-carousel').'/');

require_once($owlc_current_folder.DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.'owl-defined.php');
require_once($owlc_current_folder.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'owl-common.php');
require_once($owlc_current_folder.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'owl-register.php');
require_once($owlc_current_folder.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'owl-intermediate.php');

require_once($owlc_current_folder.DIRECTORY_SEPARATOR.'query'.DIRECTORY_SEPARATOR.'db_default.php');
require_once($owlc_current_folder.DIRECTORY_SEPARATOR.'query'.DIRECTORY_SEPARATOR.'db_gallery.php');

add_action( 'wp_enqueue_scripts', array( 'owlc_cls_registerhook', 'owlc_add_javascript_files' ));
add_action( 'admin_menu', array( 'owlc_cls_registerhook', 'owlc_adminmenu' ), 9);
add_action( 'admin_init', array( 'owlc_cls_registerhook', 'owlc_welcome' ) );
add_action( 'admin_enqueue_scripts', array( 'owlc_cls_registerhook', 'owlc_load_scripts' ) );

add_shortcode( 'owl-carousel-responsive', 'owlc_shortcode' );

add_action( 'plugins_loaded', 'owlc_textdomain' );
function owlc_textdomain() {
	load_plugin_textdomain( 'owl-carousel-responsive' , false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

register_activation_hook( $owlc_current_folder.DIRECTORY_SEPARATOR.'owl-carousel-responsive.php', array( 'owlc_cls_registerhook', 'owlc_activation' ) );
register_deactivation_hook( $owlc_current_folder.DIRECTORY_SEPARATOR.'owl-carousel-responsive.php', array( 'owlc_cls_registerhook', 'owlc_deactivation' ) );