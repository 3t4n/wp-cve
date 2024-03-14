<?php

/*
Plugin Name: Get Cash
Plugin URI: https://theafricanboss.com/get-cash
Description: Receive Cash, Tips, Donations, Funds, Support on WordPress via Cash App, Venmo, PayPal, Zelle with a button or QR Code anywhere on your website
Author: The African Boss
Author URI: https://theafricanboss.com
Version: 3.2
Requires PHP: 5.0
Requires at least: 5.0
Tested up to: 6.3.1
Text Domain: get-cash
Domain Path: languages
Created: 2021
Copyright 2023 theafricanboss.com All rights reserved
*/
// Reach out to The African Boss for website and mobile app development services at theafricanboss@gmail.com
// or at www.TheAfricanBoss.com or download our app at www.TheAfricanBoss.com/app
// If you are using this version, please send us some feedback
// via email at theafricanboss@gmail.com on your thoughts and what you would like improved
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
include_once ABSPATH . 'wp-admin/includes/plugin.php';
define( 'GET_CASH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GET_CASH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'GET_CASH_PLUGIN_DIR_URL', plugins_url( '/', __FILE__ ) );
define( 'GET_CASH_PRO_PLUGIN_DIR', plugin_dir_path( 'get-cash-pro' ) );
define( 'GET_CASH_PLUGIN_SLUG', explode( "/", GET_CASH_PLUGIN_BASENAME )[0] );
$plugin_data = get_plugin_data( __FILE__ );
define( 'GET_CASH_PLUGIN_VERSION', GET_CASH_PLUGIN_SLUG . '-' . $plugin_data['Version'] );
define( 'GET_CASH_PLUGIN_TEXT_DOMAIN', $plugin_data['TextDomain'] );
define( 'GET_CASH_UPGRADE_URL', 'https://theafricanboss.com/freemius/get-cash' );

if ( function_exists( 'getcash_fs' ) ) {
    getcash_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'getcash_fs' ) ) {
        // Create a helper function for easy SDK access.
        function getcash_fs()
        {
            global  $getcash_fs ;
            
            if ( !isset( $getcash_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_10299_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_10299_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $getcash_fs = fs_dynamic_init( array(
                    'id'             => '10299',
                    'slug'           => 'get-cash',
                    'premium_slug'   => 'get-cash-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_05c472b50fbeda0515b70faa84f8d',
                    'is_premium'     => false,
                    'premium_suffix' => 'PRO',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 3,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'       => 'get-cash',
                    'first-path' => 'admin.php?page=get-cash',
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $getcash_fs;
        }
        
        // Init Freemius.
        getcash_fs();
        // Signal that SDK was initiated.
        do_action( 'getcash_fs_loaded' );
    }
    
    add_filter( 'plugin_action_links_' . GET_CASH_PLUGIN_BASENAME, 'get_cash_settings_link' );
    function get_cash_settings_link( $links_array )
    {
        $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=get-cash', __FILE__ ) ) . '">Settings</a>';
        global  $getcash_fs ;
        $upgrade_url = getcash_fs()->get_upgrade_url();
        array_unshift( $links_array, $settings_link );
        $links_array['get_cash_pro'] = sprintf( '<a href="' . $upgrade_url . '" target="_blank" style="color: #39b54a; font-weight: bold;">' . esc_html__( 'Upgrade', GET_CASH_PLUGIN_TEXT_DOMAIN ) . '</a>' );
        return $links_array;
    }
    
    add_action( 'plugins_loaded', 'get_cash_init_class' );
    function get_cash_init_class()
    {
        include_once ABSPATH . 'wp-includes/pluggable.php';
        include_once ABSPATH . 'wp-includes/option.php';
        add_filter( 'widget_text', 'shortcode_unautop' );
        add_filter( 'widget_text', 'do_shortcode' );
        require_once GET_CASH_PLUGIN_DIR . 'includes/class-get_cash.php';
        
        if ( class_exists( 'Get_Cash' ) ) {
            $get_cash = new Get_Cash();
            $get_cash_options = get_option( 'get_cash_option_name' );
            // Array of All Options
            require_once GET_CASH_PLUGIN_DIR . 'includes/shortcodes.php';
            require_once GET_CASH_PLUGIN_DIR . 'includes/form-post.php';
            require_once GET_CASH_PLUGIN_DIR . 'includes/admin/dashboard.php';
        }
    
    }

}
