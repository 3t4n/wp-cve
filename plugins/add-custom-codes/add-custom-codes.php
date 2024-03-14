<?php
/**
 * Plugin Name: Add Custom Codes - Insert Header, Footer, Custom Code Snippets
 * Description: Light-weight plugin to add Custom CSS, Javascript, Google Analytics, Search console verification tags and other custom code snippets to your Wordpress website. Go to <em>Appearance -> Add Custom Codes</em> after installing the plugin.
 * Version: 4.6
 * Author: Saifudheen Mak
 * Author URI: https://maktalseo.com
 * License: GPL2
 * Text Domain: add-custom-codes
 */
 
// If this file was called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 add_action( 'init', 'accodes_load_textdomain' );
function accodes_load_textdomain() {
  load_plugin_textdomain( 'add-custom-codes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

/*----------------
plugin links 'Plugins' page
------------------*/
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links_accodes' );
 
function add_action_links_accodes ( $actions ) {
   $mylinks = array(
      '<a href="' . admin_url( 'themes.php?page=add-custom-codes' ) . '">Settings</a>',
   );
   $actions = array_merge( $actions, $mylinks );
   return $actions;
}

add_filter( 'plugin_row_meta', 'plugin_row_meta_accodes', 10, 2 );

function plugin_row_meta_accodes( $links, $file ) {    
    if ( plugin_basename( __FILE__ ) == $file ) {
        $row_meta = array(		
          'wrt-review'    => '<a href="' . esc_url( 'https://wordpress.org/support/plugin/add-custom-codes/reviews/#new-post' ) . '" target="_blank" style="">' . esc_html__( 'Rate this plugin', 'add-custom-codes' ) . '</a>',	
			 'acc-buy-coffee'    => '<a href="' . esc_url( 'https://maktalseo.com' ) . '" target="_blank" style="color:green;">' . esc_html__( 'Hire us!', 'add-custom-codes' ) . '</a>'
			
			
        );

        return array_merge( $links, $row_meta );
    }
    return (array) $links;
}

/*---------------------------------
styles and scripts for plugin page
-----------------------------------------*/

add_action('admin_enqueue_scripts', 'codemirror_enqueue_scripts');
 
function codemirror_enqueue_scripts($hook) {
	$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'htmlmixed'));
	wp_localize_script('jquery', 'cm_settings', $cm_settings);
	
	wp_enqueue_style('wp-codemirror');
	
	wp_register_style( 'accodes-css', plugins_url( 'add-custom-codes/css/style41.css' ), '', '4.192' );
		wp_enqueue_style( 'accodes-css' );
	
	wp_register_script( 'accodes-js', plugins_url( 'add-custom-codes/js/script.js' ), '', '4.1' );
		wp_enqueue_script( 'accodes-js' );
	
}

/*------------------------------
add menu link for plugin settings page
------------------------*/
add_action('admin_menu', 'accodes_menu');

function accodes_menu() {
	add_theme_page('Add Custom Codes', 'Add Custom Codes', 'administrator', 'add-custom-codes', 'accodes_settings_page');
}

function accodes_settings_page() {
	include('global-form.php');
}

/*---------------------------
define items in settings page
-------------------------------*/
add_action( 'admin_init', 'accodes_settings' );

function accodes_settings() {
	register_setting( 'accodes-settings-group', 'custom-css-codes-input' );
	register_setting( 'accodes-settings-group', 'custom-footer-codes-input' );
	register_setting( 'accodes-settings-group', 'custom-header-codes-input' );
	register_setting( 'accodes-settings-group', 'accodes_global_css_on_footer' );
}


/*----------------
 functions
---------------*/
function get_current_page_id()
{
	global $post;
	$current_page_id = false;
	if ( ! isset( $post ) ) {
		return false;
	}
	// if woocommerce product, get id
	if ( class_exists( 'WooCommerce' ) && is_shop() ) {
		$current_page_id = wc_get_page_id( 'shop' );
	} 
	else {
		// get page id of individual only
		if ( is_singular() ) {
			$current_page_id = $post->ID;
		}
	}
	return $current_page_id;
}

