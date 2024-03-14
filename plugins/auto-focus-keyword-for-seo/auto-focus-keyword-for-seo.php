<?php

/*
* Plugin Name: Auto Focus Keyword for SEO
* Description: This plugin will assign Focus Keywords to all your pages (on the backend) based on post titles, for websites using Yoast SEO and Rank Math.
* Author: Pagup
* Version: 1.0.2
* Author URI: https://pagup.com/
* Text Domain: auto-focus-keyword-for-seo
* Domain Path: /languages/
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'afkw__fs' ) ) {
    afkw__fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'afkw__fs' ) ) {
        if ( !defined( 'AFKW_NAME' ) ) {
            define( 'AFKW_NAME', "auto-focus-keyword-for-seo" );
        }
        if ( !defined( 'AFKW_PLUGIN_BASE' ) ) {
            define( 'AFKW_PLUGIN_BASE', plugin_basename( __FILE__ ) );
        }
        if ( !defined( 'AFKW_PLUGIN_DIR' ) ) {
            define( 'AFKW_PLUGIN_DIR', plugins_url( '', __FILE__ ) );
        }
        require_once 'vendor/autoload.php';
        // Create a helper function for easy SDK access.
        function afkw__fs()
        {
            global  $afkw__fs ;
            
            if ( !isset( $afkw__fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $afkw__fs = fs_dynamic_init( array(
                    'id'             => '12705',
                    'slug'           => 'auto-focus-keyword-for-seo',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_ad01e98bfcfe0d53fec15f5a09945',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'       => 'auto-focus-keyword-for-seo',
                    'first-path' => 'admin.php?page=auto-focus-keyword-for-seo',
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $afkw__fs;
        }
        
        // Init Freemius.
        afkw__fs();
        // Signal that SDK was initiated.
        do_action( 'afkw__fs_loaded' );
        function afkw__fs_settings_url()
        {
            return admin_url( 'admin.php?page=' . AFKW_NAME );
        }
        
        afkw__fs()->add_filter( 'connect_url', 'afkw__fs_settings_url' );
        afkw__fs()->add_filter( 'after_skip_url', 'afkw__fs_settings_url' );
        afkw__fs()->add_filter( 'after_connect_url', 'afkw__fs_settings_url' );
        afkw__fs()->add_filter( 'after_pending_connect_url', 'afkw__fs_settings_url' );
        function afkw__fs_custom_icon()
        {
            return dirname( __FILE__ ) . '/admin/assets/icon.png';
        }
        
        afkw__fs()->add_filter( 'plugin_icon', 'afkw__fs_custom_icon' );
        // freemius opt-in
        function afkw__fs_custom_connect_message(
            $message,
            $user_first_name,
            $product_title,
            $user_login,
            $site_link,
            $freemius_link
        )
        {
            $break = "<br><br>";
            $more_plugins = '<p><a target="_blank" href="https://wordpress.org/plugins/meta-tags-for-seo/">Meta Tags for SEO</a>, <a target="_blank" href="https://wordpress.org/plugins/auto-focus-keyword-for-seo/">Auto internal links for SEO</a>, <a target="_blank" href="https://wordpress.org/plugins/bulk-image-alt-text-with-yoast/">Bulk auto image Alt Text</a>, <a target="_blank" href="https://wordpress.org/plugins/bulk-image-title-attribute/">Bulk auto image Title Tag</a>, <a target="_blank" href="https://wordpress.org/plugins/mobilook/">Mobile view</a>, <a target="_blank" href="https://wordpress.org/plugins/better-robots-txt/">Wordpress Better-Robots.txt</a>, <a target="_blank" href="https://wordpress.org/plugins/wp-google-street-view/">Wp Google Street View</a>, <a target="_blank" href="https://wordpress.org/plugins/vidseo/">VidSeo</a>,  <a target="_blank" href="https://wordpress.org/plugins/automatic-internal-links-for-seo/">Automatic Internal Links for SEO</a>,  <a target="_blank" href="https://wordpress.org/plugins/mass-ping-tool-for-seo/">Mass Ping Tool for SEO</a>, ...</p>';
            return sprintf( esc_html__( 'Hey %1$s, %2$s Click on Allow & Continue to optimize your Focus keywords everywhere. %2$s Never miss an important update -- opt-in to our security and feature updates notifications. %2$s See you on the other side. Thanks', 'auto-focus-keyword-for-seo' ), $user_first_name, $break ) . $more_plugins;
        }
        
        afkw__fs()->add_filter(
            'connect_message',
            'afkw__fs_custom_connect_message',
            10,
            6
        );
    }
    
    class AutoFocusKeywordSEO
    {
        function __construct()
        {
            // register_activation_hook( __FILE__, array( &$database, 'migration' ) );
            register_activation_hook( __FILE__, array( &$this, 'activate' ) );
            register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
            add_action( 'init', array( &$this, 'afkw__textdomain' ) );
        }
        
        public function activate()
        {
            $options = get_option( 'afkw_auto-focus-keyword-for-seo' );
            if ( !is_array( $options ) ) {
                update_option( 'afkw_auto-focus-keyword-for-seo', [
                    "post_types"      => [ 'post', 'page' ],
                    "remove_settings" => false,
                ] );
            }
        }
        
        public function deactivate()
        {
            
            if ( \Pagup\AutoFocusKeyword\Core\Option::check( 'remove_settings' ) ) {
                delete_option( 'afkw_auto-focus-keyword-for-seo' );
                delete_option( 'afkw_autokeyword_sync' );
            }
        
        }
        
        function afkw__textdomain()
        {
            load_plugin_textdomain( "auto-focus-keyword-for-seo", false, basename( dirname( __FILE__ ) ) . '/languages' );
        }
    
    }
    $afk = new AutoFocusKeywordSEO();
    /*-----------------------------------------
                          Settings
      ------------------------------------------*/
    if ( is_admin() ) {
        include_once 'admin/Settings.php';
    }
}
