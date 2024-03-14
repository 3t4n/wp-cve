<?php // phpcs:disable WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * The plugin bootstrap file
 *
 * @package    Dotdigital_WordPress
 *
 * @wordpress-plugin
 * Plugin Name:       Dotdigital for WordPress
 * Plugin URI:        https://integrations.dotdigital.com/technology-partners/wordpress
 * Description:       Add a "Subscribe to Newsletter" widget to your website that will insert your contact in one of your Dotdigital lists.
 * Version:           7.1.2
 * Author:            dotdigital
 * Author URI:        https://www.dotdigital.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dotdigital-wordpress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/vendor/autoload.php';

define( 'DOTDIGITAL_WORDPRESS_VERSION', '7.1.2' );
define( 'DOTDIGITAL_WORDPRESS_PLUGIN_NAME', 'dotdigital-for-wordpress' );
define( 'DOTDIGITAL_WORDPRESS_PLUGIN_SLUG', 'dotdigital_for_wordpress' );
define( 'DOTDIGITAL_WORDPRESS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DOTDIGITAL_WORDPRESS_PLUGIN_ICON', 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzMiAzMiI+PHBhdGggZD0iTTE2LDIuNzhBMTMuMjIsMTMuMjIsMCwxLDEsMi43OCwxNiwxMy4yMywxMy4yMywwLDAsMSwxNiwyLjc4TTE2LDBBMTYsMTYsMCwxLDAsMzIsMTYsMTYsMTYsMCwwLDAsMTYsMFoiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMTYsOC4yOUE3Ljc0LDcuNzQsMCwxLDEsOC4yNiwxNiw3Ljc1LDcuNzUsMCwwLDEsMTYsOC4yOW0wLTIuNzhBMTAuNTIsMTAuNTIsMCwxLDAsMjYuNTIsMTYsMTAuNTIsMTAuNTIsMCwwLDAsMTYsNS41MVoiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNMTYsMTMuNzdBMi4yNiwyLjI2LDAsMSwxLDEzLjc1LDE2LDIuMjYsMi4yNiwwLDAsMSwxNiwxMy43N00xNiwxMWE1LDUsMCwxLDAsNSw1LDUsNSwwLDAsMC01LTVaIiBmaWxsPSIjZmZmIi8+PC9zdmc+' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dotdigital-wordpress-activator.php
 */
function activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dotdigital-wordpress-activator.php';
	\Dotdigital_WordPress\Includes\Dotdigital_WordPress_Activator::activate();
}
register_activation_hook( __FILE__, 'activate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dotdigital-wordpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_dotdigital_wordpress() {

	$plugin = new \Dotdigital_WordPress\Includes\Dotdigital_WordPress();
	$plugin->run();
}
run_dotdigital_wordpress();
