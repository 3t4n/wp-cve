<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['bootstrap'][] = function( $app )
{
	$is_me = $app->make('/app/lib')
		->isme()
		;
	if( $is_me ){
		$enqueuer = $app->make('/app/enqueuer');
	}
};