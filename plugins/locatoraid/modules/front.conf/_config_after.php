<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/app.conf/form'][] = function( $app, $return )
{
	$return['core:measure'] = array(
		'input'	=> $app->make('/form/radio')
			->set_options( 
				array(
					'mi'	=> __('Miles', 'locatoraid'),
					'km'	=> __('Km', 'locatoraid'),
					)
			),
		'label'	=> __('Measure Units', 'locatoraid'),
		);
	$return['front:links_new_window'] = array(
		'input'	=> $app->make('/form/checkbox')
			,
		'label'	=> __('Open Links In New Window', 'locatoraid'),
		);
	return $return;
};

$config['after']['/conf/view/layout->tabs'][] = function( $app, $return )
{
	$return['fields'] = array( 'front.conf/fields', __('Locations Details', 'locatoraid') );
	$return['front-map'] = array( 'front.conf/map', __('Details On Map', 'locatoraid') );
	$return['front-list'] = array( 'front.conf/list', __('Details In List', 'locatoraid') );
	$return['front-text'] = array( 'front.conf/text', __('Front Text', 'locatoraid') );
	return $return;
};

$config['after']['/app/settings->get'][] = function( $app, $return, $pname )
{
	switch( $pname ){
		case 'front_list:template':
			$app_settings = $app->make('/app/settings');
			$advanced = $app_settings->get('front_list:advanced');
			if( (! $advanced) OR (! strlen($return)) ){
				$return = $app->make('/front/view/list/template')
					->render()
					;
			}
			break;

		case 'front_map:template':
			$app_settings = $app->make('/app/settings');
			$advanced = $app_settings->get('front_map:advanced');
			if( (! $advanced) OR (! strlen($return)) ){
				$return = $app->make('/front/view/map/template')
					->render()
					;
			}
			break;
	}

	return $return;
};