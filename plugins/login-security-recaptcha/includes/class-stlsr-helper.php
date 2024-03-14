<?php
defined( 'ABSPATH' ) || die();

class STLSR_Helper {
	public static function captcha_list() {
		return array(
			'google_recaptcha_v2' => esc_html__( 'Google reCAPTCHA Version 2', 'login-security-recaptcha' ),
			'google_recaptcha_v3' => esc_html__( 'Google reCAPTCHA Version 3', 'login-security-recaptcha' ),
			'cf_turnstile'        => esc_html__( 'Cloudflare Turnstile', 'login-security-recaptcha' ),
		);
	}

	public static function grecaptcha_v2_themes() {
		return array(
			'light' => esc_html__( 'Light', 'login-security-recaptcha' ),
			'dark'  => esc_html__( 'Dark', 'login-security-recaptcha' ),
		);
	}

	public static function grecaptcha_v3_scores() {
		return array(
			'0.1' => esc_html__( '0.1', 'login-security-recaptcha' ),
			'0.2' => esc_html__( '0.2', 'login-security-recaptcha' ),
			'0.3' => esc_html__( '0.3', 'login-security-recaptcha' ),
			'0.4' => esc_html__( '0.4', 'login-security-recaptcha' ),
			'0.5' => esc_html__( '0.5', 'login-security-recaptcha' ),
			'0.6' => esc_html__( '0.6', 'login-security-recaptcha' ),
			'0.7' => esc_html__( '0.7', 'login-security-recaptcha' ),
		);
	}

	public static function grecaptcha_v3_badges() {
		return array(
			'inline'      => esc_html__( 'Inline', 'login-security-recaptcha' ),
			'bottomleft'  => esc_html__( 'Bottom - Left', 'login-security-recaptcha' ),
			'bottomright' => esc_html__( 'Bottom - Right', 'login-security-recaptcha' ),
		);
	}

	public static function cf_turnstile_themes() {
		return array(
			'auto'  => esc_html__( 'Auto', 'login-security-recaptcha' ),
			'light' => esc_html__( 'Light', 'login-security-recaptcha' ),
			'dark'  => esc_html__( 'Dark', 'login-security-recaptcha' ),
		);
	}

	public static function cf_turnstile_sizes() {
		return array(
			'normal'  => esc_html__( 'Normal', 'login-security-recaptcha' ),
			'compact' => esc_html__( 'Compact', 'login-security-recaptcha' ),
		);
	}

	public static function btn_selectors() {
		return apply_filters(
			'stls_captcha_btn_selectors',
			array(
				'login'        => '#loginform #wp-submit',
				'lostpassword' => '#lostpasswordform #wp-submit',
				'register'     => '#registerform #wp-submit',
				'comment'      => '#commentform [type="submit"]',
			)
		);
	}

	public static function steps_url_grecaptcha() {
		return 'https://scriptstown.com/how-to-get-site-and-secret-key-for-google-recaptcha/';
	}

	public static function steps_url_cf_turnstile() {
		return 'https://scriptstown.com/how-to-get-site-and-secret-key-for-cloudflare-turnstile/';
	}

	public static function grecaptcha_v2_default() {
		return array(
			'theme' => 'light',
		);
	}

