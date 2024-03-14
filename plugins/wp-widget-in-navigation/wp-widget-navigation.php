<?php
/*
Plugin Name: WP Widget in Navigation
Plugin URI: 
description: Add widgets to WordPress nav menu! and easy to show wordpress widget in menu.
Version: 3.1
Author: Yudiz Solutions Ltd.
Author URI: http://www.yudiz.com/
Text Domain: yspl_win
License: 
*/
define( 'YSPL_WIN_VERSION', '2.0.0' );

/**
 * Filters the prefix used in class/id attributes in html display. 
 * 
 * @since 2.0.0
 * 
 * @param string $default_prefix The default prefix: 'yspl_win'
 */


$attr_prefix = apply_filters( 'yspl_win_attribute_prefix', 'yspl_win' );
/**
 *
 * A string prefix for internal names and ids 
 */
define( 'YSPL_WIN_PREFIX', $attr_prefix );

/**
 * Plugin's file path
 */
define( 'YSPL_WIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin's url path
 */
define( 'YSPL_WIN_URL', plugin_dir_url( __FILE__ ));

/**
 * Plugin's Slug
 */
define( 'YSPL_WIN_SLUG', 'widget_in_nav' );

/**
 * Include main plugin File
 */
include_once YSPL_WIN_PATH . 'init/main-yspl-win.php';

/**
 * Include menu plugin File
 */
include_once YSPL_WIN_PATH . 'init/menu-yspl-win.php';
/**
 * Object of main class
 */
$yspl_win_main = new YSPL_WIN_MAIN();
$yspl_win_main->yspl_init();
/**
 * Object of menu class
 */
$yspl_win_menu = new YSPL_WIN_MENU();
$yspl_win_menu->yspl_init();
