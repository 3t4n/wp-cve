<?php

/*
* Plugin Name: Add Twitter Pixel
* Description: Twitter pixel plugin allows you to install your Twitter pixel properly on your website to track conversion & maximize ROI with your Twitter ads
* Author: Pagup
* Version: 1.0.6
* Author URI: https://pagup.com/
* Text Domain: add-twitter-pixel
* Domain Path: /languages/
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/******************************************
                Freemius Init
*******************************************/

if ( function_exists( 'atp__fs' ) ) {
    atp__fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'atp__fs' ) ) {
        if ( !defined( 'ATP_PLUGIN_BASE' ) ) {
            define( 'ATP_PLUGIN_BASE', plugin_basename( __FILE__ ) );
        }
        if ( !defined( 'ATP_PLUGIN_DIR' ) ) {
            define( 'ATP_PLUGIN_DIR', plugins_url( '', __FILE__ ) );
        }
        require 'vendor/autoload.php';
        // Create a helper function for easy SDK access.
        function atp__fs()
        {
            global  $atp__fs ;
            
            if ( !isset( $atp__fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $atp__fs = fs_dynamic_init( array(
                    'id'              => '7201',
                    'slug'            => 'add-twitter-pixel',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_aaedfc3585eeb85f3f51568a356e4',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Pro for Woocommerce',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'has_affiliation' => 'all',
                    'menu'            => array(
                    'slug'       => 'add-twitter-pixel',
                    'first-path' => 'options-general.php?page=add-twitter-pixel',
                    'support'    => false,
                    'parent'     => array(
                    'slug' => 'options-general.php',
                ),
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $atp__fs;
        }
        
        // Init Freemius.
        atp__fs();
        // Signal that SDK was initiated.
        do_action( 'atp__fs_loaded' );
        function atp__fs_settings_url()
        {
            return admin_url( 'options-general.php?page=add-twitter-pixel' );
        }
        
        atp__fs()->add_filter( 'connect_url', 'atp__fs_settings_url' );
        atp__fs()->add_filter( 'after_skip_url', 'atp__fs_settings_url' );
        atp__fs()->add_filter( 'after_connect_url', 'atp__fs_settings_url' );
        atp__fs()->add_filter( 'after_pending_connect_url', 'atp__fs_settings_url' );
        function atp__fs_custom_icon()
        {
            return dirname( __FILE__ ) . '/admin/assets/icon.jpg';
        }
        
        atp__fs()->add_filter( 'plugin_icon', 'atp__fs_custom_icon' );
        // freemius opt-in
        function atp__fs_custom_connect_message(
            $message,
            $user_first_name,
            $product_title,
            $user_login,
            $site_link,
            $freemius_link
        )
        {
            $break = "<br><br>";
            $more_plugins = '<p><a target="_blank" href="https://wordpress.org/plugins/meta-tags-for-seo/">Meta Tags for SEO</a>, <a target="_blank" href="https://wordpress.org/plugins/automatic-internal-links-for-seo/">Auto internal links for SEO</a>, <a target="_blank" href="https://wordpress.org/plugins/bulk-image-alt-text-with-yoast/">Bulk auto image Alt Text</a>, <a target="_blank" href="https://wordpress.org/plugins/bulk-image-title-attribute/">Bulk auto image Title Tag</a>, <a target="_blank" href="https://wordpress.org/plugins/mobilook/">Mobile view</a>, <a target="_blank" href="https://wordpress.org/plugins/better-robots-txt/">Wordpress Better-Robots.txt</a>, <a target="_blank" href="https://wordpress.org/plugins/wp-google-street-view/">Wp Google Street View</a>, <a target="_blank" href="https://wordpress.org/plugins/vidseo/">VidSeo</a>, ...</p>';
            return sprintf( esc_html__( 'Hey %1$s, %2$s Click on Allow & Continue to install Twitter Pixel. %2$s Never miss an important update -- opt-in to our security and feature updates notifications. %2$s See you on the other side.', 'bulk-image-title-attribute' ), $user_first_name, $break ) . $more_plugins;
        }
        
        atp__fs()->add_filter(
            'connect_message',
            'atp__fs_custom_connect_message',
            10,
            6
        );
    }
    
    class AddTwitterPixel
    {
        function __construct()
        {
            register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
            add_action( 'init', array( &$this, 'atp__textdomain' ) );
        }
        
        public function deactivate()
        {
            if ( \Pagup\Twitter\Core\Option::check( 'remove_settings' ) ) {
                delete_option( 'add-twitter-pixel' );
            }
        }
        
        function atp__textdomain()
        {
            load_plugin_textdomain( \Pagup\Twitter\Core\Plugin::domain(), false, basename( dirname( __FILE__ ) ) . '/languages' );
        }
    
    }
    $atp = new AddTwitterPixel();
    /*-----------------------------------------
                  TRACK CONTROLLER
      ------------------------------------------*/
    require_once \Pagup\Twitter\Core\Plugin::path( 'admin/controllers/TrackingController.php' );
    /*-----------------------------------------
                      Settings
      ------------------------------------------*/
    if ( is_admin() ) {
        include_once \Pagup\Twitter\Core\Plugin::path( 'admin/Settings.php' );
    }
}
