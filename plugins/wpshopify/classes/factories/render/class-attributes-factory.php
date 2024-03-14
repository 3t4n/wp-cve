<?php

namespace ShopWP\Factories\Render;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Render\Attributes;
use ShopWP\Factories;

class Attributes_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Attributes(
                Factories\DB\Products_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
