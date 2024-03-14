<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Acl_Wordpress_Roles_HC_MVC extends _HC_MVC
{
	public function always_admin()
	{
		$return = array('administrator', 'developer');
		return $return;
	}

	public function roles_mapping()
	{
		$wp_always_admin = $this->always_admin();

		$app_settings = $this->app->make('/app/settings');
		$prefix = 'wordpress_users:role_';
		$return = array();
		$all_settings = $app_settings->get();

		foreach( $all_settings as $k => $v ){
			if( substr($k, 0, strlen($prefix)) == $prefix ){
				$name = substr($k, strlen($prefix));
				$return[ $name ] = $v;
			}
		}

		$rm = $this->app->make('/acl/roles');
		$admin_bits = $rm->get_bits( 'admin' );

		reset( $wp_always_admin );
		foreach( $wp_always_admin as $wp_always_admin ){
			if( ! isset($return[$wp_always_admin]) ){
				$return[$wp_always_admin] = 0;
			}
			$return[$wp_always_admin] = $return[$wp_always_admin] | $admin_bits;
		}

		return $return;
	}
}