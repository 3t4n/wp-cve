<?php

namespace ShopWP\Factories\API\Admin\Variants;

defined('ABSPATH') ?: die();

use ShopWP\Factories;
use ShopWP\API\Admin;

class Variants_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Admin\Variants(
                Factories\API\GraphQL_Factory::build(),
                Factories\API\Admin\Variants\Queries_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
