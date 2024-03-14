<?php

namespace ShopWP\Factories\API\Misc;

defined('ABSPATH') ?: die();

use ShopWP\Factories;
use ShopWP\API;

class Notices_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Misc\Notices(
                $plugin_settings,
                Factories\DB\Settings_General_Factory::build(),
                Factories\Backend_Factory::build($plugin_settings),
                Factories\DB\Settings_Syncing_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
