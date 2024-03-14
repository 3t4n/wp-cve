<?php

class Youzify_Membership_Lost_Password {

	/**
	 * Init Lost Password Actions & Filters.
	 */
	public function __construct() {

		// Shortcodes
		add_shortcode( 'youzify_lost_password_page', array( $this, 'get_password_lost_form' ) );

		// return;

		// Redirects
		add_action( 'login_form_lostpassword', array( $this, 'redirect_to_youzify_lostpassword' ) );
		add_action( 'login_form_rp', array( $this, 'redirect_to_youzify_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_youzify_password_reset' ) );
		add_action( 'template_redirect', array( $this, 'check_reset_page_link' ) );

		// Handlers for form posting actions
		add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

		// Change Lost password form action and link.
        add_filter( 'youzify_lostpassword_url',  array( $this, 'lost_password_url' ), 10, 1 );
        add_filter( 'youzify_membership_form_action',  array( $this, 'lostpassword_form_action' ), 10, 2 );

	}

	/**
	 * Lost Password Form.
	 */
	function lostpassword_form_action( $action, $form ) {

		if ( isset( $_GET['action'] ) && $_GET['action'] == 'rp' ) {
			return $action;
		}

		if ( 'lost_password' == $form ) {
			$siteURL = get_option( 'siteurl' );
			return "{$siteURL}/wp-login.php?action=lostpassword";
		}

		return $action;

	}

	/**
	 * Redirects the user to the "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	 */
	public function redirect_to_youzify_lostpassword() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			if ( is_user_logged_in() ) {
				$this->redirect_logged_in_user();
				exit;
			}

