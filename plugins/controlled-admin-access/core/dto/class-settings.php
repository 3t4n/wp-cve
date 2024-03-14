<?php

namespace WPRuby_CAA\Core\Dto;


use WPRuby_CAA\Core\Constants;

class Settings {

	private $allow_password_login = true;
	private $redirect_after_login_to = 'dashboard';


	public function __construct()
	{
		$settings = get_option(Constants::CAA_SETTINGS, self::default_settings());
		$this->set_allow_password_login($settings['allow_password_login']);
		$this->set_redirect_after_login_to($settings['redirect_after_login_to']);
	}


	/**
	 * @param bool $allow_password_login
	 */
	public function set_allow_password_login( $allow_password_login ) {
		$this->allow_password_login = $allow_password_login;
	}

	/**
	 * @return string
	 */
	public function get_redirect_after_login_to() {
		return $this->redirect_after_login_to;
	}

	/**
	 * @param string $redirect_after_login_to
	 */
	public function set_redirect_after_login_to( $redirect_after_login_to ) {
		$this->redirect_after_login_to = $redirect_after_login_to;
	}

	public static function default_settings()
	{
		return [
			'redirect_after_login_to' => 'index.php',
			'allow_password_login' => true,
		];
	}

}
