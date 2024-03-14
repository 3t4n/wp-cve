<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$g_page = unserialize( get_option( 'Admin_custome_login_gcaptcha' ) );

if ( isset( $g_page['login_enable_gcaptcha'] ) && ( 'yes' === $g_page['login_enable_gcaptcha'] ) && isset( $g_page['login_v_gcaptcha'] ) && ! empty( $g_page['login_v_gcaptcha'] ) ) {

	$login_v_gcaptcha = esc_html( $g_page['login_v_gcaptcha'] );

	if ( 'v3' === $login_v_gcaptcha ) {
		add_action( 'login_form', 'acl_captcha3_form' );
		add_action( 'login_enqueue_scripts', 'acl_captcha3_output' );
		add_action( 'wp_authenticate_user', 'acl_validate_captcha3', 10, 2 );
	} elseif ( 'v2' === $login_v_gcaptcha ) {
		add_action( 'login_form', 'acl_captcha2_form' );
		add_action( 'login_enqueue_scripts', 'acl_captcha2_output' );
		add_action( 'wp_authenticate_user', 'acl_validate_captcha2', 10, 2 );
	}
}

function acl_captcha2_form() {
	$g_page             = unserialize( get_option( 'Admin_custome_login_gcaptcha' ) );
	$site_key           = isset( $g_page['site_key'] ) ? $g_page['site_key'] : '';
	$acl_gcaptcha_theme = isset( $g_page['acl_gcaptcha_theme'] ) ? $g_page['acl_gcaptcha_theme'] : 'light';
	if ( 'yes' === $acl_gcaptcha_theme ) {
		$acl_gcaptcha_theme = 'light';
	} else {
		$acl_gcaptcha_theme = 'dark';
	}
	?>
	<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $site_key ); ?>" data-theme="<?php echo esc_attr( $acl_gcaptcha_theme ); ?>"></div>	<br>
	<?php
}

function acl_captcha2_output() {
	wp_enqueue_script( 'acl-recaptcha-api-js', 'https://www.google.com/recaptcha/api.js' );
}

/**
 * Undocumented function
 *
 * @param [type] $user
 * @param [type] $password
 * @return void
 */
function acl_validate_captcha2( $user, $password ) {
	$g_page     = unserialize( get_option( 'Admin_custome_login_gcaptcha' ) );
	$secret_key = isset( $g_page['secret_key'] ) ? $g_page['secret_key'] : '';

	if ( isset( $_POST['g-recaptcha-response'] ) ) {
		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret'   => $secret_key,
					'response' => $_POST['g-recaptcha-response'],
				),
			)
		);

		$data = wp_remote_retrieve_body( $response );
		$data = json_decode( $data );

		if ( isset( $data->success ) && $data->success ) {
			return $user;
		}
	}

	return new WP_Error( wp_kses_post( 'empty_captcha', '<strong>ERROR</strong>: Please confirm you are not a robot', WEBLIZAR_ACL ) );
}

function acl_captcha3_form() {
	?>
	<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
	<?php
}

function acl_captcha3_output() {
	$g_page = unserialize( get_option( 'Admin_custome_login_gcaptcha' ) );
	$site_key_v3 = isset( $g_page['site_key_v3'] ) ? $g_page['site_key_v3'] : '';

	wp_enqueue_script( 'acl-recaptcha-api-js', "https://www.google.com/recaptcha/api.js?render=$site_key_v3" );
	wp_register_script( 'acl-recaptcha-v3-js', '', array( 'acl-recaptcha-api-js' ) );
	wp_enqueue_script( 'acl-recaptcha-v3-js' );

		$script = "grecaptcha.ready(function() {
			grecaptcha.execute('$site_key_v3', {action: 'login'}).then(function(token) {
				document.getElementById('g-recaptcha-response').value = token;
			});
		});";
	wp_add_inline_script( 'acl-recaptcha-v3-js', $script );
}

function acl_validate_captcha3( $user, $password ) {
	$g_page = unserialize( get_option( 'Admin_custome_login_gcaptcha' ) );

	$secret_key_v3 = isset( $g_page['secret_key_v3'] ) ? $g_page['secret_key_v3'] : '';

	if ( isset( $_POST['g-recaptcha-response'] ) ) {
		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret'   => $secret_key_v3,
					'response' => $_POST['g-recaptcha-response'],
				),
			)
		);

		$data = wp_remote_retrieve_body( $response );
		$data = json_decode( $data );

		if ( isset( $data->success ) && $data->success && isset( $data->score ) && $data->score > 0 && isset( $data->action ) && 'login' === $data->action ) {
			return $user;
		}
	}

	return new WP_Error( wp_kses_post( 'empty_captcha', '<strong>ERROR</strong>: Please confirm you are not a robot', WEBLIZAR_ACL ) );
}

?>
