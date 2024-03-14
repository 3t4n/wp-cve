<?php
/*
  Plugin Name: TR Post Slider Widget
  Plugin URI: http://tanzilur.com
  Description: Using this free post slider widget plugin you can show your posts in a slideshow.
  Version: 3.2
  Author: Mohammad Tanzilur Rahman
  Author URI: http://tanzilur.com
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'TR_PLUGIN_DIR' ) )
    define( 'TR_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

function tr_get_version(){
	if (!function_exists( 'get_plugins' ) )
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}

function tr_post_slider_includes(){
    wp_register_script( 'tr-ps-js', plugins_url( '/js/owl.carousel.min.js', __FILE__ ), array( 'jquery' ), '1.3.2' );
    wp_enqueue_script( 'tr-ps-js' );
    wp_register_script( 'tr-cycle-js', plugins_url( '/js/cycle.js', __FILE__ ), array( 'jquery' ), '3.0.3' );
    wp_enqueue_script( 'tr-cycle-js' );
    
    //wp_enqueue_style( 'tr-ps-css',  get_bloginfo('wpurl').'/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/tr.post.slider.frontend.css');
    wp_enqueue_style( 'owl-carousel-css', plugins_url( '/css/owl.carousel.css', __FILE__ ));
    wp_enqueue_style( 'tr-ps-css', plugins_url( '/css/tr-ps-frontend.css', __FILE__ ));
}
add_action('wp_enqueue_scripts', 'tr_post_slider_includes');


require_once TR_PLUGIN_DIR . '/widget-post-content.php';
require_once TR_PLUGIN_DIR . '/widget-post-with-thumbnail.php';
require_once TR_PLUGIN_DIR . '/widget-post-featured-image.php';