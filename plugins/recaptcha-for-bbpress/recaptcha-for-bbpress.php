<?php
/*
 * Plugin Name: reCAPTCHA for bbPress
 * Plugin URI: http://wordpress.org/plugins/recaptcha-for-bbpress/
 * Description: Protect your bbPress forum from spam using Googles reCAPTCHA v2. This plugin prevent bots to spam your forum and has option to enabe reCAPTCHA for guest users & logged-in users.
 * Author: Hitesh Chandwani
 * Version: 1.0.5
 * Author URI: https://hiteshchandwani.com
 * Text Domain: recaptcha-for-bbpress
 * Domain Path: /languages
 */

$plugin = plugin_basename(__FILE__);

function rfb_load_textdomain() {
    load_plugin_textdomain( 'recaptcha-for-bbpress', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'rfb_load_textdomain' );

function rfb_plugin_settings_link( $links ) {
	if ( is_plugin_active( 'bbpress/bbpress.php' ) ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', 'options-general.php?page=bbpress#_bbp_allow_anonymous', __( 'Settings', 'recaptcha-for-bbpress' ) );
		array_unshift( $links, $settings_link );
	}

	return $links;
}
add_filter( "plugin_action_links_$plugin", 'rfb_plugin_settings_link' );

function rfb_add_recaptcha_key_field( $fields ) {
	$new_fields_arrray = array();

	foreach ( $fields['bbp_settings_users'] as $k => $v ) {
		$new_fields_arrray[$k] = $v;

		if ( $k == '_bbp_allow_anonymous' ) {
			$new_fields_arrray['rfb_bbp_recaptcha_site_key'] = array();
			$new_fields_arrray['rfb_bbp_recaptcha_site_key']['title'] = __( 'reCAPTCHA v2 Site Key', 'recaptcha-for-bbpress' );
			$new_fields_arrray['rfb_bbp_recaptcha_site_key']['callback'] = 'rfb_bbp_admin_setting_callback_recaptcha_site_key';
			$new_fields_arrray['rfb_bbp_recaptcha_site_key']['sanitize_callback'] = 'sanitize_text_field';
			$new_fields_arrray['rfb_bbp_recaptcha_site_key']['args']  = array();
			$new_fields_arrray['rfb_bbp_recaptcha_server_key'] = array();
			$new_fields_arrray['rfb_bbp_recaptcha_server_key']['title'] = __( 'reCAPTCHA v2 Secret Key', 'recaptcha-for-bbpress' );
			$new_fields_arrray['rfb_bbp_recaptcha_server_key']['callback'] = 'rfb_bbp_admin_setting_callback_recaptcha_server_key';
			$new_fields_arrray['rfb_bbp_recaptcha_server_key']['sanitize_callback'] = 'sanitize_text_field';
			$new_fields_arrray['rfb_bbp_recaptcha_server_key']['args']  = array();
			$new_fields_arrray['rfb_bbp_recaptcha_registerd_user'] = array();
			$new_fields_arrray['rfb_bbp_recaptcha_registerd_user']['title'] = __( 'reCAPTCHA logged in User', 'recaptcha-for-bbpress' );
			$new_fields_arrray['rfb_bbp_recaptcha_registerd_user']['callback'] = 'rfb_bbp_admin_setting_callback_recaptcha_registered_users';
			$new_fields_arrray['rfb_bbp_recaptcha_registerd_user']['sanitize_callback'] = 'sanitize_text_field';
			$new_fields_arrray['rfb_bbp_recaptcha_registerd_user']['args']  = array();
		}
	}

	$fields['bbp_settings_users'] = $new_fields_arrray;

	return $fields;
}
add_filter( 'bbp_admin_get_settings_fields', 'rfb_add_recaptcha_key_field', 10, 1 );

function rfb_bbp_admin_setting_callback_recaptcha_site_key() { ?>
	<input name="rfb_bbp_recaptcha_site_key" id="rfb_bbp_recaptcha_site_key" type="text" value="<?php bbp_form_option( 'rfb_bbp_recaptcha_site_key', '' , true ); ?>" class="regular-text code" <?php bbp_maybe_admin_setting_disabled( 'rfb_bbp_recaptcha_site_key' ); ?> placeholder="**************"/>
	<br>
	<label for="rfb_bbp_recaptcha_site_key"><a href="https://www.google.com/recaptcha/admin" target="_blank" tabindex="-1"><?php esc_html_e( 'Get reCAPTCHA v2 Api Keys', 'recaptcha-for-bbpress' ); ?></a></label>
	<?php
}


function rfb_bbp_admin_setting_callback_recaptcha_server_key() { ?>
	<input name="rfb_bbp_recaptcha_server_key" id="rfb_bbp_recaptcha_server_key" type="text" value="<?php bbp_form_option( 'rfb_bbp_recaptcha_server_key', '' , true ); ?>" class="regular-text code" <?php bbp_maybe_admin_setting_disabled( 'rfb_bbp_recaptcha_server_key' ); ?> placeholder="**************"/>
	<?php
}

function rfb_bbp_admin_setting_callback_recaptcha_registered_users() {
	$_bbp_recaptcha_registerd_user = get_option( 'rfb_bbp_recaptcha_registerd_user' , false ); ?>
	<input name="rfb_bbp_recaptcha_registerd_user" id="rfb_bbp_recaptcha_registerd_user" type="checkbox" value="1" <?php checked( $_bbp_recaptcha_registerd_user ); bbp_maybe_admin_setting_disabled( 'rfb_bbp_recaptcha_registerd_user' ); ?> />
	<label for="rfb_bbp_recaptcha_registerd_user"><?php esc_html_e( 'Enable for logged-in users?', 'recaptcha-for-bbpress' ); ?></label>
	<?php
}

function rfb_bbp_captcha_integrate() {
	$is_guest_enable 					= get_option( '_bbp_allow_anonymous', false );
	$site_key							= get_option( 'rfb_bbp_recaptcha_site_key', false );
	$server_key 						= get_option( 'rfb_bbp_recaptcha_server_key', false );
	$rfb_bbp_recaptcha_registerd_user 	= get_option( 'rfb_bbp_recaptcha_registerd_user', false );
	
	if ( $server_key != false & $site_key != false & ( ( $is_guest_enable != false and !is_user_logged_in() ) or $rfb_bbp_recaptcha_registerd_user != false ) ) {
		printf( '<div class="g-recaptcha" data-sitekey="%1$s"></div>', esc_attr( $site_key ) );
		wp_enqueue_script( 'rfb-google-reCaptcha', 'https://www.google.com/recaptcha/api.js?hl=en' );
	}	
}
add_action( 'bbp_theme_before_reply_form_submit_wrapper', 'rfb_bbp_captcha_integrate' );
add_action( 'bbp_theme_before_topic_form_submit_wrapper', 'rfb_bbp_captcha_integrate' );

function rfb_bbp_validate_recaptcha() {
	$is_guest_enable					= get_option( '_bbp_allow_anonymous', false) ;
	$site_key 							= get_option( 'rfb_bbp_recaptcha_site_key', false );
	$server_key 						= get_option( 'rfb_bbp_recaptcha_server_key', false );
	$rfb_bbp_recaptcha_registerd_user 	= get_option( 'rfb_bbp_recaptcha_registerd_user', false );

	if ( $server_key != false & $site_key != false & $server_key != false & ( ( $is_guest_enable != false and !is_user_logged_in() ) or $rfb_bbp_recaptcha_registerd_user != false ) ) {
		include ( plugin_dir_path( __FILE__ ) .'src/autoload.php' );
		
		$recaptcha = new \ReCaptcha\ReCaptcha( $server_key );
		$gRecaptchaResponse = $_POST['g-recaptcha-response'];
		$remoteIp = $_SERVER['REMOTE_ADDR']; 
		$resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp); 

		if ( !$resp->isSuccess() ) {
			bbp_add_error( 'bbp_throw_error', __( "<strong>ERROR</strong>: reCAPTCHA is required.", 'recaptcha-for-bbpress') );
		}
	}
}
add_action('bbp_new_reply_pre_extras', 'rfb_bbp_validate_recaptcha');
add_action('bbp_new_topic_pre_extras', 'rfb_bbp_validate_recaptcha');