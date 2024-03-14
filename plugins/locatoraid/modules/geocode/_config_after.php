<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/locations/edit/view'][] = function( $app, $return, $location )
{
	if( $location['latitude'] && $location['longitude'] ){
		return $return;
	}

	$geocode_view = $app->make('/geocode/view')
		->render( $location )
		;

	$out = $app->make('/html/list')
		->set_gutter( 2 )
		->add( $geocode_view )
		->add( $return )
		;

	return $out;
};
