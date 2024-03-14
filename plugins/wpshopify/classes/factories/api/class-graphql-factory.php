<?php

namespace ShopWP\Factories\API;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API;

class GraphQL_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\GraphQL(
                Factories\DB\Settings_Connection_Factory::build()
			);

		}

		return self::$instantiated;

	}

}