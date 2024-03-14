<?php

namespace Woo_MP;

defined( 'ABSPATH' ) || die;

/**
 * Symbol autoloader.
 */
class Autoloader {

    /**
     * The root namespace (with a trailing backslash).
     *
     * @var string
     */
    private $base_namespace;

    /**
     * The root directory (with a trailing forward slash).
     *
     * @var string
     */
    private $base_directory;

    /**
     * Create a new autoloader.
     *
     * @param string $base_namespace The root namespace (with a trailing backslash).
     * @param string $base_directory The root directory (with a trailing forward slash).
     */
    public function __construct( $base_namespace, $base_directory ) {
        $this->base_namespace = $base_namespace;
        $this->base_directory = $base_directory;
    }

    /**
     * Register the autoloader.
     *
     * @return void
     */
    public function register() {
        spl_autoload_register( [ $this, 'autoload' ] );
    }

    /**
     * Autoloader following the WordPress file naming convention, but without any 'class-' prefixes.
     *
     * @param  string $name The name of the class, interface, or trait to load.
     * @return void
     */
    public function autoload( $name ) {
        if ( strpos( $name, $this->base_namespace ) !== 0 ) {
            return;
        }

        $path = substr( $name, strlen( $this->base_namespace ) );
        $path = str_replace( [ '\\', '_' ], [ '/', '-' ], strtolower( $path ) );
        $path = $this->base_directory . $path . '.php';

        if ( is_readable( $path ) ) {
            require $path;
        }
    }

}
