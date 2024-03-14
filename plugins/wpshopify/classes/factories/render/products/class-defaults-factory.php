<?php

namespace ShopWP\Factories\Render\Products;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Factories;
use ShopWP\Render\Products\Defaults;

class Defaults_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (empty($plugin_settings)) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new Defaults(
                $plugin_settings,
                Factories\Render\Attributes_Factory::build($plugin_settings)
            );
        }

        return self::$instantiated;
    }
}
