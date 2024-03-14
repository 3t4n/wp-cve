<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/locations/commands/read->args'][] = function( $app, $return )
{
// return $return;
	$my_return = array();
	$my_return[] = array('sort', 'priority', 'desc');
	$return = array_merge( $my_return, $return );
	return $return;
};

$config['after']['/locations/presenter->present_front'][] = function( $app, $return )
{
	if( isset($return['priority']) && (! $return['priority']) ){
		unset($return['priority']);
	}
	return $return;
};

$config['after']['/locations/presenter->fields'][] = function( $app, $return )
{
	$return['priority'] = __('Priority', 'locatoraid');
	return $return;
};

$config['after']['/locations/form'][] = function( $app, $return )
{
	$options = $app->make('/priority/presenter')
		->present_options()
		;

	$return['priority'] = array(
		'input'	=> $app->make('/form/radio')
			->set_options( $options ),
		'label'	=> __('Priority', 'locatoraid'),
		);
	return $return;
};

$config['after']['/locations/index/view->row'][] = function( $app, $return, $e )
{
	$app_settings = $app->make('/app/settings');
	$this_field_pname = 'fields:' . 'priority'  . ':use';
	$this_field_conf = $app_settings->get($this_field_pname);
	if( ! $this_field_conf ){
		return $return;
	}

	// featured
	if( $e['priority'] == 1 ){
		$return['title'] = $app->make('/html/element')->tag('div')
			->add( $return['title'] )
			->add_attr('class', 'hc-p2')
			->add_attr('class', 'hc-border')
			->add_attr('class', 'hc-border-olive')
			->add_attr('class', 'hc-rounded')
			;
	}

	// draft
	if( $e['priority'] == -1 ){
		$return['title'] = $app->make('/html/element')->tag('div')
			->add( __('Draft', 'locatoraid') )
			->add( $return['title'] )
			->add_attr('class', 'hc-p2')
			->add_attr('class', 'hc-border')
			->add_attr('class', 'hc-border-gray')
			->add_attr('class', 'hc-rounded')
			->add_attr('class', 'hc-muted1')
			;
	}

	return $return;
};