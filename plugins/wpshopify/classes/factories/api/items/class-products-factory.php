<?php

namespace ShopWP\Factories\API\Items;

defined('ABSPATH') ?: die();

use ShopWP\API;
use ShopWP\Factories;

class Products_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {

        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new API\Items\Products(
                Factories\DB\Settings_General_Factory::build(),
                Factories\DB\Settings_Syncing_Factory::build(),
                Factories\DB\Tags_Factory::build(),
                Factories\DB\Products_Factory::build(),
                Factories\Shopify_API_Factory::build(),
                Factories\Processing\Products_Factory::build(),
                Factories\Processing\Variants_Factory::build(),
                Factories\Processing\Tags_Factory::build(),
                Factories\Processing\Options_Factory::build(),
                Factories\Processing\Images_Factory::build(),
                Factories\API\Admin\Variants\Variants_Factory::build(),
                Factories\API\Syncing\Counts_Factory::build(),
                $plugin_settings,
                Factories\Processing\Database_Factory::build($plugin_settings),
                Factories\API\Syncing\Status_Factory::build(),
                Factories\API\Admin\Shop\Shop_Factory::build(),
                Factories\API\Storefront\Products\Products_Factory::build(),
                Factories\API\Items\Collections_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
