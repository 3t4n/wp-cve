<?php
defined( 'ABSPATH' ) || die();
require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-helper.php';

class STLSR_Captcha {
	public static function async_grecaptcha_v2( $tag, $handle ) {
		if ( 'recaptcha-api-v2' === $handle ) {
			return str_replace( ' src', ' async defer src', $tag );
		}

		return $tag;
	}

	public static function async_cf_turnstile( $tag, $handle ) {
		if ( 'cf-turnstile' === $handle ) {
			return str_replace( ' src', ' async defer src', $tag );
		}

		return $tag;
	}

	public static function login_form_captcha() {
		$capt = STLSR_Helper::capt_login();

		if ( $capt['enable'] && STLSR_Helper::is_wp_login() ) {
			STLSR_Helper::show_captcha( 'login', $capt );
		}
	}

	public static function login_verify_captcha( $user, $password ) {
		$capt = STLSR_Helper::capt_login();

		if ( $capt['enable'] ) {
			if ( ! STLSR_Helper::is_wp_login() ) {
				return $user;
			}

			$form       = esc_html__( 'Login', 'login-security-recaptcha' );
			$ip_address = STLSR_Helper::get_ip_address();

			if ( 'google_recaptcha_v2' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::grecaptcha_v2();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $user;
				}

				if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
					$data = STLSR_Helper::verify_grecaptcha2( $captcha );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $user;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						return $user;
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				return new WP_Error( 'captcha_invalid', $error_message );

			} elseif ( 'google_recaptcha_v3' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::grecaptcha_v3();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $user;
				}

				if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
					$data = STLSR_Helper::verify_grecaptcha3( $captcha, $ip_address );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $user;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						$grecaptcha_v3_score = (float) $captcha['score'];
						if ( isset( $data->action ) && ( 'login' === $data->action ) && isset( $data->score ) && ( $data->score >= $grecaptcha_v3_score ) ) {
							return $user;
						} else {
							$error_code = esc_html__( 'low-score', 'login-security-recaptcha' ) . ': ' . esc_html( $data->score );
							require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				return new WP_Error( 'captcha_invalid', $error_message );

			} elseif ( 'cf_turnstile' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::cf_turnstile();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $user;
				}

				if ( isset( $_POST['cf-turnstile-response'] ) && ! empty( $_POST['cf-turnstile-response'] ) ) {
					$data = STLSR_Helper::verify_cf_turnstile( $captcha, $ip_address );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $user;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						return $user;
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				return new WP_Error( 'captcha_invalid', $error_message );
			}
		}

