<?php

namespace ShopWP\Factories;

use ShopWP\Factories;
use ShopWP\Shopify_API;

if (!defined('ABSPATH')) {
	exit;
}

class Shopify_API_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Shopify_API(
				Factories\DB\Settings_Connection_Factory::Build()
			);

		}

		return self::$instantiated;

	}

}
