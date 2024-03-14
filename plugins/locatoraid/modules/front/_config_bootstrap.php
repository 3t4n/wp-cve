<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['bootstrap'][] = function( $app )
{
	$shortcode = 'locatoraid';
	$view = $app->make('/front/view/shortcode');
	add_shortcode( $shortcode, array($view, 'render'));
};