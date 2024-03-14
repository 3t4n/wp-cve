<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/layout/top-menu'][] = function( $app, $return )
{
	$is_setup = $app->make('/setup/lib')
		->is_setup()
		;
	if( ! $is_setup ){
		return $return;
	}

	$not_geocoded_count = $app->make('/locations/commands/read')
		->execute(
			array(
				'count',
				array( 'latitude', '=', NULL ),
				array( 'longitude', '=', NULL )
				)
			);

	$not_geocoded_count2 = $app->make('/locations/commands/read')
		->execute(
			array(
				'count',
				array( 'latitude', '=', '0' ),
				array( 'longitude', '=', '0' )
				)
			);

	$not_geocoded_count = $not_geocoded_count + $not_geocoded_count2;

	if( ! $not_geocoded_count ){
		return $return;
	}

	$label = __('Geocode', 'locatoraid');
	$label .= ' (' . $not_geocoded_count . ')';

	$link = $app->make('/html/ahref')
		->to('/geocodebulk')
		->add( $app->make('/html/icon')->icon('exclamation') )
		->add( $label )
		;

	$return['geocodebulk'] = $link;

	return $return;
};
