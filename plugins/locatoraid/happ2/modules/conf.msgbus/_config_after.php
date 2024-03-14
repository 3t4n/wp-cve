<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/conf/model->save'][] = function( $app, $return )
{
	$msg = __('Settings Updated', 'locatoraid');
	$msgbus = $app->make('/msgbus/lib');
	$msgbus->add('message', $msg);
};
