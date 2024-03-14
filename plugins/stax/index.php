<?php

/**
 * Plugin Name: STAX Header Builder
 * Description: Header builder for WordPress. Create pixel perfect headers with ease. Works with any theme.
 * Plugin URI: https://staxbuilder.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Author: StaxWP
 * Version: 1.3.6
 * Author URI: https://staxbuilder.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 *
 * Text Domain: stax
 *
 * @package Stax
 * @category Core
 *
 *
 * Stax is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Stax is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
    // Exit if accessed directly.
}


if ( !function_exists( 'stax_fs' ) ) {
    define( 'STAX_VERSION', '1.3.6' );
    define( 'STAX_FILE', __FILE__ );
    define( 'STAX_PLUGIN_BASE', plugin_basename( STAX_FILE ) );
    define( 'STAX_BASE_URL', plugins_url( '/', STAX_FILE ) );
    define( 'STAX_BASE_PATH', plugin_dir_path( STAX_FILE ) );
    define( 'STAX_CORE_PATH', STAX_BASE_PATH . 'core/' );
    define( 'STAX_FRONT_PATH', STAX_BASE_PATH . 'front/' );
    define( 'STAX_FRONT_URL', STAX_BASE_URL . 'front/' );
    define( 'STAX_ASSETS_URL', STAX_BASE_URL . 'assets/' );
    define( 'STAX_API_NAMESPACE', 'stax' );
    /**
     * Create a helper function for easy SDK access.
     *
     * @return Freemius
     */
    function stax_fs()
    {
        global  $stax_fs ;
        
        if ( !isset( $stax_fs ) ) {
            // Include Freemius SDK.
            require_once STAX_CORE_PATH . 'lib/freemius/start.php';
            $stax_fs = fs_dynamic_init( array(
                'id'              => '1977',
                'slug'            => 'stax',
                'type'            => 'plugin',
                'public_key'      => 'pk_ae5f43d871441d1c2411eedbe5d76',
                'is_premium'      => false,
                'premium_suffix'  => 'Pro',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'has_affiliation' => 'selected',
                'menu'            => array(
                'slug'        => 'stax',
                'contact'     => false,
                'support'     => false,
                'affiliation' => false,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $stax_fs;
    }
    
    // Init Freemius.
    stax_fs();
    // Signal that SDK was initiated.
    do_action( 'stax_fs_loaded' );
    $fw_url = STAX_BASE_URL . 'assets/framework-base/';
    if ( stax_fs()->can_use_premium_code__premium_only() && is_dir( STAX_BASE_PATH . 'assets/framework/' ) ) {
        $fw_url = STAX_BASE_URL . 'assets/framework/';
    }
    if ( defined( 'STAX_DEV' ) && STAX_DEV ) {
        $fw_url = STAX_BASE_URL . 'assets/framework/';
    }
    define( 'STAX_ASSETS_FW_URL', $fw_url );
    require_once STAX_CORE_PATH . 'plugin.php';
    function stax_fs_uninstall_cleanup()
    {
        global  $wpdb ;
        $tables = [
            $wpdb->prefix . 'stax_zones',
            $wpdb->prefix . 'stax_containers',
            $wpdb->prefix . 'stax_columns',
            $wpdb->prefix . 'stax_elements',
            $wpdb->prefix . 'stax_container_viewport',
            $wpdb->prefix . 'stax_container_items',
            $wpdb->prefix . 'stax_templates',
            $wpdb->prefix . 'stax_components'
        ];
        foreach ( $tables as $table ) {
            $wpdb->query( "DROP TABLE IF EXISTS {$table}" );
        }
        delete_option( 'stax-render-status' );
        delete_option( 'stax-version' );
        delete_option( 'stax-upgrades' );
    }
    
    stax_fs()->add_action( 'after_uninstall', 'stax_fs_uninstall_cleanup' );
}
