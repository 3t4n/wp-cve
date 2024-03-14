<?php
/*
 * Plugin Name: VS Contact Form
 * Description: With this lightweight plugin you can create a contact form.
 * Version: 15.1
 * Author: Guido
 * Author URI: https://www.guido.site
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Text Domain: very-simple-contact-form
 */

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// enqueue plugin scripts
function vscf_scripts() {
	wp_enqueue_style('vscf-style', plugins_url('/css/vscf-style.min.css',__FILE__));
	$anchor = get_option('vscf-setting-21');
	if ($anchor == 'yes') {
		wp_enqueue_script('vscf-anchor-script', plugins_url( '/js/vscf-anchor.js' , __FILE__ ), array(), false, true);
	}
	wp_enqueue_script('vscf-reset-script', plugins_url( '/js/vscf-reset.js' , __FILE__ ), array(), false, true);
}
add_action( 'wp_enqueue_scripts', 'vscf_scripts' );

// add new setting and remove old one
function vscf_update_settings() {
	$old_setting = get_option( 'vscf-setting' );
	if ($old_setting) {
		add_option( 'vscf-setting-1', $old_setting );
		delete_option( 'vscf-setting' );
	}
}
add_action( 'init', 'vscf_update_settings' );

// the sidebar widget
function vscf_register_widget() {
	register_widget( 'vscf_widget' );
}
add_action( 'widgets_init', 'vscf_register_widget' );

// form submissions
$list_submissions = get_option('vscf-setting-2');
if ($list_submissions == 'yes') {
	// create submission post type
	function vscf_custom_post_type() {
		$vscf_args = array(
			'labels' => array('name' => __( 'Submissions', 'very-simple-contact-form' )),
			'menu_icon' => 'dashicons-email',
			'public' => false,
			'can_export' => true,
			'show_in_nav_menus' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'capability_type' => 'post',
			'capabilities' => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap' => true,
 			'supports' => array( 'title', 'editor' )
		);
		register_post_type( 'submission', $vscf_args );
	}
	add_action( 'init', 'vscf_custom_post_type' );

	// dashboard submission columns
	function vscf_custom_columns( $columns ) {
		$columns['name_column'] = __( 'Name', 'very-simple-contact-form' );
		$columns['email_column'] = __( 'Email', 'very-simple-contact-form' );
		$custom_order = array('cb', 'title', 'name_column', 'email_column', 'date');
		foreach ($custom_order as $colname) {
			$new[$colname] = $columns[$colname];
		}
		return $new;
	}
	add_filter( 'manage_submission_posts_columns', 'vscf_custom_columns', 10 );

	function vscf_custom_columns_content( $column_name, $post_id ) {
		if ( 'name_column' == $column_name ) {
			$name = get_post_meta( $post_id, 'name_sub', true );
			echo esc_attr($name);
		}
		if ( 'email_column' == $column_name ) {
			$email = get_post_meta( $post_id, 'email_sub', true );
			echo esc_attr($email);
		}
	}
	add_action( 'manage_submission_posts_custom_column', 'vscf_custom_columns_content', 10, 2 );

	// make name and email column sortable
	function vscf_make_columns_sortable( $columns ) {
		$columns['name_column'] = 'name_sub';
		$columns['email_column'] = 'email_sub';
		return $columns;
	}
	add_filter( 'manage_edit-submission_sortable_columns', 'vscf_make_columns_sortable' );

	function vscf_name_column_sortable( $vars ) {
		if ( is_admin() ) {
			if ( isset( $vars['orderby'] ) && 'name_sub' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => 'name_sub',
					'orderby' => 'meta_value'
				) );
			}
		}
		return $vars;
	}
	add_filter( 'request', 'vscf_name_column_sortable' );

	function vscf_email_column_sortable( $vars ) {
		if ( is_admin() ) {
			if ( isset( $vars['orderby'] ) && 'email_sub' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => 'email_sub',
					'orderby' => 'meta_value'
				) );
			}
		}
		return $vars;
	}
	add_filter( 'request', 'vscf_email_column_sortable' );
}

