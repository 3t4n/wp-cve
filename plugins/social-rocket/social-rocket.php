<?php
/**
 * Plugin Name: Social Rocket
 * Description: Social Sharing... to the Moon!
 * Version: 1.3.3
 * Author: Social Rocket
 * Author URI: http://wpsocialrocket.com/
 *
 * Text Domain: social-rocket
 * Domain Path: /languages
 *
 * Copyright: © 2018-2022 Social Rocket. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( function_exists( 'social_rocket_init' ) ) {
	return; // Exit if already loaded
}

define( 'SOCIAL_ROCKET_VERSION', '1.3.3' );
define( 'SOCIAL_ROCKET_DBVERSION', '5' );
define( 'SOCIAL_ROCKET_PATH', plugin_dir_path( __FILE__ ) );
define( 'SOCIAL_ROCKET_FILE', __FILE__ );


/**
 * Activation / Deactivation Scripts
 */
require_once( SOCIAL_ROCKET_PATH . 'includes/social-rocket-activate.php' );
require_once( SOCIAL_ROCKET_PATH . 'includes/social-rocket-deactivate.php' );


/**
 * Update scripts
 */
require_once( SOCIAL_ROCKET_PATH . 'includes/social-rocket-update.php' );


/**
 * Global functions
 */
require_once( SOCIAL_ROCKET_PATH . 'includes/social-rocket-global-functions.php' );


/**
 * Shortcodes
 */
require_once( SOCIAL_ROCKET_PATH . 'includes/social-rocket-shortcodes.php' );


/**
 * Make it so...
 */
if ( ! function_exists( 'social_rocket_init' ) ) {
	function social_rocket_init() {
		
		require_once( SOCIAL_ROCKET_PATH . 'vendor/wp-background-processing/wp-background-processing.php' );
		require_once( SOCIAL_ROCKET_PATH . 'includes/class-social-rocket-background-process.php' );
		require_once( SOCIAL_ROCKET_PATH . 'includes/class-social-rocket-compatibility.php' );
		require_once( SOCIAL_ROCKET_PATH . 'includes/class-social-rocket-cron.php' );
		require_once( SOCIAL_ROCKET_PATH . 'includes/class-social-rocket.php' );
		Social_Rocket::get_instance();
		
		if ( is_admin() ) {
			if ( ! class_exists( 'Browser' ) ) {
				require_once( SOCIAL_ROCKET_PATH . 'vendor/browser/Browser.php' );
			}
			require_once( SOCIAL_ROCKET_PATH . 'admin/includes/class-social-rocket-admin-notices.php' );
			require_once( SOCIAL_ROCKET_PATH . 'admin/includes/class-social-rocket-admin.php' );
			Social_Rocket_Admin::get_instance();
		}
		
		Social_Rocket_Compatibility::get_instance();
		
		do_action( 'social_rocket_loaded' );
		
	}

	add_action( 'init', 'social_rocket_init' );
	add_action( 'init', array( 'Social_Rocket', 'validate_settings' ), 999 );
}
