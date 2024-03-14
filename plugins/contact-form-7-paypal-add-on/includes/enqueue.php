<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



// admin enqueue
function cf7pp_admin_enqueue() {

	// admin css
	wp_register_style('cf7pp-admin-css',plugins_url('/assets/css/admin.css',__DIR__),array(),CF7PP_VERSION_NUM);
	wp_enqueue_style('cf7pp-admin-css');

	// admin js
	wp_enqueue_script('cf7pp-admin',plugins_url('/assets/js/admin.js',__DIR__),array('jquery'),CF7PP_VERSION_NUM);
	wp_localize_script( 'cf7pp-admin', 'cf7pp', [
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce( 'cf7pp-free-request' )
	] );
}
add_action('admin_enqueue_scripts','cf7pp_admin_enqueue');





// public enqueue
function cf7pp_public_enqueue() {

	// path
	$site_url = get_home_url();
	$path_paypal = $site_url.'/?cf7pp_paypal_redirect=';
	$path_stripe = $site_url.'/?cf7pp_stripe_redirect=';

	// stripe public key
	$options = cf7pp_free_options();

	// redirect method js
	wp_enqueue_script('cf7pp-redirect_method',plugins_url('/assets/js/redirect_method.js',__DIR__),array('jquery'),CF7PP_VERSION_NUM);
	wp_localize_script('cf7pp-redirect_method', 'ajax_object_cf7pp',
		array (
			'ajax_url' 			=> admin_url('admin-ajax.php'),
			'forms' 			=> cf7pp_forms_enabled(),
			'path_paypal'		=> $path_paypal,
			'path_stripe'		=> $path_stripe,
			'method'			=> $options['redirect'],
		)
	);


}
add_action('wp_enqueue_scripts','cf7pp_public_enqueue',10);
