<?php
/**
 * Controls output of Flexible Frontend Plugin
 * 
 * All functions used to display the plugin's output for frontend users.
 * 
 * since version 0.9
 */

 
 /* Include the FrontendLogin class for handling of forms */
require_once( plugin_dir_path(__FILE__) . 'classes/class.FrontendLogin.php' );


/* Load Javascripts */
add_action( 'wp_enqueue_scripts', 'paw_ffl_enqueue_scripts' );

function paw_ffl_enqueue_scripts() 
{
	wp_enqueue_script( 'jquery', true );
	wp_enqueue_script( 'flexible-frontend-login', FFL_URL .'/js/ffl.js', 'jquery', true );
}

	
/* Add Shortcodes for Flexible Frontend Login and parse them */

add_shortcode( 'flexible-frontend-login-modal', 'paw_ffl_execute_shortcode_modal' );

function paw_ffl_execute_shortcode_modal( $atts, $content = null)
{
	if ( is_user_logged_in() ) { 
		return paw_ffl_show_user_info();
	} else { 
		return paw_ffl_create_form_modal();
	}
}


add_shortcode( 'flexiblefrontendlogin', 'paw_ffl_execute_shortcode' );
add_shortcode( 'flexible-frontend-login', 'paw_ffl_execute_shortcode' );

function paw_ffl_execute_shortcode( $atts, $content = null)
{
	$options = get_option( 'flexible_frontend_login' ); 
	$params = extract( shortcode_atts( array(
				'vertical' 		=> $options['vertical_position'],
				'horizontal'	=> $options['horizontal_position']
			), $atts ) ); 
	if ( is_user_logged_in() ) { 
		return paw_ffl_show_user_info();
	} else { 
		return paw_ffl_create_form( $vertical, $horizontal );
	}
}


/* Display user info  for logged in users */
function paw_ffl_show_user_info() 
{
	$options = get_option( 'flexible_frontend_login' ); 
	if ( !isset( $options['show_username'] ) ) $options['show_username'] = '0';
	if ( !isset( $options['show_logout_link'] ) ) $options['show_logout_link'] = '0';
	if ( !isset( $options['show_admin_link'] ) ) $options['show_admin_link'] = '0';
	
	$frontendlogin = new FrontendLogin;
	
		$frontendlogin->show_username = $options['show_username'];
		$frontendlogin->username_display_type = $options['username_display_type'];
		$frontendlogin->show_logout_link = $options['show_logout_link'];
		$frontendlogin->logout_link_text = $options['logout_link_text'];
		$frontendlogin->show_admin_link = $options['show_admin_link'];		
		$frontendlogin->admin_link_text = $options['admin_link_text'];		

	$output = $frontendlogin->ffl_show_user_info();
	
	return $output;
}


function paw_flexible_frontend_login( $vertical, $horizontal ) {
	if ( !is_user_logged_in() ) { 
		echo paw_ffl_create_form( $vertical, $horizontal );
	} else { 
		echo paw_ffl_show_user_info();
	}
}

function paw_flexible_frontend_login_modal() {
	if ( !is_user_logged_in() ) { 
		echo paw_ffl_create_form_modal();
	} else { 
		echo paw_ffl_show_user_info();
	}
}

/* Create the form */
function paw_ffl_create_form( $vertical='', $horizontal='' ) 
{
	$frontendlogin = new FrontendLogin;
	
	$options = get_option( 'flexible_frontend_login' ); 
	if ( empty( $vertical ) ) $vertical_position = $options['vertical_position'];
		else $vertical_position = $vertical;
	if ( empty( $horizontal ) ) $horizontal_position = $options['horizontal_position'];
		else $horizontal_position = $horizontal;
	
	$frontendlogin->vertical_position = $vertical_position;
	$frontendlogin->horizontal_position = $horizontal_position;
	
	$frontendlogin->popup_link_text = $options['popup_link_text'];
	$frontendlogin->html_block = $options['html_block'];
	
	$output = $frontendlogin->ffl_wrap_form_before();
	$output .= $frontendlogin->ffl_create_html_form();
	$output .= $frontendlogin->ffl_wrap_form_after();

	return $output;
}

/* Create the form */
/* If you change this function make sure to copy it to the test section on the options page */
function paw_ffl_create_form_modal() 
{
	$frontendlogin = new FrontendLogin;		

	$options = get_option( 'flexible_frontend_login' ); 
	
	$frontendlogin->popup_link_text = $options['popup_link_text'];
	$frontendlogin->html_block = $options['html_block'];
	
	$output  = $frontendlogin->ffl_wrap_form_modal_before();
	$output .= $frontendlogin->ffl_create_html_form();
	$output .= $frontendlogin->ffl_wrap_form_modal_after();
	
	return $output;
}


/* Provide template tags */
function flexible_frontend_login( $vertical, $horizontal ) {
	echo paw_flexible_frontend_login( $vertical, $horizontal );
}

function flexible_frontend_login_modal() {
	echo paw_flexible_frontend_login_modal();
}

/* Make sure redirect to the site itself is possible */
add_filter('allowed_redirect_hosts', 'paw_ffl_allow_redirect');
function paw_ffl_allow_redirect($allowed)
{
	$allowed[] = get_bloginfo( 'url' );
    return $allowed;
}


/* Bullet proof logout link */
function paw_ffl_logout_link() {
	$redirect = get_bloginfo( 'url' ) . $_SERVER[ 'REQUEST_URI' ];
	$logout_url = wp_logout_url( $redirect );
	$html = "<a id='ffl-logout-link' href='$logout_url'>" . __( 'Logout' ) . "</a>";
	return $html;
}
 
/* Provide a testlink for Admin */
function paw_ffl_test_link() {
	$options = get_option( 'flexible_frontend_login' ); 
	$blog_url = get_bloginfo( 'url' );
	$request_url = $_SERVER['REQUEST_URI'];
	$logout_url = wp_logout_url( get_permalink() );

}

// Validate input
function paw_ffl_validate_options( $input ) {
	return $input;
}

?>
