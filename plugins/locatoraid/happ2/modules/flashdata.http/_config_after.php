<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/http/view/response->prepare_redirect'][] = function( $app, $return )
{
	$msgbus = $app->make('/msgbus/lib');
	$session = $app->make('/session/lib');

	$msg = $msgbus->get('message');
	if( $msg ){
		$session->set_flashdata('message', $msg);
	}
	$error = $msgbus->get('error');
	if( $error ){
		$session->set_flashdata('error', $error);
	}
	$warning = $msgbus->get('warning');
	if( $warning ){
		$session->set_flashdata('warning', $warning);
	}
	$debug = $msgbus->get('debug');
	if( $debug ){
		$session->set_flashdata('debug', $debug);
	}

	return $return;
};