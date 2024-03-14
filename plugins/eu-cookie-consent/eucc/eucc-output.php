<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function rs_eucc_js_load() {
	$option = rs_eucc_get_option();
	if( ( !is_user_logged_in() ) && ( $option['cookie_consent_status'] == 'on' ) ) {
		wp_register_script( 'rs_eucc_js_load', RS_EUCC__PLUGIN_URL.RS_EUCC__BASE.'cookieconsent2/js/cookieconsent.min.js', array(), RS_EUCC__CC_VERSION, FALSE );
		wp_enqueue_script( 'rs_eucc_js_load' );
		wp_localize_script( 'rs_eucc_js_load', 'rs_eucc_js', array( 'cc_assets' => RS_EUCC__PLUGIN_URL.RS_EUCC__BASE.'cookieconsent2/css/' ) );
	}
}

function rs_eucc_js_output() {
	$option = rs_eucc_get_option();
	if( ( wp_script_is( 'rs_eucc_js_load', 'done' ) && ( !is_user_logged_in() ) && ( $option['cookie_consent_status'] == 'on' ) ) ) {
		$output = '<script type="text/javascript">';
		$output .= 'window.cookieconsent_options = {';
		$output .= '"message":"'.esc_html( stripslashes( $option['visitor_message'] ) ).'",';
		$output .= '"dismiss":"'.esc_html( stripslashes( $option['dismiss_text'] ) ).'",';
		$output .= '"learnMore":"'.esc_html( stripslashes( $option['policy_link_text'] ) ).'",';
		$output .= '"link":"'.esc_html( stripslashes( $option['policy_link_url'] ) ).'",';
		$output .= '"theme":"'.$option['colour_scheme'].'-'.$option['notice_position'].'",';
		$output .= '"target":"_blank"};</script>';
		echo $output;
	}
}