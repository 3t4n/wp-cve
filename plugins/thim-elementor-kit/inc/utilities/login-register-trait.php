<?php

namespace Thim_EL_Kit;

trait LoginRegisterTrait {

	protected function thim_get_register_url() {
		$url = add_query_arg( 'action', 'register', $this->thim_get_current_url() );

		return $url;
	}

	protected function thim_get_login_page_url() {
		if ( $page = get_option( 'thim_login_page' ) ) {
			return get_permalink( $page );
		}

		return wp_login_url();
	}

	protected function thim_get_lost_password_url() {
		$url = add_query_arg( 'action', 'lostpassword', $this->thim_get_login_page_url() );

		return $url;
	}

	protected function thim_get_current_url() {
		global $wp;

		return home_url( add_query_arg( array(), $wp->request ) );
	}

	/**
	 * Check login has errors
	 *
	 * @param null|WP_User|WP_Error $user
	 * @param string                $username
	 *
	 * @return mixed
	 */
	public function login_authenticate( $user, $username ) {
		if ( ! $username || wp_doing_ajax() || ! isset( $_POST['thim_login_user'] ) ) {
			return $user;
		}
		if ( is_wp_error( $user ) ) {
			$error_code = $user->get_error_code();
			$error_msg  = '';

			if ( $error_code == 'incorrect_password' ) {
				$error_msg = __( 'Username or password is incorrect', 'thim-elementor-kit' );
			} else {
				$error_msg = str_replace( array( '<strong>', '</strong>' ), '', $user->errors[ $error_code ][0] );
			}

			$url = add_query_arg(
				array(
					'result'         => 'failed',
					'thim_login_msg' => rawurlencode( $error_msg ),
				),
				$this->thim_get_login_page_url()
			);

			wp_safe_redirect( $url );
			die;
		}

		return $user;
	}

	/**
	 * If login success change redirect url
	 *
	 * @priority
	 * 1. Redirect to checkout if click buy course before
	 * 2. Redirect to option login after success (on Customize \ General \ Utilities)
	 * 3. Redirect to $_REQUEST['redirect_to']
	 * 4. Redirect to current url
	 *
	 * @param string           $redirect_to
	 * @param string           $requested_redirect_to
	 * @param WP_User|WP_Error $user
	 *
	 * @return mixed|string|void
	 */
	public function login_success_redirect( $redirect_to, $requested_redirect_to, $user ) {
		if ( ! isset( $_POST['thim_login_user'] ) || $user instanceof WP_Error ) {
			return $redirect_to;
		}
		if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_to = $_REQUEST['redirect_to'];
		} else {
			$redirect_to = $this->thim_get_current_url();
		}

