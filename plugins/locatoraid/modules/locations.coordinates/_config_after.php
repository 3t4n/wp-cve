<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/locations/edit/view'][] = function( $app, $return, $location )
{
	if( ! ($location['latitude'] && $location['longitude']) ){
		return $return;
	}

	$edit = 0;
	$coordinates_view = $app->make('/locations.coordinates/index/view')
		->render($location, $edit)
		;

	$out = $app->make('/html/list')
		->set_gutter( 2 )
		->add( $coordinates_view )
		->add( $return )
		;

	return $out;
};

$config['after']['/locations/edit/view/layout->menubar'][] = function( $app, $return, $e )
{
// coordinates
	$return['coordinates'] = 
		$app->make('/html/ahref')
			->to('/locations.coordinates/' . $e['id'])
			->add( $app->make('/html/icon')->icon('location') )
			->add( __('Edit Coordinates', 'locatoraid') )
		;

	return $return;
};

$config['after']['/locations/index/view->header'][] = function( $app, $return )
{
	$return['coordinates'] = __('Coordinates', 'locatoraid');
	return $return;
};

$config['after']['/locations/index/view->row'][] = function( $app, $return, $e )
{
	$p = $app->make('/locations.coordinates/presenter');

	$coordinates_view = $p->present_coordinates( $e );
	$geocoding_status = $p->geocoding_status( $e );
	if( $geocoding_status ){
		$coordinates_view = $app->make('/html/ahref')
			->to('/locations.coordinates/' . $e['id'])
			->add( $coordinates_view )
			;
	}

	$return['coordinates'] = $coordinates_view;
	return $return;
};

$config['after']['/locations/commands/update->prepare'][] = function( $app, $return, $id = NULL )
{
	$address_fields = array( 'street1', 'street2', 'city', 'state', 'zip', 'country' );

	$args = array();
	$args[] = $id;
	$args[] = array('select', array_merge(array('id'), $address_fields));
	$command = $app->make('/locations/commands/read');
	$current = $command->execute( $args );

//  check if we have address fields changed
	$changed = FALSE;
	foreach( $return as $k => $v ){
		if( ! array_key_exists($k, $current) ){
			continue;
		}

		if( $v != $current[$k] ){
			$changed = TRUE;
			break;
		}
	}

	if( ! $changed ){
		return $return;
	}

	$return['latitude'] = NULL;
	$return['longitude'] = NULL;

	return $return;
};