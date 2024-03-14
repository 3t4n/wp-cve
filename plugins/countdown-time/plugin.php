<?php

/**
 * Plugin Name: Countdown Time - Block
 * Description: Display your events date into a timer to your visitor with countdown time block
 * Version: 1.2.4
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: countdown-time
 */
// ABS PATH
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'ctb_fs' ) ) {
    register_activation_hook( __FILE__, function () {
        if ( is_plugin_active( 'countdown-time/plugin.php' ) ) {
            deactivate_plugins( 'countdown-time/plugin.php' );
        }
        if ( is_plugin_active( 'countdown-time-pro/plugin.php' ) ) {
            deactivate_plugins( 'countdown-time-pro/plugin.php' );
        }
    } );
} else {
    define( 'CTB_VERSION', ( isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.2.4' ) );
    define( 'CTB_DIR_URL', plugin_dir_url( __FILE__ ) );
    define( 'CTB_DIR_PATH', plugin_dir_path( __FILE__ ) );
    define( 'CTB_HAS_FREE', 'countdown-time/plugin.php' === plugin_basename( __FILE__ ) );
    define( 'CTB_HAS_PRO', 'countdown-time-pro/plugin.php' === plugin_basename( __FILE__ ) );
    
    if ( !function_exists( 'ctb_fs' ) ) {
        function ctb_fs()
        {
            global  $ctb_fs ;
            
            if ( !isset( $ctb_fs ) ) {
                $fsStartPath = dirname( __FILE__ ) . '/freemius/start.php';
                $bSDKInitPath = dirname( __FILE__ ) . '/bplugins_sdk/init.php';
                
                if ( CTB_HAS_PRO && file_exists( $fsStartPath ) ) {
                    require_once $fsStartPath;
                } else {
                    if ( CTB_HAS_FREE && file_exists( $bSDKInitPath ) ) {
                        require_once $bSDKInitPath;
                    }
                }
                
                $ctbConfig = [
                    'id'                  => '14562',
                    'slug'                => 'countdown-time',
                    'premium_slug'        => 'countdown-time-pro',
                    'type'                => 'plugin',
                    'public_key'          => 'pk_7f62446a2a53154c56c36346db2fa',
                    'is_premium'          => true,
                    'premium_suffix'      => 'Pro',
                    'has_premium_version' => true,
                    'has_addons'          => false,
                    'has_paid_plans'      => true,
                    'trial'               => [
                    'days'               => 7,
                    'is_require_payment' => true,
                ],
                    'menu'                => [
                    'slug'    => 'edit.php?post_type=ctb',
                    'contact' => false,
                    'support' => false,
                ],
                ];
                $ctb_fs = ( CTB_HAS_PRO && file_exists( $fsStartPath ) ? fs_dynamic_init( $ctbConfig ) : fs_lite_dynamic_init( $ctbConfig ) );
            }
            
            return $ctb_fs;
        }
        
        ctb_fs();
        do_action( 'ctb_fs_loaded' );
    }
    
    if ( CTB_HAS_PRO ) {
        if ( function_exists( 'ctb_fs' ) ) {
            ctb_fs()->add_filter( 'freemius_pricing_js_path', function () {
                return CTB_DIR_PATH . 'inc/freemius-pricing/freemius-pricing.js';
            } );
        }
    }
    function ctbIsPremium()
    {
        return ( CTB_HAS_PRO ? ctb_fs()->can_use_premium_code() : false );
    }
    
    require_once CTB_DIR_PATH . '/inc/block.php';
    require_once CTB_DIR_PATH . '/inc/pattern.php';
    require_once CTB_DIR_PATH . '/inc/CustomPost.php';
    require_once CTB_DIR_PATH . '/inc/HelpPage.php';
    if ( CTB_HAS_FREE ) {
        require_once CTB_DIR_PATH . '/inc/UpgradePage.php';
    }
    class CTBPlugin
    {
        function __construct()
        {
            add_action( 'wp_ajax_ctbPipeChecker', [ $this, 'ctbPipeChecker' ] );
            add_action( 'wp_ajax_nopriv_ctbPipeChecker', [ $this, 'ctbPipeChecker' ] );
            add_action( 'admin_init', [ $this, 'registerSettings' ] );
            add_action( 'rest_api_init', [ $this, 'registerSettings' ] );
            add_filter( 'block_categories_all', [ $this, 'blockCategories' ] );
        }
        
        function ctbPipeChecker()
        {
            $nonce = $_POST['_wpnonce'] ?? null;
            if ( !wp_verify_nonce( $nonce, 'wp_ajax' ) ) {
                wp_send_json_error( 'Invalid Request' );
            }
            wp_send_json_success( [
                'isPipe' => ctbIsPremium(),
            ] );
        }
        
        function registerSettings()
        {
            register_setting( 'ctbUtils', 'ctbUtils', [
                'show_in_rest'      => [
                'name'   => 'ctbUtils',
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
        
        function blockCategories( $categories )
        {
            return array_merge( [ [
                'slug'  => 'CTBlock',
                'title' => 'Countdown Time',
            ] ], $categories );
        }
    
    }
    new CTBPlugin();
}