		return $redirect_to;
	}

	/**
	 * @param string   $user_login
	 * @param string   $email
	 * @param WP_Error $errors
	 */
	public function check_extra_register_fields( $user_login, $email, $errors ) {
		if ( wp_doing_ajax() || ! isset( $_POST['thim_register_user'] ) || ! isset( $_POST['password'] ) ) {
			return;
		}

		if ( ! isset( $_POST['repeat_password'] ) || $_POST['password'] !== $_POST['repeat_password'] ) {
			$errors->add( 'passwords_not_matched', __( 'Passwords must match', 'thim-elementor-kit' ) );
		}
	}

	/**
	 * Update password
	 *
	 * @priority
	 * 1. Redirect to checkout if click buy course before
	 * 2. Redirect to option Register after success (on Customize \ General \ Utilities)
	 * 3. Redirect to $_REQUEST['redirect_to']
	 * 4. Redirect to current url
	 *
	 * @param int $user_id
	 *
	 * @return bool|void
	 */
	public function register_update_pass_and_login( $user_id ) {
		if ( wp_doing_ajax() || ! isset( $_POST['thim_register_user'] ) || ! isset( $_POST['password'] ) ) {
			return;
		}

		$pw = sanitize_text_field( $_POST['password'] );

		$user_data              = array();
		$user_data['ID']        = $user_id;
		$user_data['user_pass'] = $pw;

		$new_user_id = wp_update_user( $user_data );

		if ( $new_user_id instanceof WP_Error ) {
			return;
		}

		// Login after registered
		if ( ! is_admin() ) {
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id );
			wp_new_user_notification(
				$user_id,
				null,
				'admin'
			); // new user registration notification only send to admin
			if ( ! empty( $_REQUEST['redirect_to'] ) ) {
				$redirect_to = $_REQUEST['redirect_to'];
			} else {
				$redirect_to = $this->thim_get_current_url();
			}

			wp_safe_redirect( $redirect_to );
			die;

		}
	}


	/**
	 * @param WP_Error $errors
	 *
	 * @return mixed
	 */
	public function register_failed( $errors ) {
		if ( wp_doing_ajax() || ! isset( $_POST['thim_register_user'] ) ) {
			return $errors;
		}

		$error_code = $errors->get_error_code();
		if ( $error_code ) {
			$error_msg = '';

			$error_msg = str_replace( array( '<strong>', '</strong>' ), '', $errors->errors[ $error_code ][0] );

			$url = add_query_arg(
				array(
					'action'            => 'register',
					'thim_register_msg' => rawurlencode( $error_msg ),
				),
				$this->thim_get_login_page_url()
			);

			wp_redirect( $url );
			die;
		}

		return $errors;
	}

	/**
	 * Register via email - send email success
	 *
	 * @param int $user_id
	 *
	 * @return void
	 */
	public function register_verify_mail_success_redirect( $user_id ) {
		if ( ( isset( $_POST['register_auto_login'] ) && $_POST['register_auto_login'] == '1' ) || wp_doing_ajax() || ! isset( $_POST['thim_register_user'] ) ) {
			return;
		}
		if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			$redirect_url = $_REQUEST['redirect_to'];
		} else {
			$redirect_url = $this->thim_get_current_url();
		}

		if ( ! empty( $redirect_url ) ) {
			$redirect_url = add_query_arg( array( 'result' => 'registered' ), $redirect_url );
			wp_safe_redirect( $redirect_url );
			die;
		}
	}

	/**
	 * @param array   $objectEmail
	 * @param WP_User $user
	 *
	 * @return array
	 */
	public function message_set_password_when_not_auto_login( $objectEmail = array(), $user = '' ) {
		if ( wp_doing_ajax() || ! isset( $_POST['thim_lostpass'] ) ) {
			return $objectEmail;
		}
		$key = get_password_reset_key( $user );

		$set_password_link = $this->thim_get_login_page_url() . "?action=rp&key=$key&login=" . rawurlencode( $user->user_login );

		$message  = sprintf( __( 'Username: ', 'thim-elementor-kit' ) . '%s', $user->user_login ) . "\r\n\r\n";
		$message .= __( 'To set your password, visit the following address:', 'thim-elementor-kit' ) . "\r\n\r\n";
		$message .= $set_password_link . "\r\n\r\n";

		$message .= wp_login_url() . "\r\n";

		$objectEmail['message'] = $message;

		return $objectEmail;
	}

	/*** Reset pass ***/

	/**
	 * Show error if have any error when enter username/email to reset password
	 *
	 * @param WP_Error $errors
	 */
	public function check_field_to_reset_password( $errors ) {
		if ( wp_doing_ajax() || ! isset( $_POST['thim_lostpass'] ) ) {
			return;
		}

		$error_msg  = '';
		$error_code = $errors->get_error_code();

		if ( $errors instanceof WP_Error && $errors->has_errors() && $error_code ) {
			$error_msg = str_replace( array( '<strong>', '</strong>' ), '', $errors->errors[ $error_code ][0] );
		} elseif ( $_POST['user_login'] ) {
			$user_login = trim( wp_unslash( sanitize_text_field( $_POST['user_login'] ) ) );

			if ( is_email( $user_login ) ) {
				$user_data = get_user_by_email( $user_login );
			} else {
				$user_data = get_user_by( 'login', $user_login );
			}

			if ( ! $user_data ) {
				$error_msg = __( 'Error: There is no account with that username or email address.', 'thim-elementor-kit' );
			}
		}

		if ( ! empty( $error_msg ) ) {
			//$error_msg = __( '<strong>Error</strong>: There is no account with that username or email address.' );
			add_filter( 'login_errors', 'check_field_to_reset_password', 1, 9 );

			$url = add_query_arg(
				array(
					'action'            => 'lostpassword',
					'thim_lostpass_msg' => rawurlencode( $error_msg ),
				),
				$this->thim_get_login_page_url()
			);

			wp_safe_redirect( $url );
			exit;
		}
	}

	public function validate_password_reset() {
		if ( wp_doing_ajax() || ! isset( $_POST['thim_lostpass'] ) ) {
			return;
		}

		$login_page = $this->thim_get_login_page_url();
		if ( 'POST' == filter_input( INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING ) ) {

			if ( ! isset( $_REQUEST['key'] ) || ! isset( $_REQUEST['login'] ) ) {
				return;
			}

			$error_msg = '';
			$key       = $_REQUEST['key'];
			$login     = $_REQUEST['login'];

			$user = check_password_reset_key( $key, $login );

			if ( ! $user || is_wp_error( $user ) ) {
				$error_msg  = 'invalid key';
				$error_code = $user->get_error_code();
				if ( $user && $error_code ) {
					$error_msg = $user->errors[ $error_code ][0];
				}

				wp_redirect(
					add_query_arg(
						array(
							'action'             => 'rp',
							'thim_resetpass_msg' => rawurlencode( $error_msg ),
						),
						$login_page
					)
				);

				die;
			}

			if ( isset( $_POST['password'] ) ) {

				if ( empty( $_POST['password'] ) ) {
					// Password is empty
					wp_redirect(
						add_query_arg(
							array(
								'action'           => 'rp',
								'key'              => rawurlencode( $_REQUEST['key'] ),
								'login'            => rawurlencode( $_REQUEST['login'] ),
								'invalid_password' => '1',
							),
							$login_page
						)
					);
					exit;
				}

				// Parameter checks OK, reset password
				reset_password( $user, $_POST['password'] );
				wp_redirect(
					add_query_arg(
						array(
							'result' => 'changed',
						),
						$login_page
					)
				);
			} else {
				_e( 'Invalid request.', 'thim-elementor-kit' );
			}

			exit;
		}
	}

	/**
	 * Content mail when user register success with auto login
	 *
	 * @param array $objectEmail
	 *
	 * @return array
	 */
	public function message_when_user_register_auto_login( $objectEmail = array() ) {
		if ( wp_doing_ajax() || ! isset( $_POST['thim_register_user'] ) || ! isset( $_POST['thim_login_user'] ) ) {
			return $objectEmail;
		}

		$objectEmail['subject'] = 'Welcome to [%s]';
		$objectEmail['message'] = sprintf(
			'Hi ###USERNAME###,

				You registered successfully on %s site

				This email has been sent to ###EMAIL###

				Regards,
				All at ###SITENAME###
				###SITEURL###',
			get_bloginfo()
		);

		return $objectEmail;

	}
}
