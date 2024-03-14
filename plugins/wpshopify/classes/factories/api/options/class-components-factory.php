<?php

namespace ShopWP\Factories\API\Options;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\API;
use ShopWP\Factories;

class Components_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Options\Components(
                Factories\Template_Loader_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
