<?php

namespace ShopWP\Factories;

use ShopWP\Templates;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Templates_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            $Templates = new Templates(
                Factories\Template_Loader_Factory::build(),
                $plugin_settings
            );

            self::$instantiated = $Templates;
        }

        return self::$instantiated;
    }
}
