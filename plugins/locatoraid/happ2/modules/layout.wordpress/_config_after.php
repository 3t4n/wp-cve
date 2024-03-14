<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/app/enqueuer'][] = function( $app, $enqueuer )
{
	$enqueuer
		->enqueue_style( 'hc' )
		;
};

$config['after']['/app/enqueuer->register_script'][] = function( $app, $handle, $path )
{
	$wp_handle = 'hc2-script-' . $handle;
	$path = $app->make('/layout.wordpress/path')
		->full_path( $path )
		;
	wp_register_script( $wp_handle, $path, array('jquery') );
};

$config['after']['/app/enqueuer->register_style'][] = function( $app, $handle, $path )
{
	$skip = array('reset', 'style', 'form', 'font', 'hc-start');
	if( in_array($handle, $skip) ){
		return;
	}

	$wp_handle = 'hc2-style-' . $handle;
	$path = $app->make('/layout.wordpress/path')
		->full_path( $path )
		;

	$pos = strpos( $path, '?hcver=' );
	if( FALSE !== $pos ){
		list( $path, $ver ) = explode( '?hcver=', $path );
		wp_register_style( $wp_handle, $path, array(), $ver );
	}
	else {
		wp_register_style( $wp_handle, $path );
	}
};

$config['after']['/app/enqueuer->enqueue_script'][] = function( $app, $handle )
{
	$wp_handle = 'hc2-script-' . $handle;
// echo "ENQUEUEWP '$wp_handle'<br>";
	wp_enqueue_script( $wp_handle );
};

$config['after']['/app/enqueuer->enqueue_style'][] = function( $app, $handle )
{
	$wp_handle = 'hc2-style-' . $handle;
	wp_enqueue_style( $wp_handle );
};

$config['after']['/app/enqueuer->localize_script'][] = function( $app, $handle, $params )
{
	$wp_handle = 'hc2-script-' . $handle;
	$js_var = 'hc2_' . $handle . '_vars'; 
	wp_localize_script( $wp_handle, $js_var, $params );
};

$config['after']['/layout/view/body'][] = function( $app )
{
	$enqueuer = $app->make('/app/enqueuer');
	return;
};
