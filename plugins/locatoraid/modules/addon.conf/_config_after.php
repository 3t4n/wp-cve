<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/conf/view/layout->tabs'][] = function( $app, $ret )
{
	$ret['addon'] = array( 'addon.conf', __('Add-ons', 'locatoraid') );
	return $ret;
};