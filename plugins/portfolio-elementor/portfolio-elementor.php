<?php

/*
Plugin Name: Portfolio & Image Gallery for WordPress | PowerFolio
Plugin URI: https://powerfoliowp.com
Description: Create customizable and filterable portfolio and image galleries in seconds using Elementor, Gutenberg blocks, or any page builder!
Author: PWR Plugins
Text Domain: powerfolio
Version: 3.1.1
Author URI: https://pwrplugins.com
*/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
//Freemius

if ( function_exists( 'pe_fs' ) ) {
    pe_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'pe_fs' ) ) {
        // ... Freemius integration snippet ...
        // Create a helper function for easy SDK access.
        function pe_fs()
        {
            global  $pe_fs ;
            
            if ( !isset( $pe_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $pe_fs = fs_dynamic_init( array(
                    'id'             => '7226',
                    'slug'           => 'portfolio-elementor',
                    'premium_slug'   => 'portfolio-elementor-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_75702ac7c5c10d2bfd4880c1c8039',
                    'is_premium'     => false,
                    'premium_suffix' => 'PRO',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'       => 'elementor_portfolio',
                    'first-path' => 'admin.php?page=elementor_portfolio',
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $pe_fs;
        }
        
        // Init Freemius.
        pe_fs();
        // Signal that SDK was initiated.
        do_action( 'pe_fs_loaded' );
    }
    
    // ... Your plugin's main file logic ...
    
    if ( !class_exists( 'Powerfolio_Portfolio' ) ) {
        /*
         * Start Powerfolio
         */
        /*
         * Portfolio
         */
        require 'classes/Powerfolio_Portfolio.php';
        /*
         * Image Gallery
         */
        require 'classes/Powerfolio_Image_Gallery.php';
        /*
        Portfolio Carousel
        */
        require 'classes/Powerfolio_Carousel.php';
        /*
        Powerfolio Post Grid
        */
        require 'classes/Powerfolio_Post_Grid.php';
        /*
        Powerfolio Product Grid
        */
        require 'classes/Powerfolio_Product_Grid.php';
        /*
         * Common Settings for Widgets
         */
        require 'classes/Powerfolio_Common_Settings.php';
        /*
         * Elementor
         */
        require 'elementor/load_elementor.php';
        /*
         * Gutenberg
         */
        require 'classes/Powerfolio_Gutenberg.php';
        /*
         * Shortcode Generator
         */
        require 'classes/Powerfolio_Shortcode_Generator.php';
        /*
         * Plugin Options
         */
        require 'includes/panel.php';
        /*
         * Plugin Functions
         */
        require 'includes/functions.php';
        /*
         * Review
         */
        update_option( "elpt-installDate", date( 'Y-m-d h:i:s' ) );
        if ( is_admin() ) {
            require_once 'classes/Powerfolio_Feedback_Notice.php';
        }
    }
    
    //Elementor Category
    
    if ( !function_exists( 'elpug_powerups_cat' ) ) {
        //Create Elementor Category
        function elpug_powerups_cat()
        {
            \Elementor\Plugin::$instance->elements_manager->add_category( 'elpug-elements', [
                'title' => __( 'Powerfolio / Power-Ups for Elementor', 'elpug' ),
                'icon'  => 'fa fa-plug',
            ], 2 );
        }
        
        add_action( 'elementor/init', 'elpug_powerups_cat' );
    }
    
    //Post Grids Module
    if ( !class_exists( 'PWGD_Register_PwrGrids_Elementor' ) ) {
        //require 'modules/post-grid-module/post-grid-module.php';
    }
    register_activation_hook( __FILE__, 'elpt_activate' );
}

// Enqueue general scripts
/*if (! function_exists('powerfolio_enqueue_scripts')) {   
    function powerfolio_enqueue_scripts() {
        //wp_enqueue_script( 'powerfolio-bundle-js', plugin_dir_url( __FILE__ ) . 'dist/bundle.js', array(), '1.0', true );
    }
    add_action( 'wp_enqueue_scripts', 'powerfolio_enqueue_scripts' );
}*/
//Workaround for Packery mode in some themes

if ( !function_exists( 'elpt_fix_packery_layout_themes' ) ) {
    function elpt_fix_packery_layout_themes()
    {
        wp_enqueue_script(
            'jquery-packery2',
            plugin_dir_url( __FILE__ ) . 'vendor/isotope/js/packery-mode.pkgd.min.js',
            array( 'jquery', 'imagesloaded' ),
            '3.0.6',
            true
        );
    }
    
    $current_theme = wp_get_theme();
    if ( $current_theme == 'Betheme' || $current_theme == 'OceanWP' ) {
        add_action( 'wp_enqueue_scripts', 'elpt_fix_packery_layout_themes', 99999 );
    }
}

//load textdomain

if ( !function_exists( 'powerfolio_load_textdomain' ) ) {
    function powerfolio_load_textdomain()
    {
        load_plugin_textdomain( 'powerfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }
    
    add_action( 'plugins_loaded', 'powerfolio_load_textdomain' );
}
