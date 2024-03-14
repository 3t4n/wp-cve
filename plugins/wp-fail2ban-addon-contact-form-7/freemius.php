<?php

declare ( strict_types = 1 );
/**
 * Freemius integration.
 *
 * @package wp-fail2ban-addon-contact-form-7
 * @since 1.1.0
 */
namespace com\wp_fail2ban\addons\ContactForm7\Freemius;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) or exit;
if ( !function_exists( __NAMESPACE__ . '\\wpf2b_addon_cf7_fs' ) ) {
    /**
     * Create a helper function for easy SDK access.
     *
     * @since 1.0.0
     *
     * @return mixed
     */
    function wpf2b_addon_cf7_fs()
    {
        global  $wpf2b_addon_cf7_fs ;
        
        if ( !isset( $wpf2b_addon_cf7_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_3564_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_3564_MULTISITE', true );
            }
            // Include Freemius SDK.
            
            if ( file_exists( dirname( __DIR__ ) . '/wp-fail2ban/vendor/freemius/wordpress-sdk/start.php' ) ) {
                // Try to load SDK from parent plugin folder.
                require_once dirname( __DIR__ ) . '/wp-fail2ban/vendor/freemius/wordpress-sdk/start.php';
            } elseif ( file_exists( dirname( __DIR__ ) . '/wp-fail2ban-premium/vendor/freemius/wordpress-sdk/start.php' ) ) {
                // Try to load SDK from premium parent plugin folder.
                require_once dirname( __DIR__ ) . '/wp-fail2ban-premium/vendor/freemius/wordpress-sdk/start.php';
            } else {
                return false;
            }
            
            $wpf2b_addon_cf7_fs = fs_dynamic_init( array(
                'id'             => '3564',
                'slug'           => 'wp-fail2ban-addon-contact-form-7',
                'premium_slug'   => 'wp-fail2ban-addon-contact-form-7-premium',
                'type'           => 'plugin',
                'public_key'     => 'pk_7e0e413b0ffb7551b21cf57501455',
                'is_premium'     => false,
                'has_paid_plans' => false,
                'parent'         => array(
                'id'         => '3072',
                'slug'       => 'wp-fail2ban',
                'public_key' => 'pk_146d2c2a5bee3b157e43501ef8682',
                'name'       => 'WP fail2ban',
            ),
                'menu'           => array(
                'first-path' => 'admin.php?page=wp-fail2ban_addon_contactform7',
                'account'    => false,
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $wpf2b_addon_cf7_fs;
    }

}
/**
 * Freemius boilerplate
 *
 * @since 1.0.0
 *
 * @return bool
 */
function wpf2b_addon_cf7_fs_is_parent_active_and_loaded()
{
    // Check if the parent's init SDK method exists.
    return function_exists( 'org\\lecklider\\charles\\wordpress\\wp_fail2ban\\wf_fs' );
}

/**
 * Freemius boilerplate
 *
 * @since 1.0.0
 *
 * @return bool
 */
function wpf2b_addon_cf7_fs_is_parent_active()
{
    $active_plugins = get_option( 'active_plugins', array() );
    
    if ( is_multisite() ) {
        $network_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
        $active_plugins = array_merge( $active_plugins, array_keys( $network_active_plugins ) );
    }
    
    foreach ( $active_plugins as $basename ) {
        if ( 0 === strpos( $basename, 'wp-fail2ban/' ) || 0 === strpos( $basename, 'wp-fail2ban-premium/' ) ) {
            return true;
        }
    }
    return false;
}

/**
 * Freemius boilerplate
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpf2b_addon_cf7_fs_init()
{
    
    if ( wpf2b_addon_cf7_fs_is_parent_active_and_loaded() ) {
        // Init Freemius.
        
        if ( null === ($fs = wpf2b_addon_cf7_fs()) ) {
            // @TODO
        } else {
            $fs->add_filter( 'redirect_on_activation', function ( $true ) {
                assert( $true );
                fs_redirect( network_admin_url( 'admin.php?page=wp-fail2ban_addon_contactform7' ) );
            } );
            // Signal that the add-on's SDK was initiated.
            do_action( 'wpf2b_addon_cf7_fs_loaded' );
            require_once 'functions.php';
            require_once 'init.php';
            add_action( 'init', WP_FAIL2BAN_ADDON_CF7_NS . '\\init' );
        }
    
    } else {
        // Parent is inactive, add your error handling here.
    }

}


if ( wpf2b_addon_cf7_fs_is_parent_active_and_loaded() ) {
    // If parent already included, init add-on.
    wpf2b_addon_cf7_fs_init();
} elseif ( wpf2b_addon_cf7_fs_is_parent_active() ) {
    // Init add-on only after the parent is loaded.
    add_action( 'wf_fs_loaded', __NAMESPACE__ . '\\wpf2b_addon_cf7_fs_init' );
} else {
    // Even though the parent is not activated, execute add-on for activation / uninstall hooks.
    wpf2b_addon_cf7_fs_init();
}