// get ip of user
function vscf_get_the_ip() {
	if (isset($_SERVER['REMOTE_ADDR'])) {
		$ip_address = $_SERVER['REMOTE_ADDR'];
	} else {
		$ip_address = '';
	}
	return esc_attr($ip_address);
}

// create name for sum transient
function vscf_transient_name() {
	$server = preg_replace("/[^a-zA-Z0-9]/", "", $_SERVER['SERVER_NAME']);
	$ip = preg_replace("/[^a-zA-Z0-9]/", "", vscf_get_the_ip());
	$transient_id = wp_hash($server.$ip);
	$transient_name = 'vscf_'.$transient_id;
	return $transient_name;
}

// create sum transient
function vscf_transient() {
	$transient_name = vscf_transient_name();
	$rand_one = random_int(1, 9);
	$rand_two = random_int(1, 9);
	if(get_transient($transient_name) === false) {
		set_transient($transient_name, array('rand_one' => $rand_one, 'rand_two' => $rand_two), HOUR_IN_SECONDS);
	}
}
add_action( 'init', 'vscf_transient' );

// add settings link
function vscf_action_links( $links ) {
	$settingslink = array( '<a href="'. admin_url( 'options-general.php?page=vscf' ) .'">'.__('Settings', 'very-simple-contact-form').'</a>' );
	return array_merge( $links, $settingslink );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'vscf_action_links' );

// create from email header
function vscf_from_header() {
	$server = $_SERVER['SERVER_NAME'];
	if ( (substr($server, 0, 4) == "http") || (substr($server, 0, 3) == "www") ) {
		$replace = array("http://" => "", "https://" => "", "www." => "");
		$domain = strtr($server, $replace);
	} else {
		$domain = 'example.com';
	}
	return esc_attr('wordpress@'.$domain);
}

// redirect when sending succeeds
function vscf_redirect_success() {
	$current_url = $_SERVER['REQUEST_URI'];
	if (strpos($current_url, '?') == true) {
		$url_with_param = $current_url."&vscf-sh=success";
	} else {
		if (substr($current_url, -1) == '/') {
			$url_with_param = $current_url."?vscf-sh=success";
		} else {
			$url_with_param = $current_url."/?vscf-sh=success";
		}
	}
	return esc_url_raw($url_with_param);
}

function vscf_widget_redirect_success() {
	$current_url = $_SERVER['REQUEST_URI'];
	if (strpos($current_url, '?') == true) {
		$url_with_param = $current_url."&vscf-wi=success";
	} else {
		if (substr($current_url, -1) == '/') {
			$url_with_param = $current_url."?vscf-wi=success";
		} else {
			$url_with_param = $current_url."/?vscf-wi=success";
		}
	}
	return esc_url_raw($url_with_param);
}

// redirect when sending fails
function vscf_redirect_error() {
	$current_url = $_SERVER['REQUEST_URI'];
	if (strpos($current_url, '?') == true) {
		$url_with_param = $current_url."&vscf-sh=fail";
	} else {
		if (substr($current_url, -1) == '/') {
			$url_with_param = $current_url."?vscf-sh=fail";
		} else {
			$url_with_param = $current_url."/?vscf-sh=fail";
		}
	}
	return esc_url_raw($url_with_param);
}

function vscf_widget_redirect_error() {
	$current_url = $_SERVER['REQUEST_URI'];
	if (strpos($current_url, '?') == true) {
		$url_with_param = $current_url."&vscf-wi=fail";
	} else {
		if (substr($current_url, -1) == '/') {
			$url_with_param = $current_url."?vscf-wi=fail";
		} else {
			$url_with_param = $current_url."/?vscf-wi=fail";
		}
	}
	return esc_url_raw($url_with_param);
}

// include files
include 'vscf-shortcodes.php';
include 'vscf-widget.php';
include 'vscf-options.php';
