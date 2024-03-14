<?php
/**
 * Plugin Name: Easy Popup Announcement
 * Plugin URI: http://pupungbp.com/plugins/easy-popup-announcement
 * Description: Add simple popup to entire pages or certain page of the website. It has session control, random content, mobile option, and responsive layout support.
 * Version: 1.0.5
 * Author: Pupung Budi Purnama
 * Author URI: http://pupungbp.com
 * License: GPL2
 */

/*
 * Include Languange Text Domain
 */
function epa_add_lang() {
	load_plugin_textdomain( 'easy-popup-announcement', false, '/easy-popup-announcement/lang' );
}
add_action( 'init', 'epa_add_lang' );

/* 
 * Start Adding Scripts
 */
function epa_load_required_js() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'epa_popupoverlay', plugins_url( "/js/jquery.popupoverlay.js", __FILE__ ), 'jquery', '1.6.5', false );
	wp_enqueue_script( 'epa_jquery_cookies', plugins_url( "/js/jquery.cookie.js", __FILE__ ), 'jquery', '1.6.5', false );
	wp_enqueue_style( 'epa_styling', plugins_url( "/assets/css/epa_styling.css", __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'epa_load_required_js' );

function epa_load_admin_script() {
	wp_enqueue_style( 'epa_admin_styling', plugins_url( "/assets/css/epa_admin_styling.css", __FILE__ ) );
}
add_action ('admin_enqueue_scripts', 'epa_load_admin_script');

/*
 * Insert Default Popup
 */
function epa_html_include() {

	if(get_option('epa_default_id') == 'latest'):
	$latest_popup = wp_get_recent_posts(array('numberposts' => '1', 'post_type' => 'epapopup'));
	$popup_content = $latest_popup['0']['post_content'];

	elseif(get_option('epa_default_id') == 'random'):
	$random_popup = wp_get_recent_posts(array('numberposts' => '1', 'post_type' => 'epapopup', 'orderby' => 'rand'));
	$popup_content = $random_popup['0']['post_content'];
	
	else:
	$default_popup = get_post(get_option('epa_default_id'));
	$popup_content = $default_popup->post_content;
	endif;
	
	
	//$epa_html = '<button class="my_popup_open">Open popup</button>';
	$epa_html .= '<div id="my_popup" class="well"><span class="my_popup_close"></span>';
	//$epa_html .= $default_popup->post_content;
	$epa_html .= apply_filters( 'the_content', $popup_content );
	$epa_html .= '</div>';

	if(!isset($_COOKIE['epa_popup'])) {

		echo $epa_html;
		//echo $latest_popup['0']['post_content'];
	}

}
function epa_default_popup() {
	if(get_option('epa_enable') == 'yes' && get_option('epa_default_id') !== '') epa_html_include();
}
add_action( 'wp_footer', 'epa_default_popup' );

/*
 * Generate JQuery Code
 */
function epa_js_overlay_include() {
	$epa_js = '<script>jQuery(document).ready(function($) {  setTimeout( function(){ $(\'#my_popup\').popup({autoopen: true, transition: \'all 0.3s\', blur: true, color: \''.get_option('epa_bgcolor').'\'}); }, '.get_option('epa_popup_delay').'); }); </script>';

	echo $epa_js;
}
add_action( 'wp_footer', 'epa_js_overlay_include' );


/**
 * Add Custom Post Type For EPA
 */
function epa_add_popup_cpt() {
	$labels = array(
	    'name'               => __( 'Popup','easy-popup-announcement' ),
	    'singular_name'      => __( 'Popup','easy-popup-announcement' ),
	    'add_new'            => __( 'Add New','easy-popup-announcement' ),
	    'add_new_item'       => __( 'Add New Popup','easy-popup-announcement' ),
	    'edit_item'          => __( 'Edit Popup','easy-popup-announcement' ),
	    'new_item'           => __( 'New Popup','easy-popup-announcement' ),
	    'all_items'          => __( 'All Popups','easy-popup-announcement' ),
	    'view_item'          => __( 'View Popup','easy-popup-announcement' ),
	    'search_items'       => __( 'Search Popup','easy-popup-announcement' ),
	    'not_found'          => __( 'No Popup found','easy-popup-announcement' ),
	    'not_found_in_trash' => __( 'No Popup found in Trash','easy-popup-announcement' ),
	    'menu_name'          => __( 'Easy Popup','easy-popup-announcement' )
  	);

  	$args = array(
	    'labels'             => $labels,
	    'public'             => true,
	    'publicly_queryable' => true,
	    'show_ui'            => true,
	    'show_in_menu'       => true,
	    'query_var'          => true,
	    'rewrite'            => array( 'slug' => 'epapopup' ),
	    'capability_type'    => 'page',
	    'has_archive'        => true,
	    'menu_icon' 		 => 'dashicons-megaphone',
	    'hierarchical'       => false,
	    'menu_position'      => null,
	    'show_in_rest' 		 => true,
	    'supports'           => array( 'title', 'editor' )
  	);

  	register_post_type( 'epapopup', $args );
}
add_action( 'init', 'epa_add_popup_cpt' );

/*
 * Include popup options
 */
include_once('inc/options.php');

function epa_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-alpha', plugins_url('js/wp-color-picker-alpha.min.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'epa_enqueue_color_picker' );

/*
 * Include Shortcode
 */
include_once('inc/shortcode.php');

/*
 * Set Cookies
 */
function epa_setcookie() {
		$epa_expire = get_option( 'epa_expire' );
		$expire = time() + (float) $epa_expire;
		if(get_option('epa_enable') == 'yes') {
			if(isset($_COOKIE['epa_popup'])):
			else:
				//$_COOKIE['epa_popup'] = 'active';
				setcookie('epa_popup', 'active', $expire);
			endif;
		}
}
add_action( 'init', 'epa_setcookie' );

/*
 * Get EPA CPT ID
 */
function get_epa_cpt_id() {
	$args = array(
		'post_type' => 'epapopup',
		'orderby' => 'date'
	);
	$get_epa_cpt = get_posts($args);

	//Print Dropdown
	echo '<select name="epa_default_id">';
	echo '<option value="latest" '.selected( get_option('epa_default_id'), 'latest').' >Latest Popup</option>';
	echo '<option value="random" '.selected( get_option('epa_default_id'), 'random').' >Randomize</option>';
	if($get_epa_cpt !== ''):
	foreach ($get_epa_cpt as $get_epa_cpt_id) {
		echo '<option value='.$get_epa_cpt_id->ID.''.selected( get_option('epa_default_id'), $get_epa_cpt_id->ID).' > '.$get_epa_cpt_id->ID.' - '.$get_epa_cpt_id->post_title.'</option>';		
	} else :
	endif;
	echo '</select>';
}

/*
 * Single Popup Shortcode Metabox
 */
function epa_shortcode_meta() {
	echo '<strong style="font-size: 1.2em;">[epapop id=\''.get_the_id().'\']</strong>';
	echo '<br /><cite>Copy and paste the code above to your post / page to initialize the popup</cite>';
}
function epa_shortcode_init() {
	add_meta_box( 'epashort', 'Shortcode', 'epa_shortcode_meta', 'epapopup', 'side' );
}
add_action( 'add_meta_boxes', 'epa_shortcode_init' );

/*
 * Insert Overide Styling
 */
function epa_overide_style() {
if(get_option('epa_popup_padding')):

	echo '<style type="text/css">';
	echo '.well {padding: '. get_option('epa_popup_padding') .'px !important}';
	echo '</style>';

endif;
}
add_action('wp_head', 'epa_overide_style');
