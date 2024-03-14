<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Users_WordPress_Conf_Form_HC_MVC extends _HC_MVC
{
	public function inputs()
	{
		$return = array();

		$wp_roles = new WP_Roles();
		$wordpress_roles = $wp_roles->get_names();
		$wp_always_admin = $this->app->make('/acl.wordpress/roles')->always_admin();

		reset( $wordpress_roles );
		foreach( $wordpress_roles as $role_value => $role_name ){
			$this_field_pname = 'wordpress_users:role_' . $role_value;

			$this_input = $this->app->make('/acl/input/roles');
			if( in_array($role_value, $wp_always_admin) ){
				$this_input
					->set_readonly_options( array('admin') )
					;
			}

			$return[ $this_field_pname ] = $this_input;
		}

		return $return;
	}
}