<?php

namespace ShopWP\Factories\Render\Storefront;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Render\Storefront;
use ShopWP\Factories;

class Storefront_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Storefront(
                Factories\Render\Templates_Factory::build(),
                Factories\Render\Storefront\Defaults_Factory::build(
                    $plugin_settings
                )
            );
        }

        return self::$instantiated;
    }
}
