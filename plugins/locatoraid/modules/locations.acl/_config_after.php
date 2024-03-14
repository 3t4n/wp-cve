<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/root/link'][] = function( $app, $ret )
{
	if( ! $ret ){
		return $ret;
	}

	// check module
	$module = 'locations';

	// if( ($module != $ret) && (substr($ret, 0, strlen($module . '/')) != $module . '/') ){
		// return $ret;
	// }

	$our = false;
	if( $module == $ret ){
		$our = true;
	}
	elseif( substr($ret, 0, strlen($module . '/')) == $module . '/' ){
		$our = true;
	}
	elseif( substr($ret, 0, strlen($module . '.')) == $module . '.' ){
		$our = true;
	}

	if( ! $our ){
		return $ret;
	}

	// check admin
	$logged_in = $app->make('/auth/lib')
		->logged_in()
		;
	$is_admin = $app->make('/acl/roles')
		->has_role( $logged_in, 'admin')
		;
	if( $is_admin ){
		return $ret;
	}

	$ret = false;
	return $ret;
};