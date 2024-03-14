<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/root/link'][] = function( $app, $return )
{
	if( ! $return ){
		return $return;
	}

	// check module
	// also check if it ends with .conf
	$module = 'conf';

	$is_me = FALSE;

	if( ($module == $return) OR (substr($return, 0, strlen($module . '/')) == $module . '/') ){
		$is_me = TRUE;
	}
	else {
		$dotmodule = '.' . $module;
		if( substr($return, -strlen($dotmodule)) == $dotmodule ){
			$is_me = TRUE;
		}
		if( strpos($return, $dotmodule . '/') !== FALSE ){
			$is_me = TRUE;
		}

		$dotmodule = $module . '.';
		if( substr($return, 0, strlen($dotmodule)) == $dotmodule ){
			$is_me = TRUE;
		}
	}

	if( ! $is_me ){
		return $return;
	}

	// check if admin
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