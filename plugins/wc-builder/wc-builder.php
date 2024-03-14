<?php
/**
 * Plugin Name: WC Builder - WooCommerce Page Builder for WPBakery
 * Description: The WooCommerce elements library for WPBakery page builder plugin for WordPress.
 * Plugin URI: 	http://hasthemes.com
 * Version: 	1.0.19
 * Author: 		HasThemes
 * Author URI: 	http://hasthemes.com
 * License:  	GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wpbforwpbakery
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WPBFORWPBAKERY_VERSION', '1.0.19' );
define( 'WPBFORWPBAKERY_ADDONS_PL_URL', plugins_url( '/', __FILE__ ) );
define( 'WPBFORWPBAKERY_ADDONS_PL_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPBFORWPBAKERY_ADDONS_PL_ROOT', __FILE__ );

// Deactivate the pro plugin
register_activation_hook( WPBFORWPBAKERY_ADDONS_PL_ROOT, 'wpbforwpbakery_activation_hook' );
function wpbforwpbakery_activation_hook(){
    if ( ! function_exists('is_plugin_active') ){ 
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    if( is_plugin_active('wc-builder-pro/wc-builder-pro.php') ){
        add_action('update_option_active_plugins', function(){
            deactivate_plugins('wc-builder-pro/wc-builder-pro.php');
        });
    }
}

// Required File
require_once WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/helper-functions.php';
require_once WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/activation-notice.php';
require_once WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/custom-posts.php';
require_once WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/admin/admin-init.php';
require_once WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/metaboxes.php';

if(is_admin()){
    require_once WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/admin/recommended-plugins/class.recommended-plugins.php';
    require_once WPBFORWPBAKERY_ADDONS_PL_PATH.'includes/admin/recommended-plugins/recommendations.php';
}

if( wpbforwpbakery_get_option('ajaxcart_singleproduct', 'wpbforwpbakery_other_tabs') === 'on' ){
    require_once WPBFORWPBAKERY_ADDONS_PL_PATH . 'includes/single-add-to-cart-ajax.php';
}

add_action('plugins_loaded', 'wpbforwpbakery_initialize_plugin');
function wpbforwpbakery_initialize_plugin(){
	require_once WPBFORWPBAKERY_ADDONS_PL_PATH.'init.php';
}

// enqueue scripts
add_action( 'wp_enqueue_scripts','wpbforwpbakery_enqueue_scripts');
function  wpbforwpbakery_enqueue_scripts(){
    // enqueue styles
    wp_enqueue_style( 'wpbforwpbakery-main', WPBFORWPBAKERY_ADDONS_PL_URL.'/assets/css/main.css');

   // dynamic style
   $site_width = wpbforwpbakery_get_option( 'content_width', 'wpbforwpbakery_woo_template_tabs', '1170' );
   $data = '';
   if($site_width){
   	   $data = "
  			.wpbforwpbakery_archive .vc_row.wpb_row.vc_row-fluid,
  			.wpbforwpbakery-single-product .vc_row.wpb_row.vc_row-fluid,
  			.wpbforwpbakery-page-template .vc_row.wpb_row.vc_row-fluid{
  				max-width: $site_width;
  				margin: 0 auto;
  			}
  			.wpbforwpbakery_archive .vc_row.wpb_row.vc_row-fluid[data-vc-full-width='true'],
  			.wpbforwpbakery-single-product .vc_row.wpb_row.vc_row-fluid[data-vc-full-width='true'],
  			.wpbforwpbakery-page-template .vc_row.wpb_row.vc_row-fluid[data-vc-full-width='true']{
				max-width:100%;
  			}
   	   ";
   }
   wp_add_inline_style( 'wpbforwpbakery-main', $data );
}


//Register a custom menu page.
add_action( 'admin_menu', 'wpbforwpbakery_register_menu_page' );
function wpbforwpbakery_register_menu_page() {
    add_menu_page(
        __( 'WC Page Builder', 'wpbforwpbakery' ),
        'WC Page Builder',
        'manage_options',
        'wpbforwpbakery_options',
        '',
        WPBFORWPBAKERY_ADDONS_PL_URL.'includes/admin/assets/images/menu-icon.png'
    );
}

// set vc editor to post type
add_action('plugins_loaded', 'wpbforwpbakery_fire_vc_before_init');
function wpbforwpbakery_fire_vc_before_init(){
	add_action('vc_before_init', 'wpbforwpbakery_set_post_type_to_vc_editor');
}

function wpbforwpbakery_set_post_type_to_vc_editor(){
	if (function_exists('vc_set_default_editor_post_types')) {
		vc_set_default_editor_post_types(array('page', 'post', 'wpbfwpb_template'));
	}
}

// Bind admin page link to the plugin action link.
add_filter( 'plugin_action_links_wc-builder/wc-builder.php', 'wpbforwpbakery_action_links_add', 10, 4 );
function wpbforwpbakery_action_links_add( $actions, $plugin_file, $plugin_data, $context ){
    $settings_page_link = sprintf(
        /*
         * translators:
         * 1: Settings label
         */
        '<a href="'. esc_url( get_admin_url() . 'edit.php?post_type=wpbfwpb_template' ) .'">%1$s</a>',
        esc_html__( 'Settings', 'wpbforwpbakery' )
    );

    array_unshift( $actions, $settings_page_link );

    return $actions;
}
