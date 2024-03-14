<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$link = $app->make('/html/ahref')
		->to('/publish.wordpress')
		->add( $app->make('/html/icon')->icon('edit') )
		->add( 'Publish' )
		;
	$return['publish'] = array( $link, 80 );

	return $return;
};
