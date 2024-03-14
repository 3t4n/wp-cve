<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// returns the form id of the forms that have redirect enabled - used for redirect method 1 and method 2
function cf7rl_forms_enabled() {

	// array that will contain which forms redirect is enabled on
	$enabled = array();
	
	$args = array(
		'posts_per_page'   => 999,
		'post_type'        => 'wpcf7_contact_form',
		'post_status'      => 'publish',
	);
	$posts_array = get_posts($args);
	
	
	// loop through them and find out which ones have redirect enabled
	foreach($posts_array as $post) {
		
		$post_id = $post->ID;
		
		// url
		$enable = get_post_meta( $post_id, "_cf7rl_enable", true);
		
		if ($enable == "1") {
			
			$cf7rl_redirect_type = get_post_meta( $post_id, "_cf7rl_redirect_type", true);
			$cf7rl_url = get_post_meta( $post_id, "_cf7rl_url", true);
			$cf7rl_tab = get_post_meta( $post_id, "_cf7rl_tab", true);
			
			$enabled[] = '|'.$post_id.'|'.$cf7rl_redirect_type.'|'.$cf7rl_url.'|'.$cf7rl_tab.'|';
			
		}
		
	}

	return json_encode($enabled);

}


// for redirect method 2 - this must be loaded for redirect method 2 regardless of if the form has redirect enabled
$options = get_option('cf7rl_options');

if (isset($options['redirect'])) {
	if ($options['redirect'] == "2"  || $options['redirect'] == '') {
		
		if (!defined('WPCF7_LOAD_JS')) {
			define('WPCF7_LOAD_JS', false);
		}
		
	}
}



// return thank payment form
add_action('wp_ajax_cf7rl_get_form_thank', 'cf7rl_get_form_thank_callback');
add_action('wp_ajax_nopriv_cf7rl_get_form_thank', 'cf7rl_get_form_thank_callback');
function cf7rl_get_form_thank_callback() {

	$formid =						sanitize_text_field($_POST['formid']);
	$cf7rl_thank_you_page = 		get_post_meta($formid, "_cf7rl_thank_you_page", true);	
	
	$result = '';
	
	// thank you page
	$result .= "<div class='cf7rl_thank'>";
	$result .= "$cf7rl_thank_you_page";
	$result .= "<div>";


	$response = array(
		'html'         		=> $result,
	);

	echo json_encode($response);
	wp_die();
}