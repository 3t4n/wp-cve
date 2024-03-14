<?php

namespace ShopWP\Factories\Render\Translator;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Render\Translator;
use ShopWP\Factories;

class Translator_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Translator(
                Factories\Render\Templates_Factory::build(),
                Factories\Render\Translator\Defaults_Factory::build($plugin_settings)
            );
        }

        return self::$instantiated;
    }
}
