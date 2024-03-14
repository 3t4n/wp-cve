<?php

namespace ShopWP\Factories\API\Settings;

defined('ABSPATH') ?: die();

use ShopWP\API;
use ShopWP\Factories;

class License_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Settings\License(
                Factories\DB\Settings_License_Factory::build(),
                Factories\HTTP_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
