<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
include_once( dirname(__FILE__) . '/../auth/interface.php' );

class Auth_Wordpress_Lib_HC_MVC extends _HC_MVC implements _Auth_Lib_Interface_HC_MVC
{
	const USER_LOGIN_HASH = 'hc_user_hash';

	// returns the user id
	public function check( $username, $password )
	{
		$return = NULL;
		return $return;
	}

	public function logged_in()
	{
		$current_user = wp_get_current_user();
		$return = $current_user->ID;
		$return = $return ? $return : 0;
		return $return;
	}

	public function set_password( $user_id, $password )
	{
		$return = NULL;
		return $return;
	}

	public function login( $user_id, $remember = FALSE )
	{
		$return = NULL;
		return $return;
	}

	public function logout()
	{
		wp_clear_auth_cookie();
	}
}