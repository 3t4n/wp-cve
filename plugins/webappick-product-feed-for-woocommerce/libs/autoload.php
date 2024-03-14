<?php
/*
 * Autoloading file & classes
 *
 * @since 4.7
 *
 * */

defined( 'ABSPATH' ) || die();

spl_autoload_register( 'woo_feed_autoloader' );

function woo_feed_autoloader( $class ) {

    if ( strpos( $class, 'WebAppick' ) !== false ) {

        $file_path = __DIR__ . DIRECTORY_SEPARATOR
            . str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';

        $file_secondary_path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'includes' .
            DIRECTORY_SEPARATOR . str_replace( [ 'WebAppick\Feed', '\\' ], [ '', DIRECTORY_SEPARATOR ], $class ) . '.php';

        if ( file_exists( $file_path ) ) {
            require_once $file_path;
        } elseif ( file_exists( $file_secondary_path ) ) {
            require_once( $file_secondary_path );
        }    
}

}