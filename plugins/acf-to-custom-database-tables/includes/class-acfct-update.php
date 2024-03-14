<?php


class Acf_ct_update
{
	public static function activate()
	{
		$email = wp_get_current_user()->user_email;
		wp_remote_post('https://api.abhisheksatre.com/acfct/activate', array(
			"method" => "POST",
			"body" => json_encode(array(
				"website" => home_url(),
				"email" => $email,
				"pluginVersion" => ACF_CT_VERSION,
				"wpVersion" => get_bloginfo('version'),
				"pro" => (defined('ACF_CT_FREE_PLUGIN') && ACF_CT_FREE_PLUGIN === false),
				'language'	=> get_bloginfo('language'),
				'timezone'	=> get_option('timezone_string'),
			))
		));
	}
}
