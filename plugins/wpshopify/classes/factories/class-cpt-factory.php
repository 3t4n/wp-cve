<?php

namespace ShopWP\Factories;

use ShopWP\CPT;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class CPT_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            $CPT = new CPT(
                Factories\DB\Settings_General_Factory::build(),
                $plugin_settings
            );

            self::$instantiated = $CPT;
        }

        return self::$instantiated;
    }
}
