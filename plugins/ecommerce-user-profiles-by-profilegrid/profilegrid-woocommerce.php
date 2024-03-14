<?php

/**
 * @link              http://profilegrid.co
 * @since             1.0.0
 * @package           Profilegrid_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       ProfileGrid WooCommerce Integration
 * Plugin URI:        http://profilegrid.co
 * Description:       Combine the power of ProfileGrid's user groups with WooCommerce cart to provide your users ultimate shopping experience.
 * Version:           3.3
 * Author:            profilegrid
 * Author URI:        http://profilegrid.co
 * License:           Commercial/ Proprietary
 * Text Domain:       profilegrid-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 8.0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profilegrid-woocommerce-activator.php
 */
function activate_profilegrid_woocommerce() {
	$pm_woocommerce_activator = new Profilegrid_Woocommerce_Activator;
	$pm_woocommerce_activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profilegrid-woocommerce-deactivator.php
 */
function deactivate_profilegrid_woocommerce() {
        $pm_woocommerce_deactivator = new Profilegrid_Woocommerce_Deactivator();
	$pm_woocommerce_deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_profilegrid_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_profilegrid_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
/* declare extension compatible with HPOS */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

require plugin_dir_path( __FILE__ ) . 'includes/class-profilegrid-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_profilegrid_woocommerce() {

	$plugin = new Profilegrid_Woocommerce();
	$plugin->run();

}
run_profilegrid_woocommerce();