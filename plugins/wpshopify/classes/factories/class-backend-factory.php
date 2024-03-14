<?php

namespace ShopWP\Factories;

use ShopWP\Backend;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Backend_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

      if (!$plugin_settings) {
         $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
      }

		if (is_null(self::$instantiated)) {

         self::$instantiated = new Backend(
            $plugin_settings,
            Factories\DB\Settings_General_Factory::build(),
            Factories\DB\Products_Factory::build(),
            Factories\DB\Collections_Factory::build(),
            Factories\Data_Bridge_Factory::build($plugin_settings)
         );
		}

		return self::$instantiated;

	}

}
