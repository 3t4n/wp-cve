<?php

namespace ShopWP\Factories\Processing;

use ShopWP\Processing;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Collects_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Processing\Collects(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Collects_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
