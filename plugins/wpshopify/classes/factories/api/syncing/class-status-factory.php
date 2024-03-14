<?php

namespace ShopWP\Factories\API\Syncing;

defined('ABSPATH') ?: die();

use ShopWP\API;
use ShopWP\Factories;

class Status_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Syncing\Status(
                Factories\DB\Settings_Syncing_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
