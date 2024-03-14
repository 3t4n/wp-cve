<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/search/controller'][] = function( $app, $return )
{
	$uri = $app->make('/http/uri');

	$search = $uri->param('search');
	$ip_address = $app->make('/input/lib')->ip_address();
	$time = time();
	
	$values = array(
		'ip_address' => $ip_address,
		'search_text' => $search,
		'action_time' => $time,
		);

	$command = $app->make('/commands/create')
		->set_table('searchlog')
		;
	$command->execute( $values );

	return $return;
};
