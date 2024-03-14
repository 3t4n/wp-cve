<?php

/**
 * Plugin Name: Dracula Dark Mode
 * Plugin URI: https://softlabbd.com/dracula-dark-mode
 * Description: AI-powered dark mode solution for WordPress, to create a stunning dark mode theme for your website.
 * Version: 1.2.0
 * Author: SoftLab
 * Author URI: https://softlabbd.com
 * License: GPLv2 or later
 * Text Domain: dracula-dark-mode
 * Domain Path: /languages/
 *
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( function_exists( 'ddm_fs' ) ) {
    ddm_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'ddm_fs' ) ) {
        // Create a helper function for easy SDK access.
        function ddm_fs()
        {
            global  $ddm_fs ;
            
            if ( !isset( $ddm_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $ddm_fs = fs_dynamic_init( array(
                    'id'             => '11821',
                    'slug'           => 'dracula-dark-mode',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_ccbb1ab247a8d4b30a84b68c27ecf',
                    'is_premium'     => false,
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'       => 'dracula',
                    'first-path' => 'admin.php?page=dracula-getting-started',
                    'contact'    => false,
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $ddm_fs;
        }
        
        // Init Freemius.
        ddm_fs();
        // Signal that SDK was initiated.
        do_action( 'ddm_fs_loaded' );
    }
    
    /** define constants */
    define( 'DRACULA_VERSION', '1.2.0' );
    define( 'DRACULA_FILE', __FILE__ );
    define( 'DRACULA_PATH', dirname( DRACULA_FILE ) );
    define( 'DRACULA_INCLUDES', DRACULA_PATH . '/includes' );
    define( 'DRACULA_URL', plugins_url( '', DRACULA_FILE ) );
    define( 'DRACULA_ASSETS', DRACULA_URL . '/assets' );
    define( 'DRACULA_TEMPLATES', DRACULA_INCLUDES . '/templates' );
    //Include the base plugin file.
    include_once DRACULA_INCLUDES . '/base.php';
}
