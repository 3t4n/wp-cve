<?php

/**
 * Plugin Name: JCC Payment Gateway for WC
 * Plugin URI: https://www.jcc.com.cy/
 * Description: A plugin for adding the JCC Payment Gateway as a payment option in WooCommerce.
 * Author: JCC Payment Systems
 * Author URI: https://www.jcc.com.cy/
 * Version: 1.3.7
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once(ABSPATH . 'wp-admin/includes/plugin.php');

function wc_jcc_enqueue_custom_admin_js($hook) {
    // Check if we're on the plugins page
    if ('plugins.php' !== $hook) {
        return;
    }
   
    wp_enqueue_script(
        'wc-jcc-custom-update-message',
        plugin_dir_url(__FILE__) . 'custom-update-message.js', // Assuming the JS file is in the root of your plugin directory
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'wc_jcc_enqueue_custom_admin_js');

function wc_jcc_custom_update_message( $data, $response){
    if( isset( $response->new_version)){        
        if (version_compare($data['Version'], $response->new_version, '<')){
        echo '<div style=color: #d63638; font-weight: bold;">';
        echo 'URGENT: Please Contact JCC Before Updating!
        We have released a new version of the plugin that requires the usage of a new set of  credentials.
        To ensure uninterrupted service, please reach out to our team at customerservice@jcc.com.cy before updating the plugin to provide you with the new credentials. Failure to do so will result in the plugin not functioning.';
        }
    }
}

add_action( 'in_plugin_update_message-jcc-payment-gateway-for-wc/class-wc-jcc-plugin.php', 'wc_jcc_custom_update_message', 10, 2);


if ( ! is_multisite() && !is_plugin_active('woocommerce/woocommerce.php')) {  
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        ?>
    <div class="update-nag notice is-dismissable">
        <p><?php _e('WooCommerce plugin is not active. Please make sure to activate it before this plugin!'); ?></p>
    </div>
    <?php
    return;
    }
}
if ( is_multisite() && !is_plugin_active('woocommerce/woocommerce.php')) {  
    ?>
    <div class="update-nag notice is-dismissable">
        <p><?php _e('WooCommerce plugin is not active. Please make sure to activate it before this plugin!'); ?></p>
    </div>
    <?php
    return;
}

if (!class_exists('WooCommerce_JCC_Payment_Gateway_Plugin')) :

    class WooCommerce_JCC_Payment_Gateway_Plugin {

        /**
        * @var
        */
        private static $active_plugins;

        /**
         * Construct the plugin.
         */
        public function __construct() {
            add_action('plugins_loaded', array($this, 'init'));
        }

        /**
         * Initialize the plugin.
         */
        public function init() {
            // Checks if WooCommerce is installed.

            // Single-site - Add this plugin to the currently active plugins list
            self::$active_plugins = (array) get_option( 'active_plugins', array() );

            //Multisite - Add this plugin to the currently active plugins list
            if ( is_multisite() ) {
                self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
            }

            // Include our integration class.
            include_once 'includes/class-wc-jcc-payment-gateway.php';

            // Register the payment_gateway.
            add_filter('woocommerce_payment_gateways', array($this, 'add_payment_gateway'));
            // Setting action for plugin
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wc_jcc_payment_gateway_plugin_action_links');
			
			$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
			$plugin_version = $plugin_data['Version'];
			define ( 'YOURPLUGIN_CURRENT_VERSION', $plugin_version );
        }

        /*
         * Add a new Payment_Gateway to WooCommerce.
         * 
         * As well as defining your class, you need to also tell WooCommerce (WC) that it exists. 
         * Do this by filtering woocommerce_payment_gateways
         */

        public function add_payment_gateway($methods) {
            $methods[] = 'WooCommerce_JCC_Payment_Gateway';
            return $methods;
        }

    }

    $WooCommerce_JCC_Payment_Gateway_Plugin = new WooCommerce_JCC_Payment_Gateway_Plugin(__FILE__);
endif;

function wc_jcc_payment_gateway_plugin_action_links($links) {
    $links[] = '<a href="' . menu_page_url('wc-settings', false) . '&tab=checkout">Settings</a>';
    return $links;
}
