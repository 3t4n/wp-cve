<?php

namespace ShopWP\Factories;

use ShopWP\Data_Bridge;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Data_Bridge_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new Data_Bridge(
                $plugin_settings,
                Factories\Render\Cart\Defaults_Factory::build($plugin_settings),
                Factories\Render\Collections\Defaults_Factory::build($plugin_settings),
                Factories\Render\Products\Defaults_Factory::build($plugin_settings),
                Factories\Render\Search\Defaults_Factory::build($plugin_settings),
                Factories\Render\Storefront\Defaults_Factory::build($plugin_settings),
                Factories\Render\Translator\Defaults_Factory::build($plugin_settings),
                Factories\Render\Reviews\Defaults_Factory::build($plugin_settings)
            );
        }

        return self::$instantiated;
    }
}
