<?php

/*
 Plugin Name: N360 | Splash Screen
 Plugin URI: https://notion360.com/SplashScreen/
 Description: Creates a fading splash screen as a landing page for your Wordpress website
 Version: 1.0.6
 Author: bistromatic
 Author URI: https://notion360.com/
 License: GPL v3 or later
 License URI: https://www.gnu.org/licenses/gpl-3.0.html License: GPLv2 or later
 Text Domain: n360splashscreen
 Requires at least: 4.7
 Tested up to: 6.4.1
*/

/* no funny business here! */
if ( ! defined( 'ABSPATH' ) ) exit;

define ( 'N360_SPLASH_PAGE_VERSION', '1.0.4' );
define ( 'N360_VERSION_COOKIE', 'n360_version_cookie' );
define ( 'N360_SPLASH_PAGE_ROOT_PATH', plugin_dir_path( __FILE__ ) );
define ( 'N360_SPLASH_PAGE_ROOT_URL', plugin_dir_url( __FILE__ ) );

require_once ( 'includes/activation.php' );

function n360_plugin_init() {

	if ( is_admin() ) {
		require_once ( N360_SPLASH_PAGE_ROOT_PATH . 'includes/settings-fields.php' );
		require_once ( N360_SPLASH_PAGE_ROOT_PATH . 'includes/admin.php' );
	} else {
        if (session_status() == PHP_SESSION_NONE) { 
            session_start();
        }
        $home_url_rel = wp_make_link_relative(get_home_url()) . '/';
	    if ( $_SERVER['REQUEST_URI'] == $home_url_rel ) {
            require_once ( N360_SPLASH_PAGE_ROOT_PATH . 'includes/splash-screen.php' );
            $splash_screen = new n360_SplashScreen();
            if ( $splash_screen->run ) {
                $splash_screen->n360_splash_page();
            }
	    }
    }
}
add_action ( 'init', 'n360_plugin_init' );