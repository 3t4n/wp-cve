<?php

/*
 * Plugin Name: PDF Poster
 * Plugin URI:  https://bplugins.com/pdf-poster-pro-demo/
 * Description: You can easily embed/ show pdf file in your wordress website using this plugin.
 * Version:     2.1.20
 * Author:      bPlugins
 * Author URI:  https://hayat.im
 * License:     GPLv3
 * Text Domain: pdfp
 */
use  PDFPro\Model\Import ;

if ( function_exists( 'pdfp_fs' ) ) {
    pdfp_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'pdfp_fs' ) ) {
        // Create a helper function for easy SDK access.
        function pdfp_fs()
        {
            global  $pdfp_fs ;
            
            if ( !isset( $pdfp_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $pdfp_fs = fs_dynamic_init( array(
                    'id'              => '14261',
                    'slug'            => 'pdf-poster',
                    'premium_slug'    => 'pdf-poster-pro',
                    'type'            => 'plugin',
                    'public_key'      => 'pk_6e833032174d131283193892a44a2',
                    'is_premium'      => false,
                    'premium_suffix'  => 'Pro',
                    'has_addons'      => false,
                    'has_paid_plans'  => true,
                    'has_affiliation' => 'all',
                    'menu'            => array(
                    'slug'    => 'edit.php?post_type=pdfposter',
                    'support' => false,
                ),
                    'is_live'         => true,
                ) );
            }
            
            return $pdfp_fs;
        }
        
        // Init Freemius.
        pdfp_fs();
        // Signal that SDK was initiated.
        do_action( 'pdfp_fs_loaded' );
    }
    
    /*Some Set-up*/
    define( 'PDFPRO_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
    define( 'PDFPRO_PATH', plugin_dir_path( __FILE__ ) );
    define( 'PDFPRO_VER', '2.1.20' );
    define( 'PDFPRO_IMPORT_VER', '1.0.0' );
    if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
        require_once dirname( __FILE__ ) . '/vendor/autoload.php';
    }
    add_action( 'plugins_loaded', function () {
        if ( class_exists( 'PDFPro\\Init' ) ) {
            PDFPro\Init::register_services();
        }
    } );
    register_activation_hook( __FILE__, function () {
        if ( !function_exists( 'deactivate_plugins' ) ) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }
        deactivate_plugins( 'pdf-poster/pdf-poster.php' );
    } );
    require_once __DIR__ . '/upgrade.php';
    function get_p_option( $array, $key = array(), $default = null )
    {
        if ( is_array( $array ) && array_key_exists( $key, $array ) ) {
            return $array[$key];
        }
        return $default;
    }
    
    add_action( 'media_buttons', 'pdfp_my_media_button', 3 );
    function pdfp_my_media_button()
    {
        echo  '<a href="#" id="insert-pdf" class="button">
        <img src="' . PDFPRO_PLUGIN_DIR . '/img/icn.png' . '" alt="" width="20" height="20" style="position:relative; top:-1px">
        Add PDF</a>' ;
    }
    
    add_action( 'admin_init', 'pdfp_admin_init' );
    function pdfp_admin_init()
    {
        
        if ( get_option( 'pdfp_import', '0' ) != PDFPRO_IMPORT_VER ) {
            Import::meta();
            Import::settings();
            update_option( 'pdfp_import', PDFPRO_IMPORT_VER );
        }
    
    }
    
    add_action( 'wp_head', function () {
        $option = get_option( 'fpdf_option' );
        ?>
        <style>
            <?php 
        echo  esc_html( $option['custom_css'] ?? '' ) ;
        ?>
        </style>
        <?php 
    } );
}
