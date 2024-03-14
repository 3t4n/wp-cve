<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

new Math_Captcha_Cookie_Session();

class Math_Captcha_Cookie_Session {

	public $session_ids;

	public function __construct() {
		// set instance
		Math_Captcha()->cookie_session = $this;

		// actions
		add_action( 'plugins_loaded', array( $this, 'init_session' ), 1 );
	}

	/**
	 * Initialize cookie-session.
	 */
	public function init_session() {
		if ( is_admin() )
			return;

		if ( isset( $_COOKIE['mc_session_ids'] ) && is_array( $_COOKIE['mc_session_ids'] ) && array_key_exists( 'default', $_COOKIE['mc_session_ids'] ) && array_key_exists( 'multi', $_COOKIE['mc_session_ids'] ) )
			$this->session_ids = $_COOKIE['mc_session_ids'];
		else {
			foreach ( array( 'default', 'multi' ) as $place ) {
				switch ( $place ) {
					case 'multi':
						for ( $i = 0; $i < 5; $i ++  ) {
							$this->session_ids[$place][$i] = sha1( $this->generate_password() );
						}
						break;

					case 'default':
						$this->session_ids[$place] = sha1( $this->generate_password() );
						break;
				}
			}
		}

		if ( ! isset( $_COOKIE['mc_session_ids'] ) ) {
			setcookie( 'mc_session_ids[default]', $this->session_ids['default'], current_time( 'timestamp', true ) + apply_filters( 'math_captcha_time', Math_Captcha()->options['general']['time'] ), COOKIEPATH, COOKIE_DOMAIN, (isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ? true : false ), true );

			for ( $i = 0; $i < 5; $i ++  ) {
				setcookie( 'mc_session_ids[multi][' . $i . ']', $this->session_ids['multi'][$i], current_time( 'timestamp', true ) + apply_filters( 'math_captcha_time', Math_Captcha()->options['general']['time'] ), COOKIEPATH, COOKIE_DOMAIN );
			}
		}
	}

	/**
	 * Generate password helper, without wp_rand() call
	 * 
	 * @param int $length
	 * @return string
	 */
	private function generate_password( $length = 64 ) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$password = '';

		for ( $i = 0; $i < $length; $i ++  ) {
			$password .= substr( $chars, mt_rand( 0, strlen( $chars ) - 1 ), 1 );
		}

		return $password;
	}

}