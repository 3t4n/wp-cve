<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasLogin extends CanvasForm {

	/**
	 * @var array
	 */
	protected $data;
	/**
	 * @var string
	 */
	protected $user_name;
	/**
	 * @var string
	 */
	protected $password;
	/**
	 * @var bool
	 */
	protected $secure_cookie = false;
	/**
	 * @var string
	 */
	protected $template = 'login-template.php';
	/**
	 * @var string
	 */
	protected $page_title;

	public function __construct() {
		$this->page_title = __( 'Login' );
		if ( isset( $_POST['canvas_login_submit'] ) ) {
			$this->data = $_POST;
			$this->login();
		} else {
			parent::get_form( $this->template, $this->page_title );
		}
	}

	/**
	 * validate and sanitize the form data, set the redirect link, check if it is needed to force user use SSL for login
	 */
	public function sanitize_form_data() {
		$this->canvas_error_validation = new WP_Error();
		if ( is_array( $this->data ) && ! empty( $this->data ) ) {
			if ( ! isset( $this->data['canvas_nonce'] ) || ! wp_verify_nonce( $this->data['canvas_nonce'], 'canvas-login' ) ) {
				$this->canvas_error_validation->add( 'nonce_failed', __( ' Security error. Please update a page and try again later.' ) );
				return;
			}

			$this->user_name = isset( $this->data['canvas_user_login'] ) ? stripslashes( trim( $this->data['canvas_user_login'] ) ) : '';
			$this->password  = isset( $this->data['canvas_user_pass'] ) ? stripslashes( trim( $this->data['canvas_user_pass'] ) ) : '';

			if ( ! force_ssl_admin() ) {
				$user = is_email( $this->user_name ) ? get_user_by( 'email', $this->user_name ) : get_user_by( 'login', sanitize_user( $this->user_name ) );

				if ( $user && get_user_option( 'use_ssl', $user->ID ) ) {
					$this->secure_cookie = true;
					force_ssl_admin( true );
				}
			}
			if ( force_ssl_admin() ) {
				$this->secure_cookie = true;
			}
		} else {
			$this->canvas_error_validation->add( 'undefined_err', __( ' Something wrong happened. Please try again later' ) );
		}
	}

	/**
	 * Main function to login by all sanitized data,
	 * Setting cookie or return error message
	 */
	public function login() {
		$this->sanitize_form_data();
		$login_credentials                  = array();
		$login_credentials['user_login']    = $this->user_name;
		$login_credentials['user_password'] = $this->password;
		$login_credentials['remember']      = true;
		$user                               = wp_signon( $login_credentials, $this->secure_cookie );

		if ( ! is_wp_error( $user ) ) {
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID );

			wp_safe_redirect( parent::get_redirect_link() );
		} else {
			if ( $user->errors ) {
				$this->canvas_error_validation->add( 'user_name', Canvas::get_option( 'canvas-ls-invalid-user-password', __( ' Invalid user or password' ) ) );
			} else {
				$this->canvas_error_validation->add( 'user_name', __( ' Please enter your username and password to login' ) );
			}
			parent::get_form( $this->template, $this->page_title );
		}
	}
}

new CanvasLogin();
