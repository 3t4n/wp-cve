<?php
/*
	Plugin Name: Better image sizes
	Plugin URI: https://wp-speedup.eu
	Description: Better image sizes
	Version: 3.5
	Author: KubiQ
	Author URI: https://kubiq.sk
	Text Domain: better-image-sizes
	Domain Path: /languages
*/

defined('ABSPATH') || exit;

define( 'BIS_BASE', plugin_basename( __FILE__ ) );

if( ! defined('BIS_ALLOWED_MIME_TYPES') ){
	define( 'BIS_ALLOWED_MIME_TYPES', array( 'image/jpeg', 'image/png', 'image/webp' ) );
}

if( ! class_exists('Better_image_sizes') ){
	class Better_image_sizes{
		function __construct(){
			require_once('focal-point/index.php');
			require_once('resizer/index.php');

			add_action( 'plugins_loaded', function(){
				load_plugin_textdomain( 'better-image-sizes', false, basename( __DIR__ ) . '/languages/' );
			});
		}
	}
	new Better_image_sizes();
}