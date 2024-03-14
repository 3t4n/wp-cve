<?php

namespace ShopWP\Factories\Compatibility;

defined('ABSPATH') ?: exit();

use ShopWP\Factories;
use ShopWP\Compatibility\Manager;

class Manager_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Manager(
                Factories\Filesystem\Filesystem_Factory::build(),
                Factories\DB\Settings_General_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
