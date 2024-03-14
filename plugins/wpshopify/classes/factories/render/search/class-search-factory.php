<?php

namespace ShopWP\Factories\Render\Search;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Render\Search;
use ShopWP\Factories;

class Search_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Search(
                Factories\Render\Templates_Factory::build(),
                Factories\Render\Search\Defaults_Factory::build(
                    $plugin_settings
                )
            );
        }

        return self::$instantiated;
    }
}
