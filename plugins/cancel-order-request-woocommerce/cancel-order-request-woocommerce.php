<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              piwebsolution.com
 * @since             1.3.3.33
 * @package           Cancel_Order_Request_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Cancel order request for WooCommerce
 * Plugin URI:        piwebsolution.com/cancel-order-request-woocommerce
 * Description:       Gives option to replace Cancel order button with Cancel order request button, so user can send cancellation request
 * Version:           1.3.3.33
 * Author:            PI Websolution
 * Author URI:        piwebsolution.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cancel-order-request-woocommerce
 * Domain Path:       /languages
 * WC tested up to: 8.6.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(is_plugin_active( 'cancel-order-request-woocommerce-pro/cancel-order-request-woocommerce.php')){
    function corw_pro_present_error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Please deactivate Pro version of Cancel order request for WooCommerce', 'cancel-order-request-woocommerce' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'corw_pro_present_error_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}

if(!is_plugin_active( 'woocommerce/woocommerce.php')){
    function corw_pro_error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Please Install and Activate WooCommerce plugin, without that this plugin can\'t work', 'cancel-order-request-woocommerce' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'corw_pro_error_notice' );
    return;
}else{

/**
 * Currently plugin version.
 * Start at version 1.3.3.33 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CANCEL_ORDER_REQUEST_WOOCOMMERCE_VERSION', '1.3.3.33' );

define('PISOL_CORW_BASE_DIR', __DIR__);
define('PISOL_CORW_PRICE', '$19');
define('PISOL_CORW_BUY_URL', 'https://www.piwebsolution.com/cart/?add-to-cart=13147&variation_id=15708#order_review');

/**
 * Declare compatible with HPOS new order table 
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cancel-order-request-woocommerce-activator.php
 */
function activate_cancel_order_request_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cancel-order-request-woocommerce-activator.php';
	Cancel_Order_Request_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cancel-order-request-woocommerce-deactivator.php
 */
function deactivate_cancel_order_request_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cancel-order-request-woocommerce-deactivator.php';
	Cancel_Order_Request_Woocommerce_Deactivator::deactivate();
}


function pisol_corw_get_option($variable, $default=""){
    $value = get_option($variable,$default);
    return apply_filters('pisol_corw_setting_filter_'.$variable, $value, $variable, $default);
}

register_activation_hook( __FILE__, 'activate_cancel_order_request_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_cancel_order_request_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cancel-order-request-woocommerce.php';

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ),  'pisol_cancel_order_request_plugin_link' );

function pisol_cancel_order_request_plugin_link( $links ) {
    $links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=pisol-cancel-order-request' ) ) . '">' . __( 'Settings', 'cancel-order-request-woocommerce' ) . '</a>',
        '<a style="color:#0a9a3e; font-weight:bold;" target="_blank" href="https://wordpress.org/support/plugin/cancel-order-request-woocommerce/reviews/#bbp_topic_content">' . __( 'Send suggestions to improve','cancel-order-request-woocommerce' ) . '</a>', '<a style="color:#FF0000; font-weight:bold;" target="_blank" href="'.esc_url(PISOL_CORW_BUY_URL).'">' . __( 'Get PRO','cancel-order-request-woocommerce' ) . '</a>'
    ), $links );
    return $links;
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.3.3.33
 */
function run_cancel_order_request_woocommerce() {

	$plugin = new Cancel_Order_Request_Woocommerce();
	$plugin->run();

}
run_cancel_order_request_woocommerce();
}