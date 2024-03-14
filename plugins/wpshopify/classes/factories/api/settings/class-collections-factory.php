<?php

namespace ShopWP\Factories\API\Settings;

defined('ABSPATH') ?: die;

use ShopWP\API;
use ShopWP\Factories;

class Collections_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Settings\Collections(
            Factories\DB\Settings_General_Factory::build(),
            Factories\DB\Settings_Syncing_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
