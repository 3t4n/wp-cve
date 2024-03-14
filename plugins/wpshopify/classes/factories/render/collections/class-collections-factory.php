<?php

namespace ShopWP\Factories\Render\Collections;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Factories;
use ShopWP\Render\Collections;

class Collections_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Collections(
                Factories\Render\Templates_Factory::build(),
                Factories\Render\Collections\Defaults_Factory::build(
                    $plugin_settings
                )
            );
        }

        return self::$instantiated;
    }
}
