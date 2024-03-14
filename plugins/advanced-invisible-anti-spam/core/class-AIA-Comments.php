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

class AIA_Comments
{
	private $key_name;

	public function init()
	{
		add_action( 'comment_form', array( $this, 'add_token_placeholder' ) );
		add_filter( 'preprocess_comment', array( $this, 'check_token' ) );
		add_action( 'comment_form_top', array( $this, 'javascript_warning' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );

		$this->key_name = AIA_Helpers::get_key_name();
	}

	public function add_token_placeholder()
	{
		echo '<input id="aia_placeholder" type="hidden">';
	}

	public function check_token( $commentdata )
	{
		if ( current_user_can( 'moderate_comments' ) ) {
			return $commentdata;
		}

		$post_key		= isset( $_POST[ $this->key_name ] ) ? $_POST[ $this->key_name ] : false;
		$nonce_action	= 'aia_antispam_' . $this->key_name;

		if ( ! $post_key ) {
			$previous_field_name = get_option( 'aia_previous_field_key' );
			$post_key = isset( $_POST[ $previous_field_name ] ) ? $_POST[ $previous_field_name ] : false;
			$nonce_action	= 'aia_antispam_' . $previous_field_name;
		}

		if ( wp_verify_nonce( $post_key, $nonce_action ) ) {
			return $commentdata;
		}

		$failure_message	= __( 'Sorry, your comment could not be added due to an AntiSpam error. Make sure that your browser has JavaScript enabled before submitting comments. If problems persist please contact an administrator', 'AIA' );
		$failure_title		= __( 'AntiSpam Error', 'AIA' );

		do_action( 'aia-token-failed', $_POST, 'comment' );

		wp_die(
			apply_filters( 'aia-failure-message', $failure_message ),
			apply_filters( 'aia-failure-title', $failure_title ),
			array( 'back_link' => true )
		);
	}

	public function javascript_warning()
	{
		$warning_text = __( 'JavaScript is required to submit comments. Please enable JavaScript before proceeding.', 'AIA' );
		echo apply_filters( 'aia-javascript-warning', '<noscript>' . $warning_text . '</noscript>', $warning_text, 'comment' );
	}

	public function enqueue_script()
	{
		if ( is_singular() && comments_open() ) {
			wp_enqueue_script( 'advanced-invisible-antispam', AIA_PUBLIC_PATH . 'includes/aia.js', false, '1.1', true );
			wp_localize_script( 'advanced-invisible-antispam', 'AIA', array(
					'ajaxurl'	=> admin_url( 'admin-ajax.php' )
				)
			);
		}
	}

}

add_action(	'plugins_loaded', array( new AIA_Comments, 'init' ) );