/*---------------------------------
output Global CSS
----------------*/
function get_global_custom_css()
{
		$accodes_global_css = '';
		$options  = get_option( 'custom-css-codes-input' );
		if($options!='')
		{
			$accodes_global_css = '<!-- Global CSS by Add Custom Codes -->'. PHP_EOL;
			$accodes_global_css .= '<style type="text/css">';
			$accodes_global_css .= ''. PHP_EOL;
			$accodes_global_css .= $options;
			$accodes_global_css .= ''. PHP_EOL.'</style>'.PHP_EOL;
			$accodes_global_css .= '<!-- End - Global CSS by Add Custom Codes -->';
		} 
	return $accodes_global_css;
}


add_action( 'wp_head', 'accodes_css_output_header' );
function accodes_css_output_header() {
	//check if global css to be added before footer
	$css_on_footer = esc_attr( get_option('accodes_global_css_on_footer') ); 
	//put css in footer not ticked
	if($css_on_footer != 'on')
	{
		$accodes_global_css = get_global_custom_css();
		echo $accodes_global_css;
	}
}

add_action( 'wp_footer', 'accodes_css_output_footer' );
function accodes_css_output_footer() {
	//check if global css to be added before footer
	$css_on_footer = esc_attr( get_option('accodes_global_css_on_footer') ); 
	//put css in footer ticked
	if($css_on_footer == 'on')
	{
		$accodes_global_css = get_global_custom_css();
		echo $accodes_global_css;
	}
}
/*---------------------------------
output Global Header Codes
----------------*/
add_action( 'wp_head', 'accodes_header_output' );
function accodes_header_output() {
	
	$hide_global_header = '';
	$current_page_id = get_current_page_id();
	$global_header_codes ='';
	$single_header_codes = '';
	$output = '';
	
	if ( $current_page_id ) {
		//value is 'on' if checked
		$hide_global_header = get_post_meta( $current_page_id, 'accodes_hide_header', true );
		//get Single Header Codes
		$get_single_header_codes = get_post_meta( $current_page_id , '_accodes_header_metabox', true );
		if($get_single_header_codes !='')
		{
			$single_header_codes = PHP_EOL.'<!-- Single header Scripts by Add Custom Codes -->'. PHP_EOL;
			$single_header_codes .= $get_single_header_codes;
			$single_header_codes .= PHP_EOL.'<!-- End of Single header Scripts by Add Custom Codes -->'. PHP_EOL;
		} 
	}
	
	//get global header - if not set to hide
	if($hide_global_header != 'on')
	{
		//get Global Header Codes
		$get_global_header_codes  = get_option( 'custom-header-codes-input' );
		if($get_global_header_codes!='')
		{
			$global_header_codes = PHP_EOL.'<!-- Global Header Scripts by Add Custom Codes -->'. PHP_EOL;
			$global_header_codes .= $get_global_header_codes;
			$global_header_codes .= PHP_EOL.'<!-- End - Global Header Scripts by Add Custom Codes -->'. PHP_EOL;
		}  
	}
	$output .= $global_header_codes;
	$output .= $single_header_codes;
	
	if($output !='')
	{
		echo $output;
	}
	
}



