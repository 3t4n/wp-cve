<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['bootstrap'][] = function( $app )
{
	$is_me = $app->make('/app/lib')
		->isme()
		;
	if( ! $is_me ){
		return;
	}

	$view = $app->make('/flashdata.wordpress.layout/view-admin-notices');
	add_action( 'admin_notices', array($view, 'render') );
};