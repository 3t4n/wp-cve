<?php
/**
 * Autoloader for classes in Watchful plugin.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

/**
 * Autoloader definition.
 *
 * @param string $class_name The name of the class to load.
 *
 * @noinspection PhpInconsistentReturnPointsInspection
 */
function watchful_class_loader( $class_name ) {
	$class_name  = ltrim( $class_name, '\\' );
	$file_name   = '';
	$namespace   = '';
	$last_ns_pos = strripos( $class_name, '\\' );
	if ( ! empty( $last_ns_pos ) ) {
		$namespace  = substr( $class_name, 0, $last_ns_pos );
		$class_name = substr( $class_name, $last_ns_pos + 1 );
	}

	// Replace "watchful" and "Watchful" with "watchful/lib".
	$namespace = str_replace( array( 'watchful', 'Watchful' ), 'watchful' . DIRECTORY_SEPARATOR . 'lib', $namespace );
	$namespace = str_replace( '\\', DIRECTORY_SEPARATOR, $namespace );

	$file_name .= WP_PLUGIN_DIR
	. DIRECTORY_SEPARATOR . $namespace
	. DIRECTORY_SEPARATOR
	. $class_name
	. '.php';

	// Load the class file if it exists.
	if ( file_exists( $file_name ) ) {
		require_once $file_name;
	}

}
