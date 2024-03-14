<?php
/**
 * Plugin Name: WC Pickup Store
 * Plugin URI: https://www.keylormendoza.com/plugins/wc-pickup-store/
 * Description: Allows you to set up a custom post type for stores available to use it as shipping method Local pickup in WooCommerce. It also allows your clients to choose an store on the Checkout page and also adds the store fields to the order details and email.
 * Version: 1.8.6
 * Requires at least: 4.7
 * Tested up to: 6.4.1
 * WC requires at least: 3.0
 * WC tested up to: 8.3.1
 * Author: Keylor Mendoza A.
 * Author URI: https://www.keylormendoza.com
 * License: GPLv2
 * Text Domain: wc-pickup-store
 */

if ( !defined( 'ABSPATH' ) ) { exit; }

if ( !defined( 'WPS_PLUGIN_FILE' ) ) {
	define( 'WPS_PLUGIN_FILE', plugin_basename( __FILE__ ) );
}

if ( !defined( 'WPS_PLUGIN_VERSION' ) ) {
	define( 'WPS_PLUGIN_VERSION', '1.8.6' );
}

if ( !defined( 'WPS_PLUGIN_PATH' ) ) {
	define( 'WPS_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( !defined( 'WPS_PLUGIN_DIR_URL' ) ) {
	define( 'WPS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Admin Notices
 * 
 * @version 1.8.2
 * @since 1.0.0
 */
if ( ! in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
	add_action('admin_notices', 'wps_store_check_init_notice');
	return;
}

function wps_store_check_init_notice() {
	// Check if WooCommerce is active
	if ( current_user_can( 'activate_plugins') ) {
		?>
		<div id="message" class="error">
			<p>
				<?php
				printf(
					__('%1$s requires %2$sWooCommerce%3$s to be active.', 'wc-pickup-store'),
					'<strong>WC Pickup Store</strong>',
					'<a href="http://wordpress.org/plugins/woocommerce/" target="_blank" >',
					'</a>'
				);
				?>
			</p>
		</div>
		<?php
		return;
	}
}

/**
 * WC HPOS Compatibility check
 * 
 * @version 1.8.6
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * Plugin files
 */
include WPS_PLUGIN_PATH . '/includes/class-wps-init.php';
include WPS_PLUGIN_PATH . '/includes/wps-functions.php';
include WPS_PLUGIN_PATH . '/includes/cpt-store.php';
include WPS_PLUGIN_PATH . '/includes/admin/wps-admin.php';

include WPS_PLUGIN_PATH . '/includes/integrations/class-vc_stores.php';
include WPS_PLUGIN_PATH . '/includes/integrations/class-widget-stores.php';
