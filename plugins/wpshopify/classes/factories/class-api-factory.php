<?php

namespace ShopWP\Factories;

defined('ABSPATH') ?: die();

use ShopWP\API;
use ShopWP\Factories;

class API_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API(
                Factories\DB\Settings_Syncing_Factory::build(),
                $plugin_settings
            );
        }
        return self::$instantiated;
    }
}
