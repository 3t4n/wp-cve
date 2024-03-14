<?php

/**
 * Plugin Name: Easy Age Verify
 *
 * Description: Adds a mobile friendly age verification screen to adults only, vape or alcohol websites. Get set-up in minutes.
 * Author:      5 Star Plugins
 * Author URI:  https://5starplugins.com/
 * Version:     1.8.2
 *
 * Requires at least: 4.6
 * Requires PHP: 5.6
 *
 * Text Domain: easy-age-verify
 * Domain Path: /languages
 * License: GPLv2 or later
 *
 *
 * Copyright 2021 5 Star Plugins
 *
 * The following code is a derivative work of the code from Chase Wiseman, which is licensed GPLv2.
 * This code is then also licensed under the terms of the GPLv2.
 */
/**
 * The main plugin file.
 *
 * This file loads the main plugin class and gets things running.
 *
 * @since   1.0
 *
 * @package Easy_Age_Verify
 *
 */
if ( !defined( 'WPINC' ) ) {
    die;
}
define( 'EVAV_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'EVAV_PLUGIN_FILE', plugin_basename( __FILE__ ) );

if ( function_exists( 'evav_fs' ) ) {
    evav_fs()->set_basename( false, __FILE__ );
    return;
}

// Don't allow this file to be accessed directly.
if ( !function_exists( 'evav_fs' ) ) {
    // Create a helper function for easy SDK access.
    /**
     * @return Freemius
     * @throws Freemius_Exception
     */
    function evav_fs()
    {
        global  $evav_fs ;

        if ( !isset( $evav_fs ) ) {
            // Include Freemius SDK.
            require_once EVAV_PLUGIN_DIR_PATH . 'includes/freemius/start.php';
            $evav_fs = fs_dynamic_init( array(
                'id'              => '3551',
                'slug'            => 'easy-age-verify',
                'type'            => 'plugin',
                'public_key'      => 'pk_88a8f3865bc74bf9dcbe507dd437a',
                'is_premium'      => false,
                'premium_suffix'  => 'Premium',
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => false,
            ),
                'has_affiliation' => 'all',
                'menu'            => array(
                'slug' => 'easy-age-verify',
            ),
                'is_live'         => true,
            ) );
        }

        return $evav_fs;
    }

}
// Init Freemius.
global  $evav_fs ;
$evav_fs = evav_fs();
$evav_fs->add_filter(
    'connect_message_on_update',
    'evav_fs_custom_connect_message_on_update',
    10,
    6
);
$evav_fs->add_filter(
    'connect_message',
    'evav_freemius_new_message',
    10,
    6
);
// Signal that SDK was initiated.
do_action( 'evav_fs_loaded' );
if ( !function_exists( 'evav_fs_settings_url' ) ) {
    function evav_fs_settings_url()
    {
        return admin_url( 'admin.php?page=easy-age-verify' );
    }

}
$evav_fs->add_filter( 'connect_url', 'evav_fs_settings_url' );
$evav_fs->add_filter( 'after_skip_url', 'evav_fs_settings_url' );
$evav_fs->add_filter( 'after_connect_url', 'evav_fs_settings_url' );
$evav_fs->add_filter( 'after_pending_connect_url', 'evav_fs_settings_url' );
/**
 * The main class definition.
 */
require EVAV_PLUGIN_DIR_PATH . 'includes/class-easy-age-verify.php';
// Get the plugin running.
add_action( 'plugins_loaded', array( 'Easy_Age_Verify', 'get_instance' ) );
// Check that the admin is loaded.

if ( is_admin() ) {
    /**
     * The admin class definition.
     */
    require EVAV_PLUGIN_DIR_PATH . 'includes/admin/class-easy-age-verify-admin.php';
    // Get the plugin's admin running.
    add_action( 'plugins_loaded', array( 'Easy_Age_Verify_Admin', 'get_instance' ) );
}
