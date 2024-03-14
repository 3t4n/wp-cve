<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$wp_roles = new WP_Roles();
$wordpress_roles = $wp_roles->get_names();

foreach( $wordpress_roles as $role_value => $role_name ){
	$default = 1;

	switch( $role_value ){
		case 'administrator':
		case 'developer':
			$config['settings']['wordpress_users:role_' . $role_value ] = 1;
			break;

		default:
			$config['settings']['wordpress_users:role_' . $role_value ] = 0;
			break;
	}
}