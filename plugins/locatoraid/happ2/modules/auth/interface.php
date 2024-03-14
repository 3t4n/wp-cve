<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
if( ! interface_exists('_Auth_Lib_Interface_HC_MVC') ){
interface _Auth_Lib_Interface_HC_MVC
{
	public function set_password( $user_id, $password  );
	public function check( $username, $password  );
	public function logged_in();
	public function login( $user_id, $remember = FALSE );
	public function logout();
}
}