<?php

/*
* Plugin Name: Better Robots.txt - Index, rank & SEO booster + Woocommerce
* Description: Better-Robots.txt plugin helps you boosting your website indexation and your ranking by adding specific instructions in your robots.txt
* Author: Pagup
* Version: 2.0.0
* Author URI: https://pagup.com/
* Text Domain: better-robots-txt
* Domain Path: /languages/
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/******************************************
                Freemius Init
*******************************************/

if ( function_exists( 'rtf_fs' ) ) {
    rtf_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'rtf_fs' ) ) {
        if ( !defined( 'ROBOTS_PLUGIN_BASE' ) ) {
            define( 'ROBOTS_PLUGIN_BASE', plugin_basename( __FILE__ ) );
        }
        if ( !defined( 'ROBOTS_PLUGIN_DIR' ) ) {
            define( 'ROBOTS_PLUGIN_DIR', plugins_url( '', __FILE__ ) );
        }
        if ( !defined( 'ROBOTS_PLUGIN_MODE' ) ) {
            define( 'ROBOTS_PLUGIN_MODE', "prod" );
        }
        require 'vendor/autoload.php';
        // Create a helper function for easy SDK access.
        function rtf_fs()
        {
            global  $rtf_fs ;
            
            if ( !isset( $rtf_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $rtf_fs = fs_dynamic_init( array(
                    'id'              => '2345',
                    'slug'            => 'better-robots-txt',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_fc28da2ba58a7288429539266f4db',
                    'is_premium'      => false,
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 14,
                    'is_require_payment' => true,
                ),
                    'has_affiliation' => 'all',
                    'menu'            => array(
                    'slug'       => 'better-robots-txt',
                    'first-path' => 'admin.php?page=better-robots-txt',
                    'support'    => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $rtf_fs;
        }
        
        // Init Freemius.
        rtf_fs();
        // Signal that SDK was initiated.
        do_action( 'rtf_fs_loaded' );
        function rtf_fs_settings_url()
        {
            return admin_url( 'admin.php?page=better-robots-txt' );
        }
        
        rtf_fs()->add_filter( 'connect_url', 'rtf_fs_settings_url' );
        rtf_fs()->add_filter( 'after_skip_url', 'rtf_fs_settings_url' );
        rtf_fs()->add_filter( 'after_connect_url', 'rtf_fs_settings_url' );
        rtf_fs()->add_filter( 'after_pending_connect_url', 'rtf_fs_settings_url' );
        // freemius opt-in
        function rtf_fs_custom_connect_message(
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
            return sprintf( __( 'Hey %1$s, %2$s Click on Allow & Continue to start optimizing your website with your robots.txt :)!  Create a powerful robots.txt with clear instructions for crawlers to get better results on search engines and improve your SEO. %2$s Never miss an important update -- opt-in to our security and feature updates notifications. %2$s See you on the other side. %2$s Looking for more Wp plugins?', 'better-robots-txt' ), $user_first_name, $break ) . $more_plugins;
        }
        
        rtf_fs()->add_filter(
            'connect_message',
            'rtf_fs_custom_connect_message',
            10,
            6
        );
    }
    
    class BetterRobots
    {
        function __construct()
        {
            register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
            add_action( 'init', array( &$this, 'pctag_textdomain' ) );
        }
        
        public function deactivate()
        {
            
            if ( \Pagup\BetterRobots\Core\Option::check( 'remove_settings' ) ) {
                delete_option( 'robots_txt' );
                delete_option( 'robots_tour' );
            }
        
        }
        
        function pctag_textdomain()
        {
            load_plugin_textdomain( "better-robots-txt", false, basename( dirname( __FILE__ ) ) . '/languages' );
        }
    
    }
    $BetterRobots = new BetterRobots();
    /*-----------------------------------------
                  TRACK CONTROLLER
      ------------------------------------------*/
    require_once 'admin/controllers/RobotsController.php';
    /*-----------------------------------------
                      Settings
      ------------------------------------------*/
    if ( is_admin() ) {
        include_once 'admin/Settings.php';
    }
}
