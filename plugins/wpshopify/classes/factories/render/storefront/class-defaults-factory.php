<?php

namespace ShopWP\Factories\Render\Storefront;

defined('ABSPATH') ?: exit();

use ShopWP\Render\Storefront\Defaults;
use ShopWP\Factories;

class Defaults_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Defaults(
                Factories\Render\Attributes_Factory::build(),
                Factories\Render\Products\Defaults_Factory::build(
                    $plugin_settings
                )
            );
        }

        return self::$instantiated;
    }
}
