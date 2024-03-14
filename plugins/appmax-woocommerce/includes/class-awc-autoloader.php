<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Autoloader
{
    /**
     * Path to the includes directory.
     *
     * @var string
     */
    private $include_path = '';

    /**
     * The Constructor.
     */
    public function __construct()
    {
        if (function_exists('__autoload'))
        {
            spl_autoload_register('__autoload');
        }

        spl_autoload_register(array($this, 'autoload'));

        $this->include_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/includes/';
    }

    private function get_file_name_from_class( $class ) {
        return 'class-' . str_replace( '_', '-', $class ) . '.php';
    }

    private function load_file( $path ) {
        if ( $path && is_readable( $path ) ) {
            include_once $path;
            return true;
        }
        return false;
    }

    public function autoload( $class ) {
        $class = strtolower( $class );

        if ( 0 !== strpos( $class, 'awc_' ) ) {
            return;
        }

        $file = $this->get_file_name_from_class( $class );
        $path = '';

        if ( empty( $path ) || ! $this->load_file( $path . $file ) ) {
            $this->load_file( $this->include_path . $file );
        }
    }
}

new WC_Autoloader();