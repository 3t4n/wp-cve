<?php


namespace DataPeen\FaceAuth;
use DataPeen\FaceAuth\Config;
use DataPeen\FaceAuth\Options;
use DataPeen\FaceAuth\Options_Form;

class UserOptions {

	public static function get_option_name($user)
	{
		return Config::OPTION_NAME . $user->user_login;
	}

	public static function get_option($user)
	{
		return Options::get_the_only_option(self::get_option_name($user));
	}

	public static function get_form_ui($user)
	{
		$user_option_id = Options::get_the_only_option_id(UserOptions::get_option_name($user));
		return new Options_Form(UserOptions::get_option_name($user), $user_option_id);

	}
}
