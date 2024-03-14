<?php
/**
 * =======================================
 * Advanced Invisible AntiSpam Registration
 * =======================================
 * 
 * 
 * @author Matt Keys <matt@mattkeys.me>
 */

if ( ! defined( 'AIA_PLUGIN_FILE' ) ) {
	die();
}

class AIA_Registration
{
	private $key_name;

	public function init()
	{
		add_action( 'register_form', array( $this, 'add_token_placeholder' ) );
		add_filter( 'registration_errors', array( $this, 'check_token' ) );
		add_filter( 'login_message', array( $this, 'javascript_warning' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'custom_enqueue_script' ) );

		$this->key_name = AIA_Helpers::get_key_name();
	}

	public function add_token_placeholder()
	{
		echo '<input id="aia_placeholder" type="hidden">';
	}

	public function check_token( $errors )
	{
		if ( current_user_can( 'create_users' ) ) {
			return $errors;
		}

		$post_key		= isset( $_POST[ $this->key_name ] ) ? $_POST[ $this->key_name ] : false;
		$nonce_action	= 'aia_antispam_' . $this->key_name;

		if ( ! $post_key ) {
			$previous_field_name = get_option( 'aia_previous_field_key' );
			$post_key = isset( $_POST[ $previous_field_name ] ) ? $_POST[ $previous_field_name ] : false;
			$nonce_action	= 'aia_antispam_' . $previous_field_name;
		}

		if ( wp_verify_nonce( $post_key, $nonce_action ) ) {
			return $errors;
		}

		$failure_message	= __( 'Sorry, your user could not be created due to an AntiSpam error. Make sure that your browser has JavaScript enabled before registering. If problems persist please contact an administrator', 'AIA' );
		$failure_title		= __( 'AntiSpam Error', 'AIA' );

		do_action( 'aia-token-failed', $_POST, 'registration' );

		$errors->add( 'advanced_antispam', '<strong>' . $failure_title . '</strong>: ' . $failure_message );

		return $errors;
	}

	public function javascript_warning( $message )
	{
		if ( ! isset( $_REQUEST['action'] ) || 'register' != $_REQUEST['action'] ) {
			return $message;
		}

		$warning_text = __( 'JavaScript is required to register. Please enable JavaScript before proceeding.', 'AIA' );
		return $message . apply_filters( 'aia-javascript-warning', '<noscript><div id="login_error">' . $warning_text . '</div></noscript>', $warning_text, 'registration' );
	}

	public function enqueue_script()
	{
		wp_enqueue_script( 'advanced-invisible-antispam', AIA_PUBLIC_PATH . 'includes/aia.js', false, '1.1', true );
		wp_localize_script( 'advanced-invisible-antispam', 'AIA', array(
				'ajaxurl'	=> admin_url( 'admin-ajax.php' )
			)
		);
	}

	public function custom_enqueue_script()
	{
		$pages_to_enqueue = array();

		/**
		 * Filter the list of additional pages to enqueue our script on
		 */
		$pages_to_enqueue = apply_filters( 'aia-custom-registration-enqueue', $pages_to_enqueue );

		global $pagename;

		if ( ! in_array( $pagename, $pages_to_enqueue ) ) {
			return;
		}

		wp_enqueue_script( 'advanced-invisible-antispam', AIA_PUBLIC_PATH . 'includes/aia.js', false, '1.1', true );
		wp_localize_script( 'advanced-invisible-antispam', 'AIA', array(
				'ajaxurl'	=> admin_url( 'admin-ajax.php' )
			)
		);
	}

}

add_action(	'plugins_loaded', array( new AIA_Registration, 'init' ) );
