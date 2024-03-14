<?php
/**
 * Admin  - enqueue sytle, script
 */


if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HTCC_Enqueue' ) ) :

class HTCC_Enqueue {


    function enqueue( $hook ) {

        // echo $hook;
        if( 'toplevel_page_wp-chatbot' == $hook || 'wp-chatbot_page_wp-chatbot-features' == $hook || 'wp-chatbot_page_wp-chatbot-actions' == $hook || 'wp-chatbot_page_wp-chatbot-pro-woo' == $hook ) {


            // color picker..
            // wp_enqueue_style( 'htcc_admin_color_picker_styles', plugins_url( 'admin/assets/color/colors.css', HTCC_PLUGIN_FILE ), '', HTCC_VERSION );
            // wp_enqueue_script( 'htcc_admin_color_picker_js', plugins_url( 'admin/assets/color/colors.js', HTCC_PLUGIN_FILE ), array( 'jquery', 'wp-color-picker', 'htcc_admin_md_js' ), HTCC_VERSION );

            // spectrum
            wp_enqueue_style( 'htcc_admin_color_picker_styles', plugins_url('admin/assets/css/spectrum-1.8.0.min.css', HTCC_PLUGIN_FILE), '', HTCC_VERSION );
            wp_enqueue_style( 'font_awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '', HTCC_VERSION );
            wp_enqueue_style( 'recurly_css', 'https://js.recurly.com/v4/recurly.css', '', HTCC_VERSION );
            wp_enqueue_style( 'Fredoka One', 'https://fonts.googleapis.com/css?family=Fredoka+One', '', HTCC_VERSION );
            wp_enqueue_style( 'Open Sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800&display=swap', '', HTCC_VERSION );
            wp_enqueue_script( 'htcc_admin_color_picker_js', plugins_url('admin/assets/js/spectrum-1.8.0.min.js', HTCC_PLUGIN_FILE), array( 'jquery' ), HTCC_VERSION, true );



            wp_enqueue_style( 'htcc_admin_styles', plugins_url( 'admin/assets/css/admin-styles.css', HTCC_PLUGIN_FILE ), '', HTCC_VERSION );
            wp_enqueue_style( 'htcc_admin_md_styles', plugins_url( 'admin/assets/css/materialize.min.css', HTCC_PLUGIN_FILE ), '', HTCC_VERSION );

            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker');


            wp_enqueue_script( 'htcc_admin_js', plugins_url( 'admin/assets/js/admin.js', HTCC_PLUGIN_FILE ), array( 'wp-color-picker', 'jquery' ), HTCC_VERSION, true );
			wp_localize_script('htcc_admin_js', 'ajax_obj', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('htcc_nonce')));
            wp_enqueue_script( 'htcc_admin_recurly_js', 'https://js.recurly.com/v4/recurly.js', array( 'wp-color-picker', 'jquery' ), HTCC_VERSION, true );
//            wp_enqueue_script( 'htcc_admin_md_js', plugins_url( 'admin/assets/js/materialize.min.js', HTCC_PLUGIN_FILE ), array('wp-color-picker',  'jquery' ), HTCC_VERSION, true );


        }

    }

}

$htcc_enqueue = new HTCC_Enqueue();

add_action('admin_enqueue_scripts', array( $htcc_enqueue, 'enqueue' ) );


endif; // END class_exists check
