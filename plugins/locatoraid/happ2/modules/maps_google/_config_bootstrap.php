<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['bootstrap'][] = function( $app )
{
	$is_me = $app->make('/app/lib')
		->isme()
		;
	if( ! $is_me ){
		return;
	}

	$app_settings = $app->make('/app/settings');
	$api_key = $app_settings->get('maps_google:api_key');
	if( is_array($api_key) ){
		$api_key = array_shift($api_key);
	}

	if( strlen($api_key) ){
		return;
	}

	$slug = $app->make('/http/uri')
		->slug()
		;

	if( substr($slug, 0, strlen('setup')) == 'setup' ){
		return;
	}

	if( in_array($slug, array('conf/update', 'maps-google-conf', 'maps-google-conf/update')) ){
		return;
	}

// redirect to field edit
	$uri = $app->make('/http/uri')
		->mode('web')
		->url('maps-google-conf')
		;
	$view = $app->make('/http/view/response')
		->set_redirect($uri)
		->render()
		;
	echo $view;
	exit;
};