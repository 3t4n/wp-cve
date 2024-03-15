<?php

/**
 * Plugin Name: Music Player for Elementor
 * Plugin URI: https://smartwpress.com/music-player-for-elementor-wordpress-plugin/
 * Description: Music Player For Elementor is a stylish audio player addon for Elementor. Promote your music with an easy to use and highly customizable mp3 player and audio player.
 * Version: 1.9
 * Tested up to: 6.4.3
 * Elementor tested up to: 3.19.2
 * Author: SmartWPress
 * Author URI: https://www.smartwpress.com
 * Text Domain: music-player-for-elementor
 * Domain Path: /languages
 * License: GNU General Public License version 2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( !function_exists( 'mpfe_fs' ) ) {
    // Create a helper function for easy SDK access.
    function mpfe_fs()
    {
        global  $mpfe_fs ;
        
        if ( !isset( $mpfe_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $mpfe_fs = fs_dynamic_init( array(
                'id'             => '9253',
                'slug'           => 'music-player-for-elementor',
                'type'           => 'plugin',
                'public_key'     => 'pk_2b1ebb46c2b0a776611c41a8a29ef',
                'is_premium'     => false,
                'premium_suffix' => '',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => false,
            ),
                'menu'           => array(
                'slug'       => 'mpfe-dashboard',
                'first-path' => 'admin.php?page=mpfe-dashboard',
            ),
                'is_live'        => true,
            ) );
        }
        
        return $mpfe_fs;
    }
    
    // Init Freemius.
    mpfe_fs();
    // Signal that SDK was initiated.
    do_action( 'mpfe_fs_loaded' );
}

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !defined( 'MPFE_VERSION' ) ) {
    define( 'MPFE_VERSION', '1.9' );
}
if ( !defined( 'MPFE_DIR_PATH' ) ) {
    define( 'MPFE_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'MPFE_DIR_URL' ) ) {
    define( 'MPFE_DIR_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'MPFE_BASE' ) ) {
    define( 'MPFE_BASE', plugin_basename( __FILE__ ) );
}
if ( !defined( 'MPFE_PLUGIN_FILE' ) ) {
    define( 'MPFE_PLUGIN_FILE', __FILE__ );
}
require_once MPFE_DIR_PATH . 'classes/core/load-music-player-for-elementor.php';