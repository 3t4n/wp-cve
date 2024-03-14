<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/view/content-header-menubar'][] = function( $app, $ret )
{
// return $ret;
	$promo = $app->make('/promo.wordpress/view');

	$ret = $app->make('/html/list')
		->set_gutter(2)
		->add( $promo )
		->add( $ret )
		;

	return $ret;
};