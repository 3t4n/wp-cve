<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'users.wordpress-conf';
	if( ($module != $return) && (substr($return, 0, strlen($module . '/')) != $module . '/') ){
		return $return;
	}

	// check admin
	$wp_always_admin = $app->make('/acl.wordpress/roles')->always_admin();
	$wp_user = wp_get_current_user();
	if( array_intersect($wp_always_admin, (array) $wp_user->roles) ){
		return $return;
	}

	$return = FALSE;
	return $return;
};