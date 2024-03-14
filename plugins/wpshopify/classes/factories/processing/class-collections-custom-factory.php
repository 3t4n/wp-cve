<?php

namespace ShopWP\Factories\Processing;

use ShopWP\Processing;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Collections_Custom_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Processing\Collections_Custom(
                Factories\DB\Settings_Syncing_Factory::build(),
                Factories\DB\Collections_Factory::build(),
                Factories\CPT_Model_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
