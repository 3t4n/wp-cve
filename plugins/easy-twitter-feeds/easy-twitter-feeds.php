<?php

/*
 * Plugin Name: Easy Twitter Feeds
 * Plugin URI:  https://twitter-feed.bplugins.com/
 * Description: You can Embed your Twitter timeline feed, Follow widget anywhere in WordPress using Shortcode.  
 * Version: 1.2.5
 * Author: bPlugins LLC
 * Author URI: https://bplugins.com/
 * Text Domain:  easy-twitter
 * Domain Path:  /languages
 * License: GPLv3
 */
// ABS PATH
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/*Some Set-up*/
define( 'ETF_VERSION', '1.2.4' );
define( 'ETF_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'ETF_DIR_PATH', plugin_dir_path( __FILE__ ) );
// freemius integration

if ( !function_exists( 'etf_fs' ) ) {
    // Create a helper function for easy SDK access.
    function etf_fs()
    {
        global  $etf_fs ;
        
        if ( !isset( $etf_fs ) ) {
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $etf_fs = fs_dynamic_init( array(
                'id'             => '14839',
                'slug'           => 'easy-twitter-feeds',
                'premium_slug'   => 'easy-twitter-feeds-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_ba9a28a91e7b8f97d024123dad59c',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => false,
            ),
                'menu'           => array(
                'slug'    => 'edit.php?post_type=easy-twitter-feeds',
                'contact' => false,
                'support' => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $etf_fs;
    }
    
    etf_fs();
    do_action( 'etf_fs_loaded' );
}

require_once ETF_DIR_PATH . 'inc/block.php';
require_once ETF_DIR_PATH . 'inc/CustomPost.php';
require_once ETF_DIR_PATH . 'inc/ShortCode.php';
function etfIsPremium()
{
    return etf_fs()->is__premium_only() && etf_fs()->can_use_premium_code();
}

class ETFPlugin
{
    public function __construct()
    {
        add_action( 'init', [ $this, 'onInit' ] );
        add_action( 'wp_ajax_etfPipeChecker', [ $this, 'etfPipeChecker' ] );
        add_action( 'wp_ajax_nopriv_etfPipeChecker', [ $this, 'etfPipeChecker' ] );
        add_action( 'admin_init', [ $this, 'registerSettings' ] );
        add_action( 'rest_api_init', [ $this, 'registerSettings' ] );
    }
    
    function onInit()
    {
        load_plugin_textdomain( 'easy-twitter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }
    
    function etfPipeChecker()
    {
        $nonce = $_POST['_wpnonce'] ?? null;
        if ( !wp_verify_nonce( $nonce, 'wp_ajax' ) ) {
            wp_send_json_error( 'Invalid Request' );
        }
        wp_send_json_success( [
            'isPipe' => etfIsPremium(),
        ] );
    }
    
    function registerSettings()
    {
        register_setting( 'etfUtils', 'etfUtils', [
            'show_in_rest'      => [
            'name'   => 'etfUtils',
            'schema' => [
            'type' => 'string',
        ],
        ],
            'type'              => 'string',
            'default'           => wp_json_encode( [
            'nonce' => wp_create_nonce( 'wp_ajax' ),
        ] ),
            'sanitize_callback' => 'sanitize_text_field',
        ] );
    }

}
new ETFPlugin();