		return $user;
	}

	public static function lostpassword_form_captcha() {
		$capt = STLSR_Helper::capt_lostpassword();

		if ( $capt['enable'] && STLSR_Helper::is_wp_login() ) {
			STLSR_Helper::show_captcha( 'lostpassword', $capt );
		}
	}

	public static function lostpassword_verify_captcha( $errors ) {
		$capt = STLSR_Helper::capt_lostpassword();

		if ( $capt['enable'] ) {
			if ( ! STLSR_Helper::is_wp_login() ) {
				return $errors;
			}

			$form       = esc_html__( 'Lost Password', 'login-security-recaptcha' );
			$ip_address = STLSR_Helper::get_ip_address();

			if ( 'google_recaptcha_v2' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::grecaptcha_v2();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $errors;
				}

				if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
					$data = STLSR_Helper::verify_grecaptcha2( $captcha );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $errors;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						return $errors;
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				$errors->add( 'captcha_invalid', $error_message );

			} elseif ( 'google_recaptcha_v3' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::grecaptcha_v3();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $errors;
				}

				if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
					$data = STLSR_Helper::verify_grecaptcha3( $captcha, $ip_address );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $errors;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						$grecaptcha_v3_score = (float) $captcha['score'];
						if ( isset( $data->action ) && ( 'lostpassword' === $data->action ) && isset( $data->score ) && ( $data->score >= $grecaptcha_v3_score ) ) {
							return $errors;
						} else {
							$error_code = esc_html__( 'low-score', 'login-security-recaptcha' ) . ': ' . esc_html( $data->score );
							require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				$errors->add( 'captcha_invalid', $error_message );

			} elseif ( 'cf_turnstile' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::cf_turnstile();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $errors;
				}

				if ( isset( $_POST['cf-turnstile-response'] ) && ! empty( $_POST['cf-turnstile-response'] ) ) {
					$data = STLSR_Helper::verify_cf_turnstile( $captcha, $ip_address );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $errors;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						return $errors;
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				$errors->add( 'captcha_invalid', $error_message );
			}
		}

		return $errors;
	}

	public static function register_form_captcha() {
		$capt = STLSR_Helper::capt_register();

		if ( $capt['enable'] ) {
			STLSR_Helper::show_captcha( 'register', $capt );
		}
	}

	public static function register_verify_captcha( $errors, $sanitized_user_login, $user_email ) {
		$capt = STLSR_Helper::capt_register();

		if ( $capt['enable'] ) {
			$form       = esc_html__( 'Register', 'login-security-recaptcha' );
			$ip_address = STLSR_Helper::get_ip_address();

			if ( 'google_recaptcha_v2' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::grecaptcha_v2();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $errors;
				}

				if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
					$data = STLSR_Helper::verify_grecaptcha2( $captcha );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $errors;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						return $errors;
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				$errors->add( 'captcha_invalid', $error_message );

			} elseif ( 'google_recaptcha_v3' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::grecaptcha_v3();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $errors;
				}

				if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
					$data = STLSR_Helper::verify_grecaptcha3( $captcha, $ip_address );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $errors;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						$grecaptcha_v3_score = (float) $captcha['score'];
						if ( isset( $data->action ) && ( 'register' === $data->action ) && isset( $data->score ) && ( $data->score >= $grecaptcha_v3_score ) ) {
							return $errors;
						} else {
							$error_code = esc_html__( 'low-score', 'login-security-recaptcha' ) . ': ' . esc_html( $data->score );
							require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				$errors->add( 'captcha_invalid', $error_message );

			} elseif ( 'cf_turnstile' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::cf_turnstile();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $errors;
				}

				if ( isset( $_POST['cf-turnstile-response'] ) && ! empty( $_POST['cf-turnstile-response'] ) ) {
					$data = STLSR_Helper::verify_cf_turnstile( $captcha, $ip_address );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $errors;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						return $errors;
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				$errors->add( 'captcha_invalid', $error_message );
			}
		}

		return $errors;
	}

	public static function comment_form_captcha() {
		$capt = STLSR_Helper::capt_comment();

		if ( $capt['enable'] ) {
			if ( ! is_user_logged_in() || $capt['logged_in'] ) {
				STLSR_Helper::show_captcha( 'comment', $capt );
			}
		}
	}

	public static function comment_verify_captcha( $commentdata ) {
		$capt = STLSR_Helper::capt_comment();

		if ( is_user_logged_in() && ! $capt['logged_in'] ) {
			return $commentdata;
		}

		if ( $capt['enable'] ) {
			$form       = esc_html__( 'Comment', 'login-security-recaptcha' );
			$ip_address = STLSR_Helper::get_ip_address();

			if ( 'google_recaptcha_v2' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::grecaptcha_v2();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $commentdata;
				}

				if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
					$data = STLSR_Helper::verify_grecaptcha2( $captcha );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $commentdata;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						return $commentdata;
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				wp_die( $error_message, '', array( 'back_link' => true ) );

			} elseif ( 'google_recaptcha_v3' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::grecaptcha_v3();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $commentdata;
				}

				if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {
					$data = STLSR_Helper::verify_grecaptcha3( $captcha, $ip_address );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $commentdata;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						$grecaptcha_v3_score = (float) $captcha['score'];
						if ( isset( $data->action ) && ( 'comment' === $data->action ) && isset( $data->score ) && ( $data->score >= $grecaptcha_v3_score ) ) {
							return $commentdata;
						} else {
							$error_code = esc_html__( 'low-score', 'login-security-recaptcha' ) . ': ' . esc_html( $data->score );
							require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				wp_die( $error_message, '', array( 'back_link' => true ) );

			} elseif ( 'cf_turnstile' === $capt['captcha'] ) {
				$captcha = STLSR_Helper::cf_turnstile();

				if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
					return $commentdata;
				}

				if ( isset( $_POST['cf-turnstile-response'] ) && ! empty( $_POST['cf-turnstile-response'] ) ) {
					$data = STLSR_Helper::verify_cf_turnstile( $captcha, $ip_address );

					if ( isset( $data->{'error-codes'} ) && is_array( $data->{'error-codes'} ) && count( $data->{'error-codes'} ) ) {
						require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-logger.php';
						foreach ( $data->{'error-codes'} as $error_code ) {
							STLSR_Logger::log_error( $error_code, $form, $capt['captcha'], $ip_address );
						}

						if ( 0 !== count( array_intersect( array( 'missing-input-secret', 'invalid-input-secret' ), $data->{'error-codes'} ) ) ) {
							return $commentdata;
						}
					}

					if ( isset( $data->success ) && ( true === $data->success ) ) {
						return $commentdata;
					}
				}

				$error_message = wp_kses( STLSR_Helper::get_msg()['captcha_error'], array( 'strong' => array() ) );
				wp_die( $error_message, '', array( 'back_link' => true ) );
			}
		}

		return $commentdata;
	}
}
