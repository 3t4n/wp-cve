<?php

namespace ShopWP\Factories\Processing;

use ShopWP\Processing;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Collections_Smart_Collects_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Processing\Collections_Smart_Collects(
                Factories\DB\Settings_Syncing_Factory::build(),
                Factories\DB\Collects_Factory::build(),
                Factories\Shopify_API_Factory::build(),
                Factories\DB\Settings_General_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
