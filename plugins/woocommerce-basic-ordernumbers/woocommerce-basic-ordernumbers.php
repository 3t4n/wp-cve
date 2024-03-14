<?php
/**
 * Plugin Name: WooCommerce Basic Ordernumbers
 * Plugin URI: http://open-tools.net/woocommerce/advanced-ordernumbers-for-woocommerce.html
 * Description: Configure WooCommerce ordernumbers to have a running counter and arbitrary, fixed text (prefix / postfix).
 * Version: 1.4.4
 * Author: Open Tools
 * Author URI: http://open-tools.net
 * Text Domain: woocommerce-advanced-ordernumbers
 * License: GPL2+
 * Network: true
 * WC requires at least: 2.2
 * WC tested up to: 3.2.6
*/

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}
/**
 * Check if WooCommerce is active
 **/
function oton_is_wc_active() {
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	return 
		in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) 
		|| 
		is_plugin_active_for_network( 'woocommerce/woocommerce.php' );
}

/**
 * Check if WooCommerce is active
 **/
if ( oton_is_wc_active() ) {

	if (file_exists(plugin_dir_path( __FILE__ ) . '/ordernumbers_woocommerce_basic.php') && !class_exists("OpenToolsOrdernumbersBasic") ) {
		require_once( plugin_dir_path( __FILE__ ) . '/ordernumbers_woocommerce_basic.php');
	}

	function ordernumbers_print_basic_admin_notice() { 
		deactivate_plugins( plugin_basename( __FILE__ ) );
		?>
		<div class="error">
			<p><?php _e( 'The <b>OpenTools Advanced Ordernumbers</b> plugin is <b>installed</b> and activated, the <b>basic ordernumber plugin</b> with similar, but limited functionality will be <b>deactivated</b>.', 'opentools-advanced-ordernumbers' ); ?></p>
		</div>
		<?php
	}
	function ordernumbers_check_deactivate() {
		if (defined ('OPENTOOLS_ADVANCED_ORDERNUMBERS')) {
			$hook = is_multisite() ? 'network_' : '';
			add_action( "{$hook}admin_notices", 'ordernumbers_print_basic_admin_notice');
		}
	}
	add_action( 'plugins_loaded', 'ordernumbers_check_deactivate', 99 );
	
	// instantiate the plugin class
	if (class_exists("OpenToolsOrdernumbersBasic")) {
		$ordernumber_plugin = new OpenToolsOrdernumbersBasic(plugin_basename(__FILE__));
	}
 
// } else {
//    echo "Woocommerce not activated!";
   }