/*---------------------------------
output Global Footer Codes
----------------*/
add_action( 'wp_footer', 'accodes_footer_output' );
function accodes_footer_output() {
	
	$hide_global_footer = '';
	$current_page_id = get_current_page_id();
	$global_footer_codes ='';
	$single_footer_codes = '';
	$output = '';
	
	if ( $current_page_id ) {
		//value is 'on' if checked
		$hide_global_footer = get_post_meta( $current_page_id, 'accodes_hide_footer', true );
		//get Footer Codes for Single
		$get_single_footer_codes = get_post_meta( $current_page_id , '_accodes_footer_metabox', true );
		if($get_single_footer_codes !='')
		{
			$single_footer_codes = PHP_EOL.'<!-- Single Footer Scripts by Add Custom Codes -->'. PHP_EOL;
			$single_footer_codes .= $get_single_footer_codes;
			$single_footer_codes .= PHP_EOL.'<!-- End - Single Footer Scripts by Add Custom Codes -->'. PHP_EOL;
		} 
	}
	
	//get global footer - if not set to hide
	if($hide_global_footer != 'on')
	{
		//get Global Footer codes
		$get_global_footer_codes  = get_option( 'custom-footer-codes-input' );
		if($get_global_footer_codes!='')
		{
			$global_footer_codes = PHP_EOL.'<!-- Global Footer Scripts by Add Custom Codes -->'. PHP_EOL;
			$global_footer_codes .= $get_global_footer_codes;
			$global_footer_codes .= PHP_EOL.'<!-- End - Global Footer Scripts by Add Custom Codes -->'. PHP_EOL;
		}  
	}
	$output .= $global_footer_codes;
	$output .= $single_footer_codes;
	
	if($output !='')
	{
		echo $output;
	}
	
}



/*----------------
 * Individual pages
 * -----------------------*/

/**
 * Create the meta boxes for Single post, page, product any other custom post type
 */
function _accodes_create_metabox_single() {
	
	$post_types = get_post_types( '', 'names' );
	$post_types = array_merge( $post_types, array( 'post', 'page' ) );

	foreach ( $post_types as $post_type ) {
		add_meta_box( '_accodes_metabox', 'Add Custom Codes by Mak', '_accodes_render_metabox', $post_type, 
		'normal', 'default');
	}

}
add_action( 'add_meta_boxes', '_accodes_create_metabox_single' );




/*-------------------------------
 * Display Meta Boxes for Single
 * ----------------------------*/
function _accodes_render_metabox() {
	// Variables
	global $post; // Get the current post data
	$header_script = get_post_meta( $post->ID, '_accodes_header_metabox', true ); // Get the saved values
	$footer_script = get_post_meta( $post->ID, '_accodes_footer_metabox', true ); // Get the saved values
	
	$hide_header  = get_post_meta( $post->ID, 'accodes_hide_header', true );
	$hide_footer  = get_post_meta( $post->ID, 'accodes_hide_footer', true );
	
	include('single-meta.php');
	
	/* check if the submission come from actual server dashboard */
	wp_nonce_field( '_accodes_form_metabox_nonce', '_accodes_form_metabox_process' );
	
}


/*-------------------------
 * update data on post save - Single
 ---------------------------*/
function _accodes_save_metabox_single( $post_id, $post ) {

	// Verify data came from edit/dashboard screen
	if ( !wp_verify_nonce( $_POST['_accodes_form_metabox_process'], '_accodes_form_metabox_nonce' ) ) {
		return $post->ID;
	}

	// Verify user has permission to edit post
	if ( !current_user_can( 'edit_post', $post->ID )) {
		return $post->ID;
	}

	//Get values of meta boxes
	$accodes_header_metabox = isset( $_POST['_accodes_header_metabox'] ) ? $_POST['_accodes_header_metabox'] : '';
	$accodes_footer_metabox = isset( $_POST['_accodes_footer_metabox'] ) ? $_POST['_accodes_footer_metabox'] : '';
	
	// Get values of checkbox
	$hide_header   = isset( $_POST['accodes_hide_header'] ) ? $_POST['accodes_hide_header'] : '';
	$hide_footer   = isset( $_POST['accodes_hide_footer'] ) ? $_POST['accodes_hide_footer'] : '';
	
	//Update values of meta boxes
	update_post_meta( $post->ID, '_accodes_header_metabox', $accodes_header_metabox );
	update_post_meta( $post->ID, '_accodes_footer_metabox', $accodes_footer_metabox );
	
	//Update values of check boxes
	update_post_meta( $post->ID, 'accodes_hide_header', $hide_header );
	update_post_meta( $post->ID, 'accodes_hide_footer', $hide_footer );

}
add_action( 'save_post', '_accodes_save_metabox_single', 1, 2 );
?>