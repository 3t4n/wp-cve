<?php

namespace DataPeen\FaceAuth;

use PHPGangsta_GoogleAuthenticator;
class GoogleAuthenticator
{

	private static $pga;

	private static function getPGAInstance()
	{
		if (self::$pga == null)
			self::$pga = new PHPGangsta_GoogleAuthenticator();
		return self::$pga;
	}

	public static function get_user_option($user_id)
	{
		$user = get_user_by('id', $user_id);

		$option_name = Config::OPTION_NAME . $user->user_login;

		return Options::get_the_only_option($option_name);


	}

	public static function get_secret($user_id)
	{

		$option = self::get_user_option($user_id);

		$key = $option->get_string(Option_Names::AUTHENTICATOR_KEY, '');
		if ($key === '')
		{
			$key =  trim( self::getPGAInstance()->createSecret() );

			$option->set(Option_Names::AUTHENTICATOR_KEY, $key);
		}

		return trim($key);
	}

	public static function get_code($user_id)
	{
		return self::getPGAInstance()->getCode(self::get_secret($user_id));
	}


	public static function get_image_url($user_id)
	{

		$secret = self::get_secret($user_id);

		$qrCodeUrl = self::getPGAInstance()->getQRCodeGoogleUrl(get_bloginfo('blogname', 'raw'), $secret);

		return $qrCodeUrl;
	}

	public static function verify_code($code, $user_id)
	{

		$secret = self::get_secret($user_id);

		return self::getPGAInstance()->verifyCode($secret, $code, 2);    // 2 = 2*30sec clock tolerance

	}

	public static function clear_code($user_id)
	{
		$option = self::get_user_option($user_id);
		$option->unset_key(Option_Names::AUTHENTICATOR_KEY);
		$option->set(Option_Names::AUTHENTICATOR_VERIFIED, false);

	}


}
