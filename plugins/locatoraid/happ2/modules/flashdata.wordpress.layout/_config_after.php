<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/view/body->content'][] = function( $app, $return )
{
	// in admin show by admin notices
	if( is_admin() ){
		return;
	}

	$flash_out = $app->make('/flashdata.layout/view')
		->render()
		;

	if( ! $flash_out ){
		return;
	}

	$return = $app->make('/html/list')
		->set_gutter(1)
		->add( $flash_out )
		->add( $return )
		;

	return $return;
};