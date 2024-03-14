<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['wordpress-users'] = array( 'users.wordpress-conf', __('Roles', 'locatoraid') );
	return $return;
};