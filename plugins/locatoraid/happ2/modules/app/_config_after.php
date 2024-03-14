<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/app/enqueuer'][] = function( $app, $enqueuer )
{
	$ver = defined( 'LC3_VERSION' ) ? LC3_VERSION : 2;

	$enqueuer
		->register_script( 'hc', 'happ2/assets/js/hc2.js?hcver=' . $ver )

		->register_style( 'hc-start', 'happ2/assets/css/hc-start.css?hcver=' . $ver )
		->register_style( 'hc', 'happ2/assets/css/hc.css?hcver=' . $ver )
		->register_style( 'font', 'https://fonts.googleapis.com/css?family=PT+Sans' )
		;

	// enqueue
		$enqueuer
			->enqueue_script( 'hc' )
			;
};