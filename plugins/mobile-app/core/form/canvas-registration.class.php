<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasRegistration extends CanvasForm {

	/**
	 * @var array
	 */
	protected $data;
	/**
	 * @var string
	 */
	protected $email;
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
	protected $template = 'registration-template.php';
	/**
	 * @var string
	 */
	protected $page_title;

	public function __construct() {
		if ( Canvas::get_option( 'enabled_registration' ) === '1' ) {
			$this->page_title = __( 'Register new account' );
			if ( isset( $_POST['canvas_register_submit'] ) ) {
				$this->data = $_POST;
				$this->register();
			} else {
				parent::get_form( $this->template, $this->page_title );
			}
		} else {
			wp_safe_redirect( home_url() );
		}
	}

	/**
	 * validate and sanitize the form data, set the redirect link, check if it is needed to force user use SSL for login
	 */
	public function sanitize_form_data() {
		$this->canvas_error_validation = new WP_Error();
		if ( is_array( $this->data ) && ! empty( $this->data ) ) {
			if ( ! isset( $this->data['canvas_nonce'] ) || ! wp_verify_nonce( $this->data['canvas_nonce'], 'canvas-registration' ) ) {
				$this->canvas_error_validation->add( 'nonce_failed', __( ' Security error. Please update a page and try again later.' ) );

				return;
			}

			$this->email    = filter_var( trim( $this->data['canvas_user_email'] ), FILTER_SANITIZE_EMAIL );
			$this->password = stripslashes( trim( $this->data['canvas_user_pass'] ) );
			$enabled_term   = Canvas::get_option( 'enabled_term' );

			if ( filter_var( $this->email, FILTER_VALIDATE_EMAIL ) ) {
				if ( empty( $this->email ) || empty( $this->password ) || ( $enabled_term && ( ! isset( $this->data['canvas_agree_term'] ) || $this->data['canvas_agree_term'] !== '1' ) ) ) {
					$this->canvas_error_validation->add( 'missed_field', __( ' Please fill in all required fields' ) );
				}
				if ( username_exists( $this->email ) ) {
					$this->canvas_error_validation->add( 'user_existed', __( ' User already existed' ) );
				}
			} else {
				$this->canvas_error_validation->add( 'email_invalid', __( ' Email address is not valid' ) );
			}
		} else {
			$this->canvas_error_validation->add( 'undefined_err', __( ' Something wrong happened. Please try again later' ) );
		}
	}

	/**
	 * Main function to login by all sanitized data,
	 * Setting cookie or return error message
	 */
	public function register() {
		$this->sanitize_form_data();
		if ( is_wp_error( $this->canvas_error_validation ) && 1 <= count( $this->canvas_error_validation->get_error_messages() ) ) {
			parent::get_form( $this->template, $this->page_title );
		} else {
			$selected_user_role = Canvas::get_option( 'user_role', 'app_user' );
			$user_data          = array(
				'user_login' => $this->email,
				'user_email' => $this->email,
				'user_pass'  => $this->password,
				'role'       => $selected_user_role,
			);
			$user_ID            = wp_insert_user( $user_data );
			if ( ! is_wp_error( $user_ID ) ) {
				$this->login_to_user();
			} else {
				$this->canvas_error_validation->add( 'user_create_failed', __( ' An error occurred when creating user, please try again later' ) );
				parent::get_form( $this->template, $this->page_title );
			}
		}
	}

	/**
	 * Login into newly added user
	 */
	public function login_to_user() {

		$login_credentials = array(
			'user_login'    => $this->email,
			'user_password' => $this->password,
			'remember'      => true,
		);
		$user              = wp_signon( $login_credentials, $this->secure_cookie );
		if ( ! is_wp_error( $user ) ) {
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID );
			$this->show_message_on_register_successed();
		}
	}

	public function show_message_on_register_successed() {
		add_action( 'canvas_login_register_style', array( $this, 'get_style_header' ) );
		add_action( 'canvas_login_register_scripts', array( $this, 'get_script_footer' ) );
		$canvas_page_title                  = 'Redirecting...';
		$registered_and_logged_in_successed = true;
		$redirect_link                      = parent::get_redirect_link();
		require_once CANVAS_DIR . 'views/login-registration/header.php';
		$this->get_logo();
		require_once CANVAS_DIR . 'views/login-registration/parts/notice.php';
		require_once CANVAS_DIR . 'views/login-registration/footer.php';
	}
}

new CanvasRegistration();
