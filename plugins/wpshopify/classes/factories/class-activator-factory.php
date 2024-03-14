<?php

namespace ShopWP\Factories;

use ShopWP\Activator;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Activator_Factory
{
    protected static $instantiated = null;

    public static function build()
    {
        if (is_null(self::$instantiated)) {
            self::$instantiated = new Activator(
                Factories\DB\Settings_Connection_Factory::build(),
                Factories\DB\Settings_General_Factory::build(),
                Factories\DB\Settings_License_Factory::build(),
                Factories\DB\Products_Factory::build(),
                Factories\DB\Variants_Factory::build(),
                Factories\DB\Collects_Factory::build(),
                Factories\DB\Options_Factory::build(),
                Factories\DB\Collections_Custom_Factory::build(),
                Factories\DB\Collections_Smart_Factory::build(),
                Factories\DB\Images_Factory::build(),
                Factories\DB\Tags_Factory::build(),
                Factories\DB\Settings_Syncing_Factory::build(),
                Factories\Processing\Database_Factory::build(),
                Factories\Compatibility\Manager_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
