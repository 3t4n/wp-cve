<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/front/view'][] = function( $app, $return )
{
	$app_settings = $app->make('/app/settings');

	$this_pname = 'fields:directions:use';
	$this_pname_config = $app_settings->get($this_pname);
	if( ! $this_pname_config ){
		return $return;
	}

	$app->make('/app/enqueuer')
		->register_script( 'lc-directions-front', 'modules/directions.front/assets/js/directions.js?hcver=' . LC3_VERSION )
		->enqueue_script( 'lc-directions-front' )
		;

	return $return;
};