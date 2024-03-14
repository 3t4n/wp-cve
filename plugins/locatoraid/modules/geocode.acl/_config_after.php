<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	$module = 'geocode';
	if( ($module != $return) && (substr($return, 0, strlen($module . '/')) != $module . '/') ){
		return $return;
	}

	// check admin
	$logged_in = $app->make('/auth/lib')
		->logged_in()
		;
	$is_admin = $app->make('/acl/roles')
		->has_role( $logged_in, 'admin')
		;
	if( $is_admin ){
		return $return;
	}

	$return = FALSE;
	return $return;
};