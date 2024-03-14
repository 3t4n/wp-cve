<?php

namespace ShopWP\Factories\API\Admin\Products;

defined('ABSPATH') ?: die();

use ShopWP\Factories;
use ShopWP\API\Admin;

class Products_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Admin\Products(
                Factories\API\GraphQL_Factory::build(),
                Factories\API\Admin\Products\Queries_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
