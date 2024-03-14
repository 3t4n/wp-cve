<?php
/*
Plugin Name: Unlimited Background Slider 
Plugin URI: https://edatastyle.com/product/unlimited-background-slider/
Description: You can create unlimited numbers of slider and assign them to specific posts and pages.Responsive Full Width Background Slider Plugin for full screen slide show in background of your WordPress site. 
Version:1.1.3
Author: eDataStyle
Author URI: https://edatastyle.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'ED_BG_SLIDE_PATH' )    or  define( 'ED_BG_SLIDE_PATH',    plugin_dir_path( __FILE__ ) );
defined( 'ED_BG_SLIDE_URL' )    or  define( 'ED_BG_SLIDE_URL',    plugin_dir_url( __FILE__ ) );

load_plugin_textdomain( 'ed_ubs', false, plugin_dir_path(__FILE__) . 'languages/' ); 


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-background-slider-master-activator.php
 */
function activate_ed_background_slider() {
	require_once plugin_dir_path( __FILE__ ) . '/inc/unlimited-background-slider-activator.php';
	Unlimited_Background_Slider_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-background-slider-master-deactivator.php
 */
function deactivate_ed_background_slider() {
	require_once plugin_dir_path( __FILE__ ) . '/inc/unlimited-background-slider-deactivator.php';
	Unlimited_Background_Slider_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ed_background_slider' );
register_deactivation_hook( __FILE__, 'deactivate_ed_background_slider' );

if ( file_exists( ED_BG_SLIDE_PATH . '/inc/ed_custom_post_type.php' )) {
	require_once ED_BG_SLIDE_PATH . '/inc/ed_custom_post_type.php';
}
if ( file_exists( ED_BG_SLIDE_PATH . '/inc/gallery/gallery.php' )) {
	require_once ED_BG_SLIDE_PATH . '/inc/gallery/gallery.php';
}

if ( file_exists( ED_BG_SLIDE_PATH . '/inc/side-meta.php' )) {
	require_once ED_BG_SLIDE_PATH. '/inc/side-meta.php';
}
if ( file_exists( ED_BG_SLIDE_PATH . '/inc/metabox.php' )) {
	require_once ED_BG_SLIDE_PATH. '/inc/metabox.php';
}

if ( file_exists( ED_BG_SLIDE_PATH . '/inc/view.php' )) {
	require_once ED_BG_SLIDE_PATH . '/inc/view.php';
	new ED_BG_SLIDER();
} 





add_image_size( 'ed-bg-thumbnails', 50, 50,true );


if( !function_exists('ed_team_css') ){
	add_action('admin_head','ed_team_css',0);
	function ed_team_css(){
		echo '<style type="text/css">
			#menu-posts-ed_bg_slider .dashicons-admin-post::before,#menu-posts-ed_bg_slider .dashicons-format-standard::before{
			content:""!important;
			background:url('.ED_BG_SLIDE_URL.'/assets/logo.svg) no-repeat center center;	
			}
		</style>';	
	}
}


function ed_bg_admin_enqueue_scripts() {
	// admin utilities
	wp_enqueue_media();
	 // wp core styles
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( 'wp-jquery-ui-dialog' );;

	 // wp core scripts
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'jquery-ui-accordion' );
 	wp_enqueue_style( 'ed_bg_slider', plugins_url('assets/admin_ed_bg_style.css', __FILE__));
		
}
add_action( 'admin_enqueue_scripts', 'ed_bg_admin_enqueue_scripts' );


