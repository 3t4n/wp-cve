<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/locations/commands/create'][] = function( $app )
{
	$msg_key = 'locations-create';
	$msgbus = $app->make('/msgbus/lib');

	$msg = __('Location Added', 'locatoraid');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};

$config['after']['/locations/commands/update'][] = function( $app )
{
	$msg_key = 'locations-update';
	$msgbus = $app->make('/msgbus/lib');

	$msg = __('Location Updated', 'locatoraid');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};

$config['after']['/locations/commands/delete'][] = function( $app )
{
	$msg_key = 'locations-delete';
	$msgbus = $app->make('/msgbus/lib');

	$msg = __('Location Deleted', 'locatoraid');
	$msgbus->add('message', $msg, $msg_key, TRUE);
};
