<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/locations/presenter->fields'][] = function( $app, $return )
{
	$return['directions'] = __('Directions', 'locatoraid');
	return $return;
};

$config['after']['/locations/presenter->present_front'][] = function( $app, $return, $search, $search_coordinates )
{
	if( ! ($return['latitude'] && $return['longitude']) ){
		return $return;
	}

	if( ( ($return['latitude'] == -1) OR ($return['longitude'] == -1) ) ){
		return $return;
	}

	if( ! $search_coordinates ){
		return $return;
	}
	if( ! is_array($search_coordinates) ){
		return $return;
	}

	$search_lat = array_shift( $search_coordinates );
	$search_lng = array_shift( $search_coordinates );
	if( ! ($search_lat && $search_lng) ){
		return $return;
	}

	$app_settings = $app->make('/app/settings');

	$this_pname = 'fields:directions:use';
	$this_pname_config = $app_settings->get($this_pname);
	if( ! $this_pname_config ){
		return $return;
	}

	$this_pname = 'fields:directions:label';
	$this_label = $app_settings->get($this_pname);
	$this_label = strlen($this_label) ? $this_label : __('Directions', 'locatoraid');

	$link_args = array(
		'class'			=> 'lpr-directions',
		'href'			=> '#',
		'data-to-lat'	=> $return['latitude'],
		'data-to-lng'	=> $return['longitude'],
		'data-from-lat'	=> $search_lat,
		'data-from-lng'	=> $search_lng,
		);

	$link_view = '<a';
	foreach( $link_args as $k => $v ){
		$link_view .= ' ' . $k . '="' . $v . '"';
	}

	$link_view .= '>';
	$link_view .= $this_label;
	$link_view .= '</a>';

	$return['directions'] = $link_view;
	return $return;
};
