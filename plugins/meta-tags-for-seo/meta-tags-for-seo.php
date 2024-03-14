<?php

/*
* Plugin Name: Meta Tags for SEO
* Description: META TAGS for SEO allows you to display custom META Keywords strategically (based on Yoast / Rank Math in some cases) to boost your ranking on search engines.
* Author: Pagup
* Version: 1.1.3
* Author URI: https://pagup.com/
* Text Domain: meta-tags-for-seo
* Domain Path: /languages/
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/******************************************
                Freemius Init
*******************************************/

if ( function_exists( 'pmt__fs' ) ) {
    pmt__fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'pmt__fs' ) ) {
        if ( !defined( 'PMT_PLUGIN_BASE' ) ) {
            define( 'PMT_PLUGIN_BASE', plugin_basename( __FILE__ ) );
        }
        if ( !defined( 'PMT_PLUGIN_DIR' ) ) {
            define( 'PMT_PLUGIN_DIR', plugins_url( '', __FILE__ ) );
        }
        require 'vendor/autoload.php';
        // Create a helper function for easy SDK access.
        function pmt__fs()
        {
            global  $pmt__fs ;
            
            if ( !isset( $pmt__fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $pmt__fs = fs_dynamic_init( array(
                    'id'              => '8127',
                    'slug'            => 'meta-tags-for-seo',
                    'premium_slug'    => 'meta-tags-for-seo-pro',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_653375e6160cc7172763b461926b7',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Pro',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'has_affiliation' => 'all',
                    'menu'            => array(
                    'slug'       => 'meta-tags-for-seo',
                    'first-path' => 'admin.php?page=meta-tags-for-seo',
                    'support'    => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $pmt__fs;
        }
        
        // Init Freemius.
        pmt__fs();
        // Signal that SDK was initiated.
        do_action( 'pmt__fs_loaded' );
        function pmt__fs_settings_url()
        {
            return admin_url( 'admin.php?page=meta-tags-for-seo' );
        }
        
        pmt__fs()->add_filter( 'connect_url', 'pmt__fs_settings_url' );
        pmt__fs()->add_filter( 'after_skip_url', 'pmt__fs_settings_url' );
        pmt__fs()->add_filter( 'after_connect_url', 'pmt__fs_settings_url' );
        pmt__fs()->add_filter( 'after_pending_connect_url', 'pmt__fs_settings_url' );
        function pmt__fs_custom_icon()
        {
            return dirname( __FILE__ ) . '/admin/assets/icon.png';
        }
        
        pmt__fs()->add_filter( 'plugin_icon', 'pmt__fs_custom_icon' );
        // freemius opt-in
        function pmt__fs_custom_connect_message(
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
            return sprintf( esc_html__( 'Hey %1$s, %2$s Click on Allow & Continue to optimize your META keywords for SEO. %2$s Never miss an important update -- opt-in to our security and feature updates notifications. %2$s See you on the other side.', 'meta-tags-for-seo' ), $user_first_name, $break ) . $more_plugins;
        }
        
        pmt__fs()->add_filter(
            'connect_message',
            'pmt__fs_custom_connect_message',
            10,
            6
        );
    }
    
    class MetaTagsForSEO
    {
        function __construct()
        {
            register_activation_hook( __FILE__, array( &$this, 'activate' ) );
            register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
            add_action( 'init', array( &$this, 'pmt__textdomain' ) );
        }
        
        public function activate()
        {
            update_option( 'meta-tags-for-seo', [
                'meta_tags' => array(),
            ] );
        }
        
        public function deactivate()
        {
            if ( \Pagup\MetaTags\Core\Option::check( 'remove_settings' ) ) {
                delete_option( 'meta-tags-for-seo' );
            }
        }
        
        function pmt__textdomain()
        {
            load_plugin_textdomain( \Pagup\MetaTags\Core\Plugin::domain(), false, basename( dirname( __FILE__ ) ) . '/languages' );
        }
    
    }
    $atp = new MetaTagsForSEO();
    /*-----------------------------------------
                  TRACK CONTROLLER
      ------------------------------------------*/
    require_once \Pagup\MetaTags\Core\Plugin::path( 'admin/controllers/TagsController.php' );
    /*-----------------------------------------
                      Settings
      ------------------------------------------*/
    if ( is_admin() ) {
        include_once \Pagup\MetaTags\Core\Plugin::path( 'admin/Settings.php' );
    }
}
