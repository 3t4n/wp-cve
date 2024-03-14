<?php
namespace PHPF\WP\Core;

/**
 * Framework initialization
 *
 * @author Petr Stastny <petr@stastny.eu>
 */
final class Init
{
    /**
     * Framework initialization
     *
     * @return void
     */
    public static function bootstrap()
    {
        // common includes
        require_once __DIR__.'/Validator.php';
        require_once __DIR__.'/Autoload.php';

        Autoload::init();
    }
}
