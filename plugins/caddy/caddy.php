<?php
/**
 * Plugin Name:       Caddy - Smart Side Cart for WooCommerce
 * Plugin URI:        https://usecaddy.com
 * Description:       A high performance, conversion-boosting side cart for your WooCommerce store that improves the shopping experience & helps grow your sales.
 * Version:           2.0
 * Author:            Tribe Interactive
 * Author URI:        https://usecaddy.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       caddy
 * Domain Path:       /languages
 *
 * WC requires at least: 7.0
 * WC tested up to: 8.5.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Define all constants for the plugin
 */
if ( ! defined( 'CADDY_VERSION' ) ) {
	define( 'CADDY_VERSION', '2.0' );
}
if ( ! defined( 'CADDY_PLUGIN_FILE' ) ) {
	define( 'CADDY_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'CADDY_DIR_URL' ) ) {
	define( 'CADDY_DIR_URL', untrailingslashit( plugins_url( '/', CADDY_PLUGIN_FILE ) ) );
}

$wc_plugin_flag = false;
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if ( is_multisite() ) {
	// this plugin is network activated - WC must be network activated
	if ( is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
		$wc_plugin_flag = is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ? false : true;
		// this plugin is locally activated - WC can be network or locally activated
	} else {
		$wc_plugin_flag = is_plugin_active( 'woocommerce/woocommerce.php' ) ? false : true;
	}
} else { // this plugin runs on a single site
	$wc_plugin_flag = is_plugin_active( 'woocommerce/woocommerce.php' ) ? false : true;
}

if ( $wc_plugin_flag === true ) {
	add_action( 'admin_notices', 'caddy_wc_requirements_error' );

	return;
}

/**
 * If WC requirements are not match
 */
function caddy_wc_requirements_error() {
	?>
	<div class="error notice"><p>
			<strong><?php _e( 'The WooCommerce plugin needs to be installed and activated in order for Caddy to work properly.', 'caddy' ); ?></strong> <?php _e( 'Please activate WooCommerce to enable Caddy.', 'caddy' ); ?>
		</p></div>
	<?php
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-caddy-activator.php
 */
function activate_caddy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-caddy-activator.php';
	Caddy_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-caddy-deactivator.php
 */
function deactivate_caddy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-caddy-deactivator.php';
	Caddy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_caddy' );
register_deactivation_hook( __FILE__, 'deactivate_caddy' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-caddy.php';

/**
 * The plugin class that is used to register and load the cart widget.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-caddy-cart-widget.php';

/**
 * The plugin class that is used to register and load the saved items widget.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-caddy-saved-items-widget.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_caddy() {

	$plugin = new Caddy();
	$plugin->run();

}

run_caddy();

/**
 * Add plugin settings link.
 *
 * @param $caddy_links
 *
 * @return mixed
 */
function caddy_add_settings_link( $caddy_links ) {

	$caddy_links = array_merge( array( '<a href="' . esc_url( admin_url( '/admin.php?page=caddy&amp;tab=settings' ) ) . '">' . __( 'Settings', 'caddy' ) . '</a>' ), $caddy_links );

	return $caddy_links;
}

$caddy_plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$caddy_plugin", 'caddy_add_settings_link' );

/**
 * Declaring WooCommerce HPOS support
 *
 */
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );