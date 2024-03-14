<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$label = 'REST API';

	$link = $app->make('/html/ahref')
		->to('/rest')
		->add( $label )
		;

	$return['rest'] = array( $link, 110 );
	return $return;
};
