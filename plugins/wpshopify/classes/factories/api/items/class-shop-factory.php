<?php

namespace ShopWP\Factories\API\Items;

defined('ABSPATH') ?: die();

use ShopWP\API;
use ShopWP\Factories;

class Shop_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {

        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Items\Shop(
                $plugin_settings,
                Factories\API\Storefront\Shop\Shop_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
