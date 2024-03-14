<?php

namespace ShopWP\Factories\DB;

use ShopWP\DB;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Collections_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB\Collections(
				Factories\DB\Collects_Factory::build(),
				Factories\CPT_Model_Factory::build(),
				Factories\DB\Collections_Smart_Factory::build(),
				Factories\DB\Collections_Custom_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
