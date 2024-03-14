<?php


namespace DataPeen\FaceAuth;

use DataPeen\FaceAuth\UserOptions;
use UxBuilder\Options\Option;

class Helpers {

	public static function is_email_enabled($user)
	{
		$option = UserOptions::get_option($user);
		return (
			count($option->get_array(Option_Names::PIN_METHODS)) > 0 &&
			in_array('email', $option->get_array(Option_Names::PIN_METHODS))
		);
	}

	public static function is_site_verified()
	{
		$common_option = Options::get_the_only_option(Config::COMMON_OPTION_NAME);

		$site_verified  = $common_option->get_bool(Option_Names::SECRET_TOKEN_VERIFIED);

		return $site_verified;
	}

	public static function is_authenticator_enabled($user)
	{
		$option = UserOptions::get_option($user);
		return (
			count($option->get_array(Option_Names::PIN_METHODS)) > 0 &&
			in_array('google_authenticator', $option->get_array(Option_Names::PIN_METHODS))
		);
	}

	public static function deauthorize_site()
	{
		$common_option = Options::get_the_only_option(Config::COMMON_OPTION_NAME);
		$common_option->unset_key(Option_Names::SECRET_TOKEN);
		$common_option->set(Option_Names::SECRET_TOKEN_VERIFIED, false);
	}

	/**
	 * if any of the 2 step methods enabled
	 */
	public static function is_two_step_enabled($user)
	{
		$option = UserOptions::get_option($user);

		return count($option->get_array(Option_Names::PIN_METHODS));
	}

}
