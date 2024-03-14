<?php

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	die( 'direct access aborted' );
}

if ( function_exists( 'pwc_autoload' ) && is_callable( 'pwc_autoload' ) ) {
	spl_autoload_register( 'pwc_autoload' );
}

function pwc_autoload( $class_name )
{
	$namespace = 'Payamito\Woocommerce';
	if ( 0 !== strpos( $class_name, $namespace ) ) {
		return;
	}

	$class_name = str_replace( $namespace, '', $class_name );
	$class_name = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );

	$path = PAYAMITO_WC_DIR . 'class-' . $class_name . '.php';
	if ( is_file( $path ) ) {
		include_once $path;
	}
}
