<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasForgotPassword extends CanvasForm {


	/**
	 * @var string
	 */
	protected $email;
	/**
	 * @var bool
	 */
	protected $secure_cookie = false;
	/**
	 * @var string
	 */
	protected $template = 'forgot-password-template.php';
	/**
	 * @var string
	 */
	protected $page_title;

	public function __construct() {
		 $this->canvas_error_validation    = new WP_Error();
		$this->page_title                  = __( 'Forgot password' );
		$this->canvas_message_notification = __( ' Please enter your username or email address. You will receive a link to create a new password via email.' );
		if ( isset( $_POST['canvas_fp_submit'] ) ) {
			$this->forgot_pass();
		} else {
			parent::get_form( $this->template, $this->page_title );
		}
	}

	/**
	 * Main function to login by all sanitized data,
	 * Setting cookie or return error message
	 */
	public function forgot_pass() {
		 $errors = retrieve_password();
		if ( is_wp_error( $errors ) ) {
			// Errors found
			$this->canvas_error_validation->add( 'user_name', __( ' Please check your email/username and try again.' ) );
		} else {
			// Email sent
			$this->canvas_message_notification = __( ' Please check your email to reset the password' );
			$this->back_to_login               = true;
		}
		parent::get_form( $this->template, $this->page_title );
	}
}

new CanvasForgotPassword();
