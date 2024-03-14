<?php

namespace ShopWP\Factories;

use ShopWP\Hooks;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Hooks_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            $Hooks = new Hooks(
                $plugin_settings,
                Factories\DB\Settings_Syncing_Factory::build()
            );

            self::$instantiated = $Hooks;
        }

        return self::$instantiated;
    }
}
