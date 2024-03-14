<?php
/**
 * =======================================
 * Advanced Invisible AntiSpam Comments
 * =======================================
 * 
 * 
 * @author Matt Keys <matt@mattkeys.me>
 */

if ( ! defined( 'AIA_PLUGIN_FILE' ) ) {
	die();
}

class AIA_bbPress
{
	private $key_name;

	public function init()
	{
		add_action( 'bbp_theme_before_topic_form', array( $this, 'add_token_placeholder' ) );
		add_action( 'bbp_theme_before_reply_form', array( $this, 'add_token_placeholder' ) );
		add_filter( 'bbp_new_topic_pre_extras', array( $this, 'check_token' ) );
		add_filter( 'bbp_new_reply_pre_extras', array( $this, 'check_token' ) );
		add_action( 'bbp_theme_before_topic_form', array( $this, 'javascript_warning' ) );
		add_action( 'bbp_theme_before_reply_form', array( $this, 'javascript_warning' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );

		$this->key_name = AIA_Helpers::get_key_name();
	}

	public function add_token_placeholder()
	{
		echo '<input id="aia_placeholder" type="hidden">';
	}

	public function check_token()
	{
		if ( current_user_can( 'moderate_comments' ) ) {
			return;
		}

		$post_key		= isset( $_POST[ $this->key_name ] ) ? $_POST[ $this->key_name ] : false;
		$nonce_action	= 'aia_antispam_' . $this->key_name;

		if ( ! $post_key ) {
			$previous_field_name = get_option( 'aia_previous_field_key' );
			$post_key = isset( $_POST[ $previous_field_name ] ) ? $_POST[ $previous_field_name ] : false;
			$nonce_action	= 'aia_antispam_' . $previous_field_name;
		}

		if ( wp_verify_nonce( $post_key, $nonce_action ) ) {
			return;
		}

		$failure_message	= __( 'Sorry, your post could not be added due to an AntiSpam error. Make sure that your browser has JavaScript enabled before submitting posts. If problems persist please contact an administrator', 'AIA' );
		$failure_title		= __( 'AntiSpam Error', 'AIA' );

		do_action( 'aia-token-failed', $_POST, 'bbpress' );

		bbp_add_error( 'aia_antispam_error', '<strong>' . $failure_title . '</strong>: ' . $failure_message );
	}

	public function javascript_warning()
	{
		$warning_text = __( 'JavaScript is required to submit posts. Please enable JavaScript before proceeding.', 'AIA' );
		echo apply_filters( 'aia-javascript-warning', '<noscript>' . $warning_text . '</noscript>', $warning_text, 'comment' );
	}

	public function enqueue_script()
	{
		if ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			wp_enqueue_script( 'advanced-invisible-antispam', AIA_PUBLIC_PATH . 'includes/aia.js', false, '1.1', true );
			wp_localize_script( 'advanced-invisible-antispam', 'AIA', array(
					'ajaxurl'	=> admin_url( 'admin-ajax.php' )
				)
			);
		}
	}

}

add_action(	'plugins_loaded', array( new AIA_bbPress, 'init' ) );
