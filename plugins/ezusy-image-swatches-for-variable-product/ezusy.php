<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.ryviu.com
 * @since             1.0.5
 * @package           Ezusy
 *
 * @wordpress-plugin
 * Plugin Name:       Ezusy - Image Swatches for Variable Product 
 * Plugin URI:        https://www.ezusy.com
 * Description:       Showing thumbnail images for variations on woocommerce site.
 * Version:           1.0.5
 * Requires at least: 4.0
 * Tested up to:      6.4
 * WC requires at least: 3.0
 * WC tested up to: 8.4
 * Author:            Ezusy
 * Author URI:        https://www.ezusy.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ezusy
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define('EZUSY_WOO_VERSION', '1.0.5');
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
define('EZUSY_DIR_PATH', plugin_dir_path(__FILE__) );
define('EZUSY_URL_ASSETS', plugins_url( 'public/', __FILE__ ) );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ezusy-activator.php
 */

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

function activate_ezusy() {
	$options = get_option( 'ezusy_settings_reviews' );
	
	$default_opt = array(
		'list_name_variation' => "Color",
		'ez_width_images' => 40,
	);
	
	if(is_array($options) && $options){
		update_option('ezusy_settings_reviews', $options);
	}else{
		add_option('ezusy_settings_reviews', $default_opt);
	}

	flush_rewrite_rules();
	
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ezusy-activator.php';
	Ezusy_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ezusy-deactivator.php
 */
function deactivate_ezusy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ezusy-deactivator.php';
	Ezusy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ezusy' );
register_deactivation_hook( __FILE__, 'deactivate_ezusy' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ezusy.php';

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'ezusy_code_add_plugin_page_settings_link');

function ezusy_code_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=ezusy-setting-admin' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ezusy() {

	$plugin = new Ezusy();
	$plugin->run();

}
run_ezusy();
