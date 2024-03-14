<?php

namespace ShopWP\Factories\DB;

use ShopWP\DB;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Variants_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB\Variants(
				Factories\DB\Settings_Connection_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
