<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$link = $app->make('/html/ahref')
		->to('/locations')
		->add( $app->make('/html/icon')->icon('home') )
		->add( __('Locations', 'locatoraid') )
		;
	$return['location'] = array( $link, 1 );

	return $return;
};
