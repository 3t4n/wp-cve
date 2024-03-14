<?php

namespace ShopWP\Factories\Render\Reviews;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Render\Reviews;
use ShopWP\Factories;

class Reviews_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Reviews(
                Factories\Render\Templates_Factory::build(),
                Factories\Render\Reviews\Defaults_Factory::build($plugin_settings)
            );
        }

        return self::$instantiated;
    }
}
