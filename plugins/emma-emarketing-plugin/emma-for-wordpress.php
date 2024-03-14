<?php

/**
 * Plugin Name: Emma For WordPress
 * Plugin URI: http://ahsodesigns.com/what-we-do/plugin-development/
 * Description: The Emma WordPress plugin allows you to quickly and easily add a signup form for your Emma list as a widget or a shortcode.
 * Version: 1.3.3
 * Author: Ah So
 * Author URI: http://ahsodesigns.com
 * Contributors: ahsodesigns, brettshumaker, thackston
 * License: GPLv2
 *
 */

/*  Copyright 2012 Ah SO Designs  (email : info@ahsodesigns.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// proxy for debugging emma API calls
//define('WP_PROXY_HOST','192.168.1.7');
//define('WP_PROXY_PORT','8888');

define( 'EMMA_EMARKETING_PATH',     dirname( __FILE__ ) );
define( 'EMMA_EMARKETING_URL',      plugins_url( '', __FILE__ ) );
define( 'EMMA_EMARKETING_FILE',     plugin_basename( __FILE__ ) );
define( 'EMMA_EMARKETING_ASSETS',   EMMA_EMARKETING_URL . '/assets' );

// just sos ya know, this can't be called from the main class.
// i don't want to talk about it.
register_activation_hook( __FILE__, 'emma_plugin_activation' );
register_deactivation_hook( __FILE__, 'emma_plugin_deactivation' );

include_once( EMMA_EMARKETING_PATH . '/class-emma-emarketing.php' );

include_once('admin/class-account-information.php');
include_once('admin/class-advanced-settings.php');
include_once('admin/class-form-setup.php');
include_once('admin/class-form-custom.php');

function emma_admin_styles(){
	wp_register_script('emma admin js', EMMA_EMARKETING_URL . '/assets/js/emma-admin.js', array('jquery'), '201610201101');
	wp_enqueue_script('emma admin js');
	
	wp_localize_script('emma admin js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	
	
    wp_register_style('emma admin', EMMA_EMARKETING_URL . '/assets/css/emma-admin-styles.css' );
    wp_enqueue_style('emma admin');
}

add_action( 'admin_enqueue_scripts', 'emma_admin_styles' );

function emma_frontend_scripts() {
	wp_register_script('emma js', EMMA_EMARKETING_URL . '/assets/js/emma.js', array('jquery'), '201706140834');
	// We'll enqueue the script whenever we output the form so we're not loading it on pages without our form.
	
	wp_localize_script('emma js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
}
add_action( 'init', 'emma_frontend_scripts');

// instantiate main class
$emma_emarketing = new Emma_Emarketing();


function emma_plugin_activation() {
	$error_text = '';
	
    if( version_compare( PHP_VERSION, '5.2.6', '<' ) ) {
        $error_txt = 'The Emma For WordPress plugin requires at least PHP 5.2.6.';
    }
    if( version_compare( get_bloginfo( 'version' ), '3.1', '<' ) ) {
        $error_txt = 'The Emma For WordPress plugin requires at least WordPress version 3.1.';
    }

    // probably should do some checking before sending this off...
    if ( $error_text !== '' )
	    add_action( 'admin_notices', 'emma_version_require', $error_text );

    // load default options into database on activation
    // add_option( $option, $value, $depreciated, $autoload );
	add_option( Account_Information::$key, Account_Information::get_settings_defaults(), '', 'yes' );
	add_option( Advanced_Settings::$key, Advanced_Settings::get_settings_defaults(), '', 'yes' );
	add_option( Form_Setup::$key, Form_Setup::get_settings_defaults(), '', 'yes' );
	add_option( Form_Custom::$key, Form_Custom::get_settings_defaults(), '', 'yes' );

}

function emma_plugin_deactivation() {
	delete_option('emma_account_information');
	delete_option('emma_advanced_settings');
	delete_option('emma_form_custom');
	delete_option('emma_form_setup');
    // buh-bye!
}

function emma_version_require( $error_text ) {
    if( current_user_can( 'manage_options' ) )
        echo '<div class="error"><p>' . $error_txt . '</p></div>';
}



// setup our AJAX actions for the front-end - I couldn't do this from the class for whatever reason
add_action( 'wp_ajax_emma_ajax_form_submit', 'emma_ajax_form_submit_callback' );
add_action( 'wp_ajax_nopriv_emma_ajax_form_submit', 'emma_ajax_form_submit_callback' );

function emma_ajax_form_submit_callback() {
	
	$emma_email = $_POST['emma_email'];
	$emma_firstname = $_POST['emma_firstname'];
	$emma_lastname = $_POST['emma_lastname'];
	$emma_signup_form = $_POST['emma_signup_form_id'];
	
	$emma_form = new Emma_Form();
	
	if ( $emma_form->check_recaptcha_settings() ) {
	    
	    $recaptcha_result = emma_ajax_recaptcha_verify_callback( $_POST['token'], false );
	    
	    if ( ! $recaptcha_result )
	    	wp_send_json_error( array( 'txt' => 'ReCaptcha Validation Error', 'token' => $_POST['token'] ) );
	    
    }
	
	$emma_form->generate_form($_POST);
	
	$status_text = $emma_form->status_txt;
	$response = $emma_form->emma_response;
	
	$advanced_settings = get_option('emma_advanced_settings');
    $success_pixel = $advanced_settings['successTrackingPixel'];

	$response_array = array(
		'status_txt' => $status_text,
		'code' => $response,
		'raw_data' => $emma_form->raw_data,
		'raw_response' => $emma_form->raw_response,
		'tracking_pixel' => '' . apply_filters('emma_tracking_pixel', $success_pixel),
	);
	
	wp_send_json_success( $response_array );
}

add_action( 'wp_ajax_emma_ajax_recaptcha_verify', 'emma_ajax_recaptcha_verify_callback' );
add_action( 'wp_ajax_nopriv_emma_ajax_recaptcha_verify', 'emma_ajax_recaptcha_verify_callback' );

function emma_ajax_recaptcha_verify_callback( $token = null, $ajax = true ) {
	
	$result = false;
	$token = isset( $_POST['token'] ) ? $_POST['token'] : $token;
	
    if ( isset( $token ) ) {
	    
	    $emma_form = new Emma_Form();
	    
	    $recaptcha_settings = $emma_form->get_recaptcha_settings();
	    
	    $response = json_decode( file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptcha_settings['recaptchaSecretKey'] . '&response=' . $token ) );
	    
	    if ( isset( $response->success ) && $response->success )
			$result = true;
	    
    }
    
    if ( $ajax ) {
	    
	    if ( $result )
	    	wp_send_json_success();
	    
	    wp_send_json_error( $token );
	    
    } else {
	    
	    return $result;
	    
    }
    
}