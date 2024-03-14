<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://boomdevs.com/
 * @since             1.0.0
 * @package           Euis
 *
 * @wordpress-plugin
 * Plugin Name:       Unlimited Elementor Inner Sections By BoomDevs
 * Plugin URI:        https://boomdevs.com/product-category/wordpress/wordpress-plugins/
 * Description:       The only plugin that allows to add unlimited inner sections in Elementor without any other bloat-add-ons
 * Version:           1.0.5
 * Author:            BoomDevs
 * Author URI:        https://boomdevs.com/
 * Elementor tested up to: 3.18.3
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       euis
 * Domain Path:       /languages 
 */

// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EUIS_VERSION', '1.0.5' );

require __DIR__ . '/vendor/autoload.php';

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_unlimited_elementor_inner_sections_by_boomdevs() {

    if ( ! class_exists( 'Appsero\Client' ) ) {
      require_once __DIR__ . '/appsero/src/Client.php';
    }

    $client = new Appsero\Client( '7d1e2808-f512-4e91-b06f-95ad6e5653e5', 'Unlimited Elementor Inner Sections By BoomDevs', __FILE__ );

    // Active insights
    $client->insights()->init();

}

appsero_init_tracker_unlimited_elementor_inner_sections_by_boomdevs();

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-euis-activator.php
 */
function activate_euis() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-euis-activator.php';
    Euis_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-euis-deactivator.php
 */
function deactivate_euis() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-euis-deactivator.php';
    Euis_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_euis' );
register_deactivation_hook( __FILE__, 'deactivate_euis' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-euis.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_euis() {

// Check if Elementor is installed and activated
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'euis_elementor_missing_notice' );
    } else {
        // Run plugin
        $plugin = new Euis();
        $plugin->run();
    }

}

add_action( 'plugins_loaded', 'run_euis' );

/**
 * Shows admin notice if Elementor is not installed or activated
 *
 * @since    1.0.0
 */
function euis_elementor_missing_notice() {

    $message = sprintf(
        __( 'You must install and activate %s to use %s. %s.', 'euis' ),
        '<strong>' . __( 'Elementor', 'euis' ) . '</strong>',
        '<strong>' . __( 'Unlimited Elementor Inner Sections By BoomDevs', 'euis' ) . '</strong>',
        '<br><a href="' . esc_url( admin_url( 'plugin-install.php?s=Elementor&tab=search&type=term' ) ) . '">' . __( 'Please click on this on link to install or activate Elementor', 'euis' ) . '</a>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 15px 0">%1$s</p></div>', $message );

}