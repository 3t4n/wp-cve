<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/app/enqueuer'][] = function( $app, $enqueuer )
{
	static $done = FALSE;
	if( $done ){
		return;
	}
	$done = TRUE;

	$enqueuer
		->register_script( 'gmaps', 'happ2/modules/maps_google/assets/js/gmaps.js' )
		;

	$app_settings = $app->make('/app/settings');
	$api_key = $app_settings->get('maps_google:api_key');
	if( is_array($api_key) ){
		$api_key = array_shift($api_key);
	}

	if( $api_key == 'none' ){
		$api_key = '';
	}
	$api_key = trim($api_key);

	$map_style = $app_settings->get('maps_google:map_style');
	$scrollwheel = $app_settings->get('maps_google:scrollwheel');
	$scrollwheel = $scrollwheel ? TRUE : FALSE;
	$more_options = $app_settings->get('maps_google:more_options');

	$icon = '';
	$icon_id = $app_settings->get('maps_google:icon');
	if( $icon_id ){
		$your_img_src = wp_get_attachment_image_src( $icon_id, 'full' );
		$have_img = is_array( $your_img_src );
		if( $have_img ){
			$icon = $your_img_src[0];
		}
	}

	$params = array(
		'api_key'		=> $api_key,
		'map_style'		=> $map_style,
		'scrollwheel'	=> $scrollwheel,
		'more_options'	=> $more_options,
		'icon'			=> $icon,
		);

	$enqueuer
		->localize_script( 'gmaps', $params )
		;

	$enqueuer
		->enqueue_script( 'gmaps' )
		;

	$enqueuer
		->register_script( 'gmapsclusterer', 'happ2/modules/maps_google/assets/js/markerclusterer.js' )
		;
};