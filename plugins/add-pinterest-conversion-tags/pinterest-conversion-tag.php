<?php

/*
* Plugin Name: Pctags - Pinterest Conversion Tags
* Description: The Pinterest conversion tags plugin allows to add strategically your Pinterest TAG ID on all your webpages (with the base code). No need to edit your theme files!
* Author: Pagup
* Version: 1.2.5
* Author URI: https://pagup.com/
* Text Domain: add-pinterest-conversion-tags
* Domain Path: /languages/
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/******************************************
                Freemius Init
*******************************************/

if ( function_exists( 'pctag_fs' ) ) {
    pctag_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'pctag_fs' ) ) {
        if ( !defined( 'PCTAG_PLUGIN_BASE' ) ) {
            define( 'PCTAG_PLUGIN_BASE', plugin_basename( __FILE__ ) );
        }
        if ( !defined( 'PCTAG_PLUGIN_DIR' ) ) {
            define( 'PCTAG_PLUGIN_DIR', plugins_url( '', __FILE__ ) );
        }
        require 'vendor/autoload.php';
        // Create a helper function for easy SDK access.
        function pctag_fs()
        {
            global  $pctag_fs ;
            
            if ( !isset( $pctag_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $pctag_fs = fs_dynamic_init( array(
                    'id'              => '3054',
                    'slug'            => 'pctags-pinterest-conversion-tags',
                    'premium_slug'    => 'pctags-pinterest-conversion-tag-premium',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_7f3d162827e52ef0e6f8e46b2725f',
                    'is_premium'      => false,
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'has_affiliation' => 'all',
                    'menu'            => array(
                    'slug'       => 'pctag',
                    'first-path' => 'admin.php?page=pctag',
                    'support'    => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $pctag_fs;
        }
        
        // Init Freemius.
        pctag_fs();
        // Signal that SDK was initiated.
        do_action( 'pctag_fs_loaded' );
        function pctag_fs_settings_url()
        {
            return admin_url( 'admin.php?page=pctag' );
        }
        
        pctag_fs()->add_filter( 'connect_url', 'pctag_fs_settings_url' );
        pctag_fs()->add_filter( 'after_skip_url', 'pctag_fs_settings_url' );
        pctag_fs()->add_filter( 'after_connect_url', 'pctag_fs_settings_url' );
        pctag_fs()->add_filter( 'after_pending_connect_url', 'pctag_fs_settings_url' );
        function pctag_fs_custom_icon()
        {
            return dirname( __FILE__ ) . '/admin/assets/imgs/icon.jpg';
        }
        
        pctag_fs()->add_filter( 'plugin_icon', 'pctag_fs_custom_icon' );
        // freemius opt-in
        function pctag_fs_custom_connect_message(
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
            return sprintf( esc_html__( 'Hey %1$s, %2$s Click on Allow & Continue to activate PCTAGs and track conversions on your website :)! The Pinterest tag (with base & event codes) allows you to track actions people take on your website after viewing your Promoted Pin, measure return on ad spend (RoAS) and create audiences to target on your Promoted Pins. %2$s Never miss an important update -- opt-in to our security and feature updates notifications. %2$s See you on the other side.', 'add-pinterest-conversion-tags' ), $user_first_name, $break ) . $more_plugins;
        }
        
        pctag_fs()->add_filter(
            'connect_message',
            'pctag_fs_custom_connect_message',
            10,
            6
        );
    }
    
    class pctag
    {
        function __construct()
        {
            register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
            add_action( 'init', array( &$this, 'pctag_textdomain' ) );
        }
        
        public function deactivate()
        {
            if ( \Pagup\Pctag\Core\Option::check( 'pctag_remove_settings' ) ) {
                delete_option( 'pctag' );
            }
        }
        
        function pctag_textdomain()
        {
            load_plugin_textdomain( "add-pinterest-conversion-tags", false, basename( dirname( __FILE__ ) ) . '/languages' );
        }
    
    }
    $pctag = new pctag();
    /*-----------------------------------------
                  TRACK CONTROLLER
      ------------------------------------------*/
    require_once \Pagup\Pctag\Core\Plugin::path( 'admin/controllers/TrackingController.php' );
    /*-----------------------------------------
                      Settings
      ------------------------------------------*/
    if ( is_admin() ) {
        include_once \Pagup\Pctag\Core\Plugin::path( 'admin/Settings.php' );
    }
}
