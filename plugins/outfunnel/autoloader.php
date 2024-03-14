<?php
namespace Outfunnel;

class Autoloader {
    public static $loader;

    public static function init()
    {
        if (self::$loader == NULL)
            self::$loader = new self();

        return self::$loader;
    }

    private function __construct() {
        spl_autoload_register( [ $this, 'autoload_outfunnel' ] );
    }

    /**
     * @param String $class
     * @return void
     */
    public static function autoload_outfunnel($class) {
        if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
            return;
        }

        $filename = strtolower(
            preg_replace(
                [ '/'. __NAMESPACE__ . '/', '/^\\\\/', '/\\\/'],
                [ '', '', DIRECTORY_SEPARATOR],
                $class
            )
        );

        $directory = plugin_dir_path( __FILE__ );
        $path = $directory . $filename . '.php';

        if (file_exists($path)) {
            include($path);
        }
	}
}
