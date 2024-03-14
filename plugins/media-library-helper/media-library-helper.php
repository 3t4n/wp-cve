<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codexin.com
 * @since             1.0.0
 * @package           ImageMetadataSettings
 *
 * @wordpress-plugin
 * Plugin Name:       Media Library Helper by Codexin
 * Plugin URI:        https://wordpress.org/plugins/media-library-helper/
 * Description:       Add or edit or Bulk edit image ALT tag, caption & description with one click straight from WordPress media library to boost your SEO score.
 * Version:           1.3.0
 * Author:            Codexin Technologies
 * Author URI:        https://codexin.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       media-library-helper
 * Domain Path:       /languages
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in lib/Activator.php
 */
\register_activation_hook( __FILE__, '\Codexin\ImageMetadataSettings\Activator::activate' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in lib/Deactivator.php
 */
\register_deactivation_hook( __FILE__, '\Codexin\ImageMetadataSettings\Deactivator::deactivate' );

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
\add_action(
	'plugins_loaded',
	function () {
		define( 'CDXN_MLH_PREFIX', 'cdxn_mlh' );
		define( 'CDXN_MLH_FILE', __FILE__ );
		define( 'CDXN_MLH_PATH', __DIR__ );
		$plugin = new \Codexin\ImageMetadataSettings\Plugin();
		$plugin->run();
	}
);
