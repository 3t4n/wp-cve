<?php

/**
 * Plugin Name: Categorify
 * Plugin URI:  https://frenify.com/project/categorify/
 * Description: Organize your WordPress media files in categories via drag and drop.
 * Version:     1.0.7.5
 * Author:      Frenify
 * Author URI:  https://frenify.com/
 * Text Domain: categorify
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages/
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'cat_fs' ) ) {
    cat_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'cat_fs' ) ) {
        // Create a helper function for easy SDK access.
        function cat_fs()
        {
            global  $cat_fs ;
            
            if ( !isset( $cat_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $cat_fs = fs_dynamic_init( array(
                    'id'              => '9133',
                    'slug'            => 'categorify',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_e220c4ceaeae940de176acb6b2767',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Premium',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'trial'           => array(
                    'days'               => 3,
                    'is_require_payment' => false,
                ),
                    'has_affiliation' => 'all',
                    'menu'            => array(
                    'slug'       => 'categorify',
                    'first-path' => 'admin.php?page=categorify',
                    'support'    => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $cat_fs;
        }
        
        // Init Freemius.
        cat_fs();
        // Signal that SDK was initiated.
        do_action( 'cat_fs_loaded' );
    }
    
    // ... Plugin's main file logic ...
    define( 'CATEGORIFY__FILE__', __FILE__ );
    define( 'CATEGORIFY_TAXONOMY', 'categorify_category' );
    define( 'CATEGORIFY_PATH', plugin_dir_path( CATEGORIFY__FILE__ ) );
    define( 'CATEGORIFY_URL', plugins_url( '/', CATEGORIFY__FILE__ ) );
    define( 'CATEGORIFY_ASSETS_URL', CATEGORIFY_URL . 'assets/' );
    define( 'CATEGORIFY_TEXT_DOMAIN', 'categorify' );
    define( 'CATEGORIFY_PLUGIN_BASE', plugin_basename( CATEGORIFY__FILE__ ) );
    define( 'CATEGORIFY_PLUGIN_NAME', 'Categorify' );
    define( 'CATEGORIFY_PLUGIN_URL', plugin_dir_url( CATEGORIFY__FILE__ ) );
    define( 'CATEGORIFY_PLUGIN_VERSION', '1.0.7.5' );
    function categorify_plugins_loaded()
    {
        // include main plugin file
        include_once CATEGORIFY_PATH . 'inc/plugin.php';
        load_plugin_textdomain( CATEGORIFY_TEXT_DOMAIN, false, plugin_basename( __DIR__ ) . '/languages/' );
    }
    
    add_action( 'plugins_loaded', 'categorify_plugins_loaded' );
}
