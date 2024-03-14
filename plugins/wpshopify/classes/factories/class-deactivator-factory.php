<?php

namespace ShopWP\Factories;

use ShopWP\Factories;
use ShopWP\Deactivator;

if (!defined('ABSPATH')) {
    exit();
}

class Deactivator_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            $Deactivator = new Deactivator(
                Factories\Compatibility\Manager_Factory::build()
            );

            self::$instantiated = $Deactivator;
        }

        return self::$instantiated;
    }
}
