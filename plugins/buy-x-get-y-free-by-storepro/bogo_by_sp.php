<?php

/**
 
 * Plugin Name:       Buy X Get Y Free by StorePro
 * Plugin URI:        https://storepro.io/
 * Description:       Buy X product to Get Y product as free.
 * Version:           1.1.6
 * Author:            StorePro
 * Author URI:        https://storepro.io/
 * WC tested up to:   8.2.1
 * WC requires at least: 2.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* 
    Making sure woocommerce is there 
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(!is_plugin_active( 'woocommerce/woocommerce.php')){
    function bogo_by_sp_deal() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Buy X Get Y Free by StorePro is inactive.WooCommerce plugin must be active for Buy X Get Y Free to work. Please install & activate WooCommerce.', 'bogo-by-sp' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'bogo_by_sp_deal' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}


/**
 * The code that runs during plugin activation.
 * This action is documented in Inc/bogo_by_sp_active.php
 */
function bogo_by_sp_active() {
	require_once plugin_dir_path( __FILE__ ) . 'Inc/bogo_by_sp_active.php';
	Bogo_By_Sp_Activor::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in Inc/bogo_by_sp_deactive.php
 */
function bogo_by_sp_deactive() {
	require_once plugin_dir_path( __FILE__ ) . 'Inc/bogo_by_sp_deactive.php';
	Bogo_By_Sp_Deactivor::deactivate();
}

register_activation_hook( __FILE__, 'bogo_by_sp_active' );
register_deactivation_hook( __FILE__, 'bogo_by_sp_deactive' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'Inc/bogo_by_sp_class.php';

function bogo_by_sp_plugin_link( $links ) {
	$links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=BuyXGetY-by-storepro' ) ) . '">' . __( 'Settings' ) . '</a>',
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bogo_by_sp_plugin_link' );

if(!function_exists('wc_version_check_bogo_by_sp')){
function wc_version_check_bogo_by_sp( $version = '3.0' ) {
	if ( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}
	}
	return false;
}
}

/**
 * Begins execution of the plugin.
 */
function bogo_by_sp_run() {

	$plugin = new bogo_by_sp();
	$plugin->run();

}
bogo_by_sp_run();



add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

