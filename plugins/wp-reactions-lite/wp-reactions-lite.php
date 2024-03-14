<?php
use WP_Reactions\Lite\Main;

/*
Plugin Name: WP Reactions Lite
Description: The #1 Emoji Reactions Plugin for Wordpress. Engage your users with Lottie animated emoji reactions and increase social sharing with mobile and desktop sharing pop-ups and surprise button reveals. Put your emojis anywhere you want to get a reaction.
Plugin URI: https://wpreactions.com
Version: 1.3.10
Requires at least: 4.4
Requires PHP: 5.3
Author: WP Reactions, LLC
Text Domain: wpreactions-lite
*/

define( 'WPRA_LITE_VERSION', '1.3.10' );
define( 'WPRA_LITE_DB_VERSION', 2);
define( 'WPRA_LITE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPRA_LITE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPRA_LITE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPRA_LITE_OPTIONS', 'wpra_lite_options' );

spl_autoload_register(function ($class_name) {
	if ( false !== strpos( $class_name, 'WP_Reactions\\Lite' ) ) {
		$class_file = str_replace( 'WP_Reactions\\Lite\\', '', $class_name ) . '.class.php';
		$class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_file );
		require_once(WPRA_LITE_PLUGIN_PATH . 'includes/' . $class_file);
	}
});

// init plugin
global $wpra_lite;
$wpra_lite = new Main();

