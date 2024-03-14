<?php

namespace ShopWP\Factories\Render;

defined('ABSPATH') ?: die();

use ShopWP\Render\Root;
use ShopWP\Factories;

class Root_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Root(
                Factories\Template_Loader_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
