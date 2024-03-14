<?php

namespace ShopWP\Factories\Render\Cart;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Render\Cart\Defaults;
use ShopWP\Factories;

class Defaults_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Defaults(
                $plugin_settings,
                Factories\Render\Attributes_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
