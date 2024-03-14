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
 * @since             1.1.27
 * @package           Conditional_fees_Rule_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Conditional extra fees for WooCommerce
 * Plugin URI:        piwebsolution.com/conditional-fees-rule-wooCommerce
 * Description:       Conditional extra fees for WooCommerce
 * Version:           1.1.27
 * Author:            PI Websolution
 * Author URI:        piwebsolution.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       conditional-extra-fees-woocommerce
 * Domain Path:       /languages
 * WC tested up to: 8.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if(!is_plugin_active( 'woocommerce/woocommerce.php')){
    function pisol_cefw_free_woo() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Please Install and Activate WooCommerce plugin, without that this plugin cant work', 'conditional-extra-fees-woocommerce' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pisol_cefw_free_woo' );
    return;
}

if(is_plugin_active( 'conditional-extra-fees-for-woocommerce-pro/conditional-fees-rule-woocommerce.php')){
	
	function pisol_cefw_free_error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Please uninstall/deactivate the Pro version of Conditional fees rule plugin', 'conditional-extra-fees-woocommerce' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pisol_cefw_free_error_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;

}else{

/**
 * Currently plugin version.
 * Start at version 1.1.27 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CONDITIONAL_FEES_RULE_WOOCOMMERCE_VERSION', '1.1.27' );
define('PI_CEFW_BUY_URL', 'https://www.piwebsolution.com/cart/?add-to-cart=15441&variation_id=15442');
define('PI_CEFW_PRICE', '$34');
define('PI_CEFW_DELETE_SETTING', false);

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
 * This action is documented in includes/class-conditional-fees-rule-woocommerce-activator.php
 */
function activate_conditional_fees_rule_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-conditional-fees-rule-woocommerce-activator.php';
	Conditional_fees_Rule_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-conditional-fees-rule-woocommerce-deactivator.php
 */
function deactivate_conditional_fees_rule_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-conditional-fees-rule-woocommerce-deactivator.php';
	Conditional_fees_Rule_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_conditional_fees_rule_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_conditional_fees_rule_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-conditional-fees-rule-woocommerce.php';

if(!function_exists('pisol_free_conditional_fees_plugin_link')){
    add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ),  'pisol_free_conditional_fees_plugin_link' );

    function pisol_free_conditional_fees_plugin_link( $links ) {
        $links = array_merge( array(
            '<a href="' . esc_url( admin_url( '/admin.php?page=pisol-cefw' ) ) . '">' . __( 'Settings', 'conditional-extra-fees-woocommerce' ) . '</a>'
        ), $links );
        return $links;
    }
}

function run_conditional_fees_rule_woocommerce() {

	$plugin = new Conditional_fees_Rule_Woocommerce();
	$plugin->run();

}
run_conditional_fees_rule_woocommerce();

}