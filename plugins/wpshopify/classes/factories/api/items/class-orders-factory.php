<?php

namespace ShopWP\Factories\API\Items;

defined('ABSPATH') ?: die();

use ShopWP\API;
use ShopWP\Factories;

class Orders_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Items\Orders(
                $plugin_settings,
                Factories\Shopify_API_Factory::build(),
                Factories\API\Admin\Orders\Orders_Factory::build(),
            );
        }

        return self::$instantiated;
    }
}
