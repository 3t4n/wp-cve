<?php

namespace ShopWP\Factories;

use ShopWP\Webhooks;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Webhooks_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Webhooks(
                $plugin_settings,
                Factories\Template_Loader_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