	public static function grecaptcha_v2() {
		$options = get_option( 'stlsr_google_recaptcha_v2', array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$default = self::grecaptcha_v2_default();

		return array(
			'site_key'   => isset( $options['site_key'] ) ? esc_attr( $options['site_key'] ) : '',
			'secret_key' => isset( $options['secret_key'] ) ? esc_attr( $options['secret_key'] ) : '',
			'theme'      => isset( $options['theme'] ) ? esc_attr( $options['theme'] ) : $default['theme'],
		);
	}

	public static function show_grecaptcha_v2( $action, $captcha = array(), $inline_css = '' ) {
		if ( empty( $captcha ) ) {
			$captcha = self::grecaptcha_v2();
		}

		if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
			return;
		}

		add_filter( 'script_loader_tag', array( 'STLSR_Captcha', 'async_grecaptcha_v2' ), 10, 2 );

		wp_enqueue_style( 'stlsr', STLSR_PLUGIN_URL . 'assets/css/stlsr.css', array(), STLSR_PLUGIN_VERSION, 'all' );
		wp_style_add_data( 'stlsr', 'rtl', 'replace' );
		if ( $inline_css ) {
			wp_add_inline_style( 'stlsr', $inline_css );
		}

		$script = ( "if('function' !== typeof lsrecaptcha2) {
			function lsrecaptcha2() {
				[].forEach.call(document.querySelectorAll('.stls-grecaptcha2'), function(el) {
					const action = el.getAttribute('data-action');
					stgrecaptcha2[action] = grecaptcha.render(
						el,
						{
							'sitekey': '" . esc_attr( $captcha['site_key'] ) . "',
							'theme': '" . esc_attr( $captcha['theme'] ) . "'
						}
					);
				});
			}
		}" );

		wp_enqueue_script( 'recaptcha-api-v2', 'https://www.google.com/recaptcha/api.js?onload=lsrecaptcha2', array(), null );
		wp_add_inline_script( 'recaptcha-api-v2', $script, 'before' );
		wp_localize_script( 'recaptcha-api-v2', 'stgrecaptcha2', array() );
		?>
		<div id="stls-grecaptcha2-<?php echo esc_attr( $action ); ?>" class="stls-grecaptcha2" data-action="<?php echo esc_attr( $action ); ?>"></div>
		<?php
	}

