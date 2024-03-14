<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/form/helper->render'][] = function( $app, $return )
{
	$security = $app->make('/security/lib');

	$csrf_name = $security->get_csrf_token_name();
	$csrf_value = $security->get_csrf_hash();

	if( strlen($csrf_name) && strlen($csrf_value) ){
		$hidden = $app->make('/form/hidden')
			->render( $csrf_name, $csrf_value )
			;

		$return->add(
			$app->make('/html/element')->tag('div')
				->add_attr('style', 'display:none')
				->add( $hidden )
			);
	}

	return $return;
};