<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Wordpress_Setup_Controller_HC_MVC extends _HC_MVC
{
	public function extend_do_setup( $return, $args, $src )
	{
		$app_settings = $this->app->make('/app/settings');
		$values = array_shift( $args );
		// $skip = array('wordpress_users:role_administrator');

		foreach( $values as $k => $v ){
			if( in_array($k, $skip) ){
				continue;
			}
			if( ! strlen($v) ){
				continue;
			}
			$app_settings->set( $k, $v );
		}

		$wp_user = wp_get_current_user();

		$app_settings->set('email_from',		$wp_user->user_email );
		$app_settings->set('email_from_name',	$wp_user->display_name );

		return $return;
	}
}