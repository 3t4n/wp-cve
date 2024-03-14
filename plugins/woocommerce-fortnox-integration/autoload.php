<?php

/**
 * PSR-4 compliant autoloader
 *
 * @param $path
 */
if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

spl_autoload_register( function( $class ) {

    $parts = explode('\\', $class);
    $filename = end( $parts );
    $name = 'class-' . strtolower( str_replace( array( '\\', '_' ), '-', $filename ) );
    $path_array = explode('\\', $class);
    array_pop( $path_array );
    $path = implode('/', $path_array );

    $file = plugin_dir_path( __FILE__ ) . str_replace( "\\", "/", $path . '/' . $name ) . ".php";

    if( ! file_exists( $file ) )
        return;

    require_once $file;
} );