			wp_redirect( youzify_membership_page_url( 'lost-password' ) );
			exit;
		}
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she is an admin or not.
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
		$user = wp_get_current_user();
		if ( user_can( $user, 'manage_options' ) ) {
			if ( $redirect_to ) {
				wp_safe_redirect( $redirect_to );
			} else {
				wp_redirect( admin_url() );
			}
		} else {
			wp_redirect( home_url() );
		}
	}


	/**
	 * Check Reset Password Page Link : if the key is valid or not then
	 * redirect to a custom page.
	 */
	function check_reset_page_link() {
		if ( isset( $_GET['action'] ) && 'rp' == $_GET['action'] ) {
			// Verify key / Login Combo
			$user = check_password_reset_key( sanitize_text_field( $_REQUEST['key'] ), sanitize_text_field( $_REQUEST['login'] ) );
			if ( ! $user || is_wp_error( $user ) ) {
				$login_page = youzify_membership_page_url( 'login' );
				if ( $user && 'expired_key' === $user->get_error_code()  ) {
					$this->redirect( 'expiredkey', $login_page );
				} else {
					$this->redirect( 'invalidkey', $login_page );
				}
			}

			// Show Lost Password Messages
			if ( isset( $_REQUEST['errors'] ) ) {
				$error_codes = explode( ',', sanitize_text_field( $_REQUEST['errors'] ) );
				$messages = array();
				foreach ( $error_codes as $error_code ) {

					// Get Message.
					$messages[] = youzify_membership_get_message( $this->get_error_message( $error_code ), $type );
					// Add Errors.
					youzify_membership_add_message( $messages, 'error');

				}

			}

		}

	}

	/**
	 * Redirects to the custom password reset page, or the login page
	 * if there are errors.
	 */
	public function redirect_to_youzify_password_reset() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
			// Verify key / login combo
			$user = check_password_reset_key( sanitize_text_field( $_REQUEST['key'] ), sanitize_text_field( $_REQUEST['login'] ) );
			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && 'expired_key' === $user->get_error_code() ) {
					wp_redirect( add_query_arg( 'login', 'expiredkey', youzify_membership_page_url( 'login' ) ) );
				} else {
					wp_redirect( add_query_arg( 'login', 'invalidkey', youzify_membership_page_url( 'login' ) ) );
				}
				exit;
			}
			// Reset Password Page
			$redirect_url = youzify_membership_page_url( 'lost-password' );
			$redirect_url = add_query_arg( 'action', 'rp', $redirect_url );
			$redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
			$redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

			wp_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * A shortcode for rendering the form used to initiate the password reset.
	 */
	public function get_password_lost_form() {

		if ( is_user_logged_in() ) {
			return false;
		}

		global $Youzify_Membership;

		// Render the Lost Password form.
		return $Youzify_Membership->form->get_page( 'lost_password' );

	}

	/**
	 * Messages Attributes
	 */
	function messages_attributes() {

		if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
			$attributes['login'] = sanitize_text_field( $_REQUEST['login'] );
			$attributes['key'] = sanitize_text_field( $_REQUEST['key'] );
		}

		// Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['errors'] ) ) {
			$error_codes = explode( ',', sanitize_text_field( $_REQUEST['errors'] ) );
			foreach ( $error_codes as $error_code ) {
				$attributes['errors'] []= $this->get_error_message( $error_code );
			}
		}

		return $attributes;
	}

	/**
	 * Attributes
	 */
	function attributes() {

		$attrs = $this->messages_attributes();

		return $attrs;
	}

	/**
	 * Initiates password reset.
	 */
	public function do_password_lost() {

		if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
			return false;
		}

		// Init Messages.
		$messages = array();

		// Process Reset Password.
		if ( youzify_is_bp_reset_password_active() ) {
			$errors = youzify_bp_retrieve_password();
		} else {
			$errors = retrieve_password();
		}

		if ( is_wp_error( $errors ) ) {

			// Errors found
			$redirect_url = youzify_membership_page_url( 'lost-password' );

			// Get Error Message.
			$messages = youzify_membership_get_error_messages( $errors->get_error_messages() );

			// Add Message.
			youzify_membership_add_message( $messages , 'error' );

			wp_safe_redirect( $redirect_url );
			exit;

		} else {

			// Email sent
			$this->redirect( 'lost_password_sent', youzify_membership_page_url( 'login' ), 'success' );

		}


	}

	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			// Get Data
			$rp_key 	= sanitize_text_field( $_REQUEST['rp_key'] );
			$rp_login 	= sanitize_text_field( $_REQUEST['rp_login'] );
			$login_page = youzify_membership_page_url( 'login' );

			$user = check_password_reset_key( $rp_key, $rp_login );

			if ( ! $user || is_wp_error( $user ) ) {
				if ( $user && 'expired_key' === $user->get_error_code() ) {
					wp_redirect( add_query_arg( 'login', 'expiredkey', $login_page ) );
				} else {
					wp_redirect( add_query_arg( 'login', 'invalidkey', $login_page ) );
				}
				exit;
			}


			if ( isset( $_POST['pass1'] ) ) {

				$password 	  = sanitize_text_field( $_POST['pass1'] );
				$redirect_url = youzify_membership_page_url( 'lost-password' );
				$redirect_url = add_query_arg( 'action', 'rp', $redirect_url );
				$redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
				$redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );

				// Check Password Lenght
				if ( 6 > strlen( $password ) ) {
					$redirect_url = add_query_arg( 'errors', 'password_length', $redirect_url );
					wp_redirect( $redirect_url );
					exit;
				}

				// Passwords don't match
				if ( $password != sanitize_text_field( $_POST['pass2'] ) ) {
					$redirect_url = add_query_arg( 'errors', 'password_reset_mismatch', $redirect_url );
					wp_redirect( $redirect_url );
					exit;
				}

				// Password is empty
				if ( empty( $password ) ) {
					$redirect_url = add_query_arg( 'errors', 'password_reset_empty', $redirect_url );
					wp_redirect( $redirect_url );
					exit;
				}

				// Parameter checks OK, reset password
				wp_set_password( $password, $user->ID );

				// Redirect To login Page.
				wp_redirect( add_query_arg( 'password', 'changed', $login_page ) );

			} else {
				echo __( 'Invalid request.', 'youzify' );
			}

			exit;
		}
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 */
	private function get_error_message( $error_code ) {

		switch ( $error_code ) {

			case 'empty_username':
				return __( 'You need to enter your email address to continue.', 'youzify' );

			case 'invalid_email':
			case 'invalidcombo':
				return __( 'There is no user registered with this email address.', 'youzify' );

			case 'password_length':
				return __( 'Password length must be greater than 6!', 'youzify' );

			case 'password_reset_mismatch':
				return __( "The two passwords you entered don't match.", 'youzify' );

			case 'password_reset_empty':
				return __( "Sorry, we don't accept empty passwords.", 'youzify' );

			default:
				break;
		}

		return __( 'An unknown error occurred. Please try again later.', 'youzify' );
	}

	/**
	 * Get Lost password Link.
	 */
	function lost_password_url( $default_link ) {

		// Get Lost Password Url.
	    $lost_password_url = youzify_membership_page_url( 'lost-password' );

	    if ( ! empty( $lost_password_url ) ) {
	    	$default_link = $lost_password_url;
	    }

	    return $default_link;
	}

	/**
	 * Redirect User To Specific Page..
	 */
	public function redirect( $code, $redirect_to = null, $type = null ) {

		global $Youzify_Membership;

		// Init Erros.
		$messages = array();

		// Get Redirect Url.
		$redirect_url = ! empty( $redirect_to ) ? $redirect_to : youzify_membership_page_url( 'lost-password' );

		// Get Message.
		$messages[] = youzify_membership_get_message( $Youzify_Membership->form->get_error_message( $code ), $type );

		// Get messages.
		youzify_membership_add_message( $messages, $type );

		// Redirect User.
		wp_redirect( $redirect_url );

		// Exit.
		exit;

	}
}

new Youzify_Membership_Lost_Password();
