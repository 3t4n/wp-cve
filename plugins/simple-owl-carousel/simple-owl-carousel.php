<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://presstigers.com
 * @since             1.0.0
 * @package           Simple_Owl_Carousel
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Owl Carousel
 * Plugin URI:        https://wordpress.org/plugins/simple-owl-carousel/
 * Description:       Based on the Owl Carousel, an extremely powerful, robust & responsive customizable plugin.
 * Version:           1.1.1
 * Author:            PressTigers
 * Author URI:        http://presstigers.com/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       simple-owl-carousel
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 *  Show SOC Upgrade Notice
 */
function soc_showUpgradeNotification($currentPluginMetadata, $newPluginMetadata)
{

    // check "upgrade_notice"
    if (isset($newPluginMetadata->upgrade_notice) && strlen(trim($newPluginMetadata->upgrade_notice)) > 0) {
        echo '<br><br><strong>Important Upgrade Notice:</strong> ' . strip_tags($newPluginMetadata->upgrade_notice) . '';
    }
}

// Show SOC Upgrade Notice
add_action('in_plugin_update_message-simple-owl-carousel/simple-owl-carousel.php', 'soc_showUpgradeNotification', 10, 2);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-owl-carousel-activator.php
 */
function activate_simple_owl_carousel() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-owl-carousel-activator.php';
	Simple_Owl_Carousel_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-owl-carousel-deactivator.php
 */
function deactivate_simple_owl_carousel() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-owl-carousel-deactivator.php';
	Simple_Owl_Carousel_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_owl_carousel' );
register_deactivation_hook( __FILE__, 'deactivate_simple_owl_carousel' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-owl-carousel.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since   1.0.0
 */
function run_simple_owl_carousel() {

	$plugin = new Simple_Owl_Carousel();
	$plugin->run();

}

run_simple_owl_carousel();
