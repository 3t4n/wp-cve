<?php

/*
Plugin Name: Primary Addon for Elementor
Plugin URI: https://nicheaddons.com/demos/primary
Description: Primary Addon for Elementor covers all the must-needed elements for creating a perfect websites using Elementor Page Builder. 20+ Common Elementor widget covers all of the Primary elements.
Author: NicheAddons
Author URI: https://nicheaddons.com/
Version: 1.5.4
Text Domain: primary-addon-for-elementor
*/
include_once ABSPATH . 'wp-admin/includes/plugin.php';
// Freemius Code

if ( !function_exists( 'pafe_fs' ) ) {
    // Create a helper function for easy SDK access.
    function pafe_fs()
    {
        global  $pafe_fs ;
        
        if ( !isset( $pafe_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $pafe_fs = fs_dynamic_init( array(
                'id'             => '6452',
                'slug'           => 'primary-addon-for-elementor',
                'premium_slug'   => 'primary-addon-for-elementor-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_01658fce590ad5577db459c36af48',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'           => 'napae_admin_page',
                'override_exact' => true,
                'support'        => false,
                'parent'         => array(
                'slug' => 'napae_admin_page',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        return $pafe_fs;
    }
    
    // Init Freemius.
    pafe_fs();
    // Signal that SDK was initiated.
    do_action( 'pafe_fs_loaded' );
}

/**
 * Enqueue Files for BackEnd
 */

if ( !function_exists( 'napae_admin_scripts_styles' ) ) {
    function napae_admin_scripts_styles()
    {
        wp_enqueue_style( 'napae-admin-styles', plugins_url( '/', __FILE__ ) . 'assets/css/admin-styles.css', true );
    }
    
    add_action( 'admin_enqueue_scripts', 'napae_admin_scripts_styles' );
}

// Admin Pages
require_once plugin_dir_path( __FILE__ ) . '/elementor/napae-admin-page.php';
require_once plugin_dir_path( __FILE__ ) . '/elementor/napae-admin-sub-page.php';
require_once plugin_dir_path( __FILE__ ) . '/elementor/napae-admin-basic-fields.php';
add_action( 'admin_init', 'pafe_bw_settings_init' );
// is_premium

if ( !function_exists( 'napae_admin_menu' ) ) {
    add_action( 'admin_menu', 'napae_admin_menu' );
    function napae_admin_menu()
    {
        add_menu_page(
            'Primary Addon for Elementor',
            'Primary Addon',
            'manage_options',
            'napae_admin_page',
            'napae_admin_page',
            'dashicons-carrot',
            80
        );
        add_submenu_page(
            'napae_admin_page',
            'Enable & Disable',
            'Enable & Disable',
            'manage_options',
            'napae_admin_sub_page',
            'napae_admin_sub_page'
        );
    }

}

// ABSPATH
if ( !function_exists( 'prim_block_direct_access' ) ) {
    function prim_block_direct_access()
    {
        if ( !defined( 'ABSPATH' ) ) {
            exit( 'Forbidden' );
        }
    }

}
// Initial File
if ( is_plugin_active( 'elementor/elementor.php' ) ) {
    require_once plugin_dir_path( __FILE__ ) . '/elementor/em-setup.php';
}
// is_premium
// Plugin language

if ( !function_exists( 'prim_plugin_language_setup' ) ) {
    function prim_plugin_language_setup()
    {
        load_plugin_textdomain( 'primary-addon-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }
    
    add_action( 'init', 'prim_plugin_language_setup' );
}

// Check if Elementor installed and activated

if ( !function_exists( 'prim_load_plugin' ) ) {
    function prim_load_plugin()
    {
        
        if ( !did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', 'admin_notice_missing_main_plugin' );
            return;
        }
    
    }
    
    add_action( 'plugins_loaded', 'prim_load_plugin' );
}

// Warning when the site doesn't have Elementor installed or activated.
if ( !function_exists( 'admin_notice_missing_main_plugin' ) ) {
    function admin_notice_missing_main_plugin()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'primary-addon-for-elementor' ),
            '<strong>' . esc_html__( 'Primary Addon for Elementor', 'primary-addon-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'primary-addon-for-elementor' ) . '</strong>'
        );
        printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );
    }

}
// Both Free and Pro activated
if ( is_plugin_active( 'primary-addon-for-elementor/primary-addon-for-elementor.php' ) && is_plugin_active( 'primary-addon-for-elementor-pro/primary-addon-for-elementor.php' ) ) {
    add_action( 'admin_notices', 'admin_notice_deactivate_free' );
}
// Warning when the site have Both Free and Pro activated.
if ( !function_exists( 'admin_notice_deactivate_free' ) ) {
    function admin_notice_deactivate_free()
    {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        $message = sprintf(
            /* translators: 1: Plugin name */
            esc_html__( 'Please deactivate the free version of "%1$s".', 'primary-addon-for-elementor' ),
            '<strong>' . esc_html__( 'Primary Addon for Elementor', 'primary-addon-for-elementor' ) . '</strong>'
        );
        printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );
    }

}
// Enable & Dissable Notice
add_action( 'admin_notices', 'admin_notice_enable_dissable' );
if ( !function_exists( 'admin_notice_enable_dissable' ) ) {
    function admin_notice_enable_dissable()
    {
        
        if ( isset( $_GET['settings-updated'] ) ) {
            $message = sprintf( esc_html__( 'Widgets Settings Saved.', 'primary-addon-for-elementor' ) );
            printf( '<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', $message );
        }
    
    }

}
// Enqueue Files for Elementor Editor

if ( is_plugin_active( 'elementor/elementor.php' ) ) {
    // Css Enqueue
    add_action( 'elementor/editor/before_enqueue_scripts', function () {
        wp_enqueue_style(
            'prim-ele-editor-linea',
            plugins_url( '/', __FILE__ ) . 'assets/css/linea.min.css',
            [],
            '1.0.0'
        );
        wp_enqueue_style(
            'prim-ele-editor-themify',
            plugins_url( '/', __FILE__ ) . 'assets/css/themify-icons.min.css',
            [],
            '1.0.0'
        );
        wp_enqueue_style(
            'prim-ele-editor-icofont',
            plugins_url( '/', __FILE__ ) . 'assets/css/icofont.min.css',
            [],
            '1.0.1'
        );
    } );
    // Js Enqueue
    add_action( 'elementor/frontend/after_enqueue_scripts', function () {
        wp_enqueue_script(
            'prim-chartjs',
            plugins_url( '/', __FILE__ ) . 'assets/js/Chart.min.js',
            array( 'jquery' ),
            '2.9.3',
            true
        );
    } );
}

// Enqueue Files for FrontEnd

if ( !function_exists( 'prim_scripts_styles' ) ) {
    function prim_scripts_styles()
    {
        // Styles
        wp_enqueue_style(
            'niche-frame',
            plugins_url( '/', __FILE__ ) . 'assets/css/niche-frame.css',
            array(),
            '1.2',
            'all'
        );
        wp_enqueue_style(
            'font-awesome',
            plugins_url( '/', __FILE__ ) . 'assets/css/font-awesome.min.css',
            array(),
            '4.7.0',
            'all'
        );
        wp_enqueue_style(
            'animate',
            plugins_url( '/', __FILE__ ) . 'assets/css/animate.min.css',
            array(),
            '3.7.2',
            'all'
        );
        wp_enqueue_style(
            'themify-icons',
            plugins_url( '/', __FILE__ ) . 'assets/css/themify-icons.min.css',
            array(),
            '1.0.0',
            'all'
        );
        wp_enqueue_style(
            'linea',
            plugins_url( '/', __FILE__ ) . 'assets/css/linea.min.css',
            array(),
            '1.0.0',
            'all'
        );
        wp_enqueue_style(
            'hover',
            plugins_url( '/', __FILE__ ) . 'assets/css/hover-min.css',
            array(),
            '2.3.2',
            'all'
        );
        wp_enqueue_style(
            'icofont',
            plugins_url( '/', __FILE__ ) . 'assets/css/icofont.min.css',
            array(),
            '1.0.1',
            'all'
        );
        wp_enqueue_style(
            'magnific-popup',
            plugins_url( '/', __FILE__ ) . 'assets/css/magnific-popup.min.css',
            array(),
            '1.0',
            'all'
        );
        wp_enqueue_style(
            'flickity',
            plugins_url( '/', __FILE__ ) . 'assets/css/flickity.min.css',
            array(),
            '2.2.1',
            'all'
        );
        wp_enqueue_style(
            'owl-carousel',
            plugins_url( '/', __FILE__ ) . 'assets/css/owl.carousel.min.css',
            array(),
            '2.3.4',
            'all'
        );
        wp_enqueue_style(
            'juxtapose',
            plugins_url( '/', __FILE__ ) . 'assets/css/juxtapose.css',
            array(),
            '1.2.1',
            'all'
        );
        wp_enqueue_style(
            'prim-styles',
            plugins_url( '/', __FILE__ ) . 'assets/css/styles.css',
            array(),
            '1.2',
            'all'
        );
        wp_enqueue_style(
            'prim-responsive',
            plugins_url( '/', __FILE__ ) . 'assets/css/responsive.css',
            array(),
            '1.2',
            'all'
        );
        // Scripts
        wp_enqueue_script(
            'waypoints',
            plugins_url( '/', __FILE__ ) . 'assets/js/jquery.waypoints.min.js',
            array( 'jquery' ),
            '2.0.3',
            true
        );
        wp_enqueue_script(
            'imagesloaded',
            plugins_url( '/', __FILE__ ) . 'assets/js/imagesloaded.pkgd.min.js',
            array( 'jquery' ),
            '4.1.4',
            true
        );
        wp_enqueue_script(
            'magnific-popup',
            plugins_url( '/', __FILE__ ) . 'assets/js/jquery.magnific-popup.min.js',
            array( 'jquery' ),
            '1.1.0',
            true
        );
        wp_enqueue_script(
            'juxtapose',
            plugins_url( '/', __FILE__ ) . 'assets/js/juxtapose.js',
            array( 'jquery' ),
            '1.2.1',
            true
        );
        wp_enqueue_script(
            'InstagramFeed',
            plugins_url( '/', __FILE__ ) . 'assets/js/InstagramFeed.min.js',
            array( 'jquery' ),
            '1.3.8',
            true
        );
        wp_enqueue_script(
            'typed',
            plugins_url( '/', __FILE__ ) . 'assets/js/typed.min.js',
            array( 'jquery' ),
            '2.0.11',
            true
        );
        wp_enqueue_script(
            'flickity',
            plugins_url( '/', __FILE__ ) . 'assets/js/flickity.pkgd.min.js',
            array( 'jquery' ),
            '2.2.1',
            true
        );
        wp_enqueue_script(
            'owl-carousel',
            plugins_url( '/', __FILE__ ) . 'assets/js/owl.carousel.min.js',
            array( 'jquery' ),
            '2.3.4',
            true
        );
        wp_enqueue_script(
            'matchheight',
            plugins_url( '/', __FILE__ ) . 'assets/js/jquery.matchHeight.min.js',
            array( 'jquery' ),
            '0.7.2',
            true
        );
        wp_enqueue_script(
            'isotope',
            plugins_url( '/', __FILE__ ) . 'assets/js/isotope.min.js',
            array( 'jquery' ),
            '3.0.6',
            true
        );
        wp_enqueue_script(
            'counterup',
            plugins_url( '/', __FILE__ ) . 'assets/js/jquery.counterup.min.js',
            array( 'jquery' ),
            '1.0',
            true
        );
        wp_enqueue_script(
            'packery-mode',
            plugins_url( '/', __FILE__ ) . 'assets/js/packery-mode.pkgd.min.js',
            array( 'jquery' ),
            '2.1.2',
            true
        );
        wp_enqueue_script(
            'prim-scripts',
            plugins_url( '/', __FILE__ ) . 'assets/js/scripts.js',
            array( 'jquery' ),
            '1.2',
            true
        );
    }
    
    add_action( 'wp_enqueue_scripts', 'prim_scripts_styles' );
}
