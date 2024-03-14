<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin
 * and defines a function that starts the plugin.
 *
 * @link              https://easypodcastpro.com
 * @since             1.0.0
 * @package           Podcast_Player
 *
 * @wordpress-plugin
 * Plugin Name:       podcast player
 * Plugin URI:        https://easypodcastpro.com
 * Description:       Host your podcast episodes anywhere, display them only using podcast feed url. Use custom widget or shortcode to display podcast player anywhere on your site.
 * Version:           7.0.0
 * Author:            vedathemes
 * Author URI:        https://easypodcastpro.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       podcast-player
 * Domain Path:       /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Currently plugin version.
define( 'PODCAST_PLAYER_VERSION', '7.0.0' );

// Define plugin constants.
define( 'PODCAST_PLAYER_DIR', plugin_dir_path( __FILE__ ) );

// Define plugin constants.
define( 'PODCAST_PLAYER_URL', plugin_dir_url( __FILE__ ) );

// Define plugin constants.
define( 'PODCAST_PLAYER_BASENAME', plugin_basename( __FILE__ ) );

// Register PHP autoloader.
spl_autoload_register(
	function( $class ) {
		$namespace = 'Podcast_Player\\';

		// Bail if the class is not in our namespace.
		if ( 0 !== strpos( $class, $namespace ) ) {
			return;
		}

		// Get classname without namespace.
		$carray = array_values( explode( '\\', $class ) );
		$clast  = count( $carray ) - 1;

		// Return if proper array is not available. (Just in case).
		if ( ! $clast ) {
			return;
		}

		// Prepend actual classname with 'class-' prefix.
		$carray[ $clast ] = 'class-' . $carray[ $clast ];
		$class            = implode( '\\', $carray );

		// Generate file path from classname.
		$path = strtolower(
			str_replace(
				array( $namespace, '_' ),
				array( '', '-' ),
				$class
			)
		);

		// Build full filepath.
		$file = PODCAST_PLAYER_DIR . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $path ) . '.php';

		// If the file exists for the class name, load it.
		if ( file_exists( $file ) ) {
			include $file;
		}
	}
);

add_action(
	'plugins_loaded',
	function() {
		// Load plugin's text domain.
		load_plugin_textdomain( 'podcast-player', false, dirname( PODCAST_PLAYER_BASENAME ) . '/lang' );

		// Register Podcast player front-end hooks.
		Podcast_Player\Frontend\Register::init();

		// Register Podcast player back-end hooks.
		Podcast_Player\Backend\Register::init();
	},
	8
);

// Load premium features (if exist).
if ( file_exists( PODCAST_PLAYER_DIR . '/pp-pro/pp-pro.php' ) ) {
	require_once PODCAST_PLAYER_DIR . '/pp-pro/pp-pro.php';
}