	public static function verify_grecaptcha2( $captcha ) {
		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret'   => $captcha['secret_key'],
					'response' => $_POST['g-recaptcha-response'],
				),
			)
		);

		$data = wp_remote_retrieve_body( $response );
		return json_decode( $data );
	}

	public static function grecaptcha_v3_default() {
		return array(
			'score'    => '0.3',
			'badge'    => 'bottomright',
			'onaction' => true,
		);
	}

	public static function grecaptcha_v3() {
		$options = get_option( 'stlsr_google_recaptcha_v3', array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$default = self::grecaptcha_v3_default();

		return array(
			'site_key'   => isset( $options['site_key'] ) ? esc_attr( $options['site_key'] ) : '',
			'secret_key' => isset( $options['secret_key'] ) ? esc_attr( $options['secret_key'] ) : '',
			'score'      => isset( $options['score'] ) ? esc_attr( $options['score'] ) : $default['score'],
			'badge'      => isset( $options['badge'] ) ? esc_attr( $options['badge'] ) : $default['badge'],
			'onaction'   => isset( $options['onaction'] ) ? (bool) ( $options['onaction'] ) : $default['onaction'],
		);
	}

	public static function show_grecaptcha_v3( $action, $captcha = array(), $inline_css = '' ) {
		if ( empty( $captcha ) ) {
			$captcha = self::grecaptcha_v3();
		}

		if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
			return;
		}

		if ( $captcha['onaction'] ) {
			$script = ( "if('function' !== typeof lsrecaptcha3) {
				function lsrecaptcha3() {
					grecaptcha.ready(function() {
						[].forEach.call(document.querySelectorAll('.stls-grecaptcha3'), function(el) {
							const action = el.getAttribute('data-action');
							const form = el.form;
							form.addEventListener('submit', function(e) {
								e.preventDefault();
								grecaptcha.execute('" . esc_attr( $captcha['site_key'] ) . "', {action: action}).then(function(token) {
									el.setAttribute('value', token);
									const button = form.querySelector('[type=\"submit\"]');
									if(button) {
										const input = document.createElement('input');
										input.type = 'hidden';
										input.name = button.getAttribute('name');
										input.value = button.value;
										input.classList.add('stls-submit-input');
										var inputEls = document.querySelectorAll('.stls-submit-input');
										[].forEach.call(inputEls, function(inputEl) {
											inputEl.remove();
										});
										form.appendChild(input);
									}
									HTMLFormElement.prototype.submit.call(form);
								});
							});
						});
					});
				}
			}" );
		} else {
			$script = ( "if('function' !== typeof lsrecaptcha3) {
				function lsrecaptcha3() {
					grecaptcha.ready(function() {
						[].forEach.call(document.querySelectorAll('.stls-grecaptcha3'), function(el) {
							const action = el.getAttribute('data-action');
							const form = el.form;
							function lsgrecaptcha3SetToken(action) {
								if(action) {
									grecaptcha.execute('" . esc_attr( $captcha['site_key'] ) . "', {action: action}).then(function(token) {
										document.getElementById('lsrecaptcha3-res-' + action).value = token;
									});
								}
							}
							lsgrecaptcha3SetToken(action);
							setInterval(function() { lsgrecaptcha3SetToken(action); }, (2 * 60 * 1000));
						});
					});
				}
			}" );
		}

		wp_enqueue_script( 'recaptcha-api-v3', ( 'https://www.google.com/recaptcha/api.js?onload=lsrecaptcha3&render=' . esc_attr( $captcha['site_key'] ) . '&badge=' . esc_attr( $captcha['badge'] ) ), array(), null );
		wp_add_inline_script( 'recaptcha-api-v3', $script, 'before' );
		wp_localize_script( 'recaptcha-api-v3', 'stgrecaptcha3', array() );
		?>
		<input type="hidden" name="g-recaptcha-response" id="lsrecaptcha3-res-<?php echo esc_attr( $action ); ?>" class="stls-grecaptcha3" data-action="<?php echo esc_attr( $action ); ?>">
		<?php
	}

	public static function verify_grecaptcha3( $captcha, $ip_address ) {
		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret'   => $captcha['secret_key'],
					'response' => $_POST['g-recaptcha-response'],
					'remoteip' => $ip_address,
				),
			)
		);

		$data = wp_remote_retrieve_body( $response );
		return json_decode( $data );
	}

	public static function cf_turnstile_default() {
		return array(
			'theme'       => 'light',
			'size'        => 'normal',
			'disable_btn' => false,
		);
	}

	public static function cf_turnstile() {
		$options = get_option( 'stlsr_cf_turnstile', array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$default = self::cf_turnstile_default();

		return array(
			'site_key'    => isset( $options['site_key'] ) ? esc_attr( $options['site_key'] ) : '',
			'secret_key'  => isset( $options['secret_key'] ) ? esc_attr( $options['secret_key'] ) : '',
			'theme'       => isset( $options['theme'] ) ? esc_attr( $options['theme'] ) : $default['theme'],
			'size'        => isset( $options['size'] ) ? esc_attr( $options['size'] ) : $default['size'],
			'disable_btn' => isset( $options['disable_btn'] ) ? (bool) ( $options['disable_btn'] ) : $default['disable_btn'],
		);
	}

	public static function show_cf_turnstile( $action, $captcha = array(), $inline_css = '' ) {
		if ( empty( $captcha ) ) {
			$captcha = self::cf_turnstile();
		}

		if ( empty( $captcha['site_key'] ) || empty( $captcha['secret_key'] ) ) {
			return;
		}

		add_filter( 'script_loader_tag', array( 'STLSR_Captcha', 'async_cf_turnstile' ), 10, 2 );

		$btn_selector = '';
		if ( $captcha['disable_btn'] ) {
			$btn_selectors = self::btn_selectors();
			if ( isset( $btn_selectors[ $action ] ) ) {
				$btn_selector = $btn_selectors[ $action ];
			}

			if ( $btn_selector ) {
				$inline_css .= ( $btn_selector . '{pointer-events:none;opacity: 0.5;}' );
			}
		}

		wp_enqueue_style( 'stlsr', STLSR_PLUGIN_URL . 'assets/css/stlsr.css', array(), STLSR_PLUGIN_VERSION, 'all' );
		wp_style_add_data( 'stlsr', 'rtl', 'replace' );
		if ( $inline_css ) {
			wp_add_inline_style( 'stlsr', $inline_css );
		}

		$script = ( "if('function' !== typeof lscfturnstile) {
			function lscfturnstile() {
				[].forEach.call(document.querySelectorAll('.stls-cfturnstile'), function(el) {
					const action = el.getAttribute('data-action');
					var btn = el.getAttribute('data-btn');
					if(btn) {
						btn = JSON.parse(btn);
					}
					ststcfturnstile[action] = turnstile.render(
						el,
						{
							'sitekey': '" . esc_attr( $captcha['site_key'] ) . "',
							'theme': '" . esc_attr( $captcha['theme'] ) . "',
							'size': '" . esc_attr( $captcha['size'] ) . "',
							'action': action,
							'retry': 'auto',
							'refresh-expired': 'auto',
							'callback': function() {
								if(btn) {
									[].forEach.call(document.querySelectorAll(btn), function(el) {
										el.style.pointerEvents = 'auto';
										el.style.opacity = '1';
									});
								}
							}
						}
					);
				});
			}
		}" );

		wp_enqueue_script( 'cf-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js?onload=lscfturnstile', array(), null );
		wp_add_inline_script( 'cf-turnstile', $script, 'before' );
		wp_localize_script( 'cf-turnstile', 'ststcfturnstile', array() );
		?>
		<div id="stls-cfturnstile-<?php echo esc_attr( $action ); ?>" class="stls-cfturnstile stls-cfturnstile-<?php echo esc_attr( $captcha['size'] ); ?>" data-action="<?php echo esc_attr( $action ); ?>" data-btn="<?php echo esc_attr( wp_json_encode( $btn_selector ) ); ?>"></div>
		<?php
	}

	public static function verify_cf_turnstile( $captcha, $ip_address ) {
		$response = wp_remote_post(
			'https://challenges.cloudflare.com/turnstile/v0/siteverify',
			array(
				'body' => array(
					'secret'   => $captcha['secret_key'],
					'response' => $_POST['cf-turnstile-response'],
					'remoteip' => $ip_address,
				),
			)
		);

		$data = wp_remote_retrieve_body( $response );

		return json_decode( $data );
	}

	public static function show_captcha( $action, $capt = array(), $inline_css = '' ) {
		if ( 'google_recaptcha_v2' === $capt['captcha'] ) {
			self::show_grecaptcha_v2( $action );
		} elseif ( 'google_recaptcha_v3' === $capt['captcha'] ) {
			self::show_grecaptcha_v3( $action );
		} elseif ( 'cf_turnstile' === $capt['captcha'] ) {
			self::show_cf_turnstile( $action );
		}
	}

	public static function capt_login() {
		$capt = get_option( 'stlsr_login_captcha' );
		if ( ! is_array( $capt ) ) {
			$capt = array();
		}

		return array(
			'enable'  => isset( $capt['enable'] ) ? (bool) $capt['enable'] : false,
			'captcha' => isset( $capt['captcha'] ) ? esc_attr( $capt['captcha'] ) : '',
		);
	}

	public static function capt_lostpassword() {
		$capt = get_option( 'stlsr_lostpassword_captcha' );
		if ( ! is_array( $capt ) ) {
			$capt = array();
		}

		return array(
			'enable'  => isset( $capt['enable'] ) ? (bool) $capt['enable'] : false,
			'captcha' => isset( $capt['captcha'] ) ? esc_attr( $capt['captcha'] ) : '',
		);
	}

	public static function capt_register() {
		$capt = get_option( 'stlsr_register_captcha' );
		if ( ! is_array( $capt ) ) {
			$capt = array();
		}

		return array(
			'enable'  => isset( $capt['enable'] ) ? (bool) $capt['enable'] : false,
			'captcha' => isset( $capt['captcha'] ) ? esc_attr( $capt['captcha'] ) : '',
		);
	}

	public static function capt_comment_default() {
		return array(
			'logged_in' => false,
		);
	}

	public static function capt_comment() {
		$capt = get_option( 'stlsr_comment_captcha' );
		if ( ! is_array( $capt ) ) {
			$capt = array();
		}

		$default = self::capt_comment_default();

		return array(
			'enable'    => isset( $capt['enable'] ) ? (bool) $capt['enable'] : false,
			'captcha'   => isset( $capt['captcha'] ) ? esc_attr( $capt['captcha'] ) : '',
			'logged_in' => isset( $capt['logged_in'] ) ? (bool) $capt['logged_in'] : $default['logged_in'],
		);
	}

	public static function get_pro_detail_url() {
		return 'https://scriptstown.com/wordpress-plugins/login-security-pro/';
	}

	public static function get_pro_url() {
		return 'https://scriptstown.com/account/signup/login-security-pro';
	}

	public static function get_msg() {
		return array(
			'captcha_error' => __( '<strong>Error:</strong> Please confirm you are not a robot.', 'login-security-pro' ),
		);
	}

	public static function get_ip_address() {
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		} else {
			$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '0.0.0.0';
		}

		$ip = filter_var( $ip, FILTER_VALIDATE_IP );
		$ip = ( false === $ip ) ? '0.0.0.0' : $ip;

		return $ip;
	}

	public static function wp_date( $format, $timestamp = null, $timezone = null ) {
		if ( function_exists( '\\wp_date' ) ) {
			return wp_date( $format, $timestamp, $timezone );
		}

		global $wp_locale;

		if ( null === $timestamp ) {
			$timestamp = time();
		} elseif ( ! is_numeric( $timestamp ) ) {
			return false;
		}

		if ( ! $timezone ) {
			$timezone = wp_timezone();
		}

		$datetime = date_create( '@' . $timestamp );
		$datetime->setTimezone( $timezone );

		if ( empty( $wp_locale->month ) || empty( $wp_locale->weekday ) ) {
			$date = $datetime->format( $format );
		} else {
			$format = preg_replace( '/(?<!\\\\)r/', DATE_RFC2822, $format );

			$new_format    = '';
			$format_length = strlen( $format );
			$month         = $wp_locale->get_month( $datetime->format( 'm' ) );
			$weekday       = $wp_locale->get_weekday( $datetime->format( 'w' ) );

			for ( $i = 0; $i < $format_length; $i ++ ) {
				switch ( $format[ $i ] ) {
					case 'D':
						$new_format .= addcslashes( $wp_locale->get_weekday_abbrev( $weekday ), '\\A..Za..z' );
						break;
					case 'F':
						$new_format .= addcslashes( $month, '\\A..Za..z' );
						break;
					case 'l':
						$new_format .= addcslashes( $weekday, '\\A..Za..z' );
						break;
					case 'M':
						$new_format .= addcslashes( $wp_locale->get_month_abbrev( $month ), '\\A..Za..z' );
						break;
					case 'a':
						$new_format .= addcslashes( $wp_locale->get_meridiem( $datetime->format( 'a' ) ), '\\A..Za..z' );
						break;
					case 'A':
						$new_format .= addcslashes( $wp_locale->get_meridiem( $datetime->format( 'A' ) ), '\\A..Za..z' );
						break;
					case '\\':
						$new_format .= $format[ $i ];

						if ( $i < $format_length ) {
							$new_format .= $format[ ++$i ];
						}
						break;
					default:
						$new_format .= $format[ $i ];
						break;
				}
			}

			$date = $datetime->format( $new_format );
			$date = wp_maybe_decline_date( $date, $format );
		}

		$date = apply_filters( 'wp_date', $date, $format, $timestamp, $timezone );

		return $date;
	}

	public static function is_wp_login() {
		$abspath = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, ABSPATH );
		return ( ( in_array( $abspath . 'wp-login.php', get_included_files() ) ) || ( isset( $_GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ) || '/wp-login.php' === $_SERVER['PHP_SELF'] );
	}
}
