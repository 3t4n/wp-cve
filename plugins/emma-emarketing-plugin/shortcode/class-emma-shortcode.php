<?php

include_once( EMMA_EMARKETING_PATH . '/class-emma-form.php' );

// Register shortcode [emma_form]
add_shortcode( 'emma_form', 'emma_form_shortcode' );

function emma_form_shortcode($atts) {
	$account_settings_options = get_option('emma_account_information');
	
	$atts = shortcode_atts( array(
		'signup_form_id' => $account_settings_options['form_signup_id'],
		'signup_form_layout' => '',
		'recaptcha' => '',
	), $atts, 'emma_form' );
	
	// call the dynamic stylesheet
	$emma_style = new Emma_Style();
	$returned = $emma_style->output();
	
	// Check to make sure we're signed in, otherwise display an alert
	if ($account_settings_options['logged_in'] == 'true') {
		$emma_form = new Emma_Form();
		
		$emma_form->signup_form_id = $atts['signup_form_id'];
		$emma_form->signup_form_layout = $atts['signup_form_layout'];
		
		if ( 'false' === $atts['recaptcha'] ) {
			add_filter( 'emma_set_use_recaptcha', 'emma_shortcode_set_use_recaptcha', 10, 2 );
		}
		
		$returned .= $emma_form->generate_form();
		
		if ( 'false' === $atts['recaptcha'] ) {
			remove_filter( 'emma_set_use_recaptcha', 'emma_shortcode_set_use_recaptcha' );
		}
		
	} else {
		// Only show the warning if the user is logged in
		if ( current_user_can('manage_options') ) {
			$account_settings_options = get_option('emma_account_information');
			$returned .= '<p class="emma-alert">You need to sign into your Emma account before we can display your form!</p>';
		}
	}
	// this just cracks me up.
	return $returned;
}

function emma_shortcode_set_use_recaptcha( $value, $settings ) {
	return false;
}