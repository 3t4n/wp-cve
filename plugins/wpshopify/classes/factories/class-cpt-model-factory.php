<?php

namespace ShopWP\Factories;

use ShopWP\CPT_Model;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class CPT_Model_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new CPT_Model();
        }

        return self::$instantiated;
    }
}
