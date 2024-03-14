<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['maps-google'] = array( 'maps-google-conf', __('Google Maps', 'locatoraid') );
	return $return;
};