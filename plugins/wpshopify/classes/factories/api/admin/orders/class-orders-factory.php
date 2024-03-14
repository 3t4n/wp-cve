<?php

namespace ShopWP\Factories\API\Admin\Orders;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API\Admin;

class Orders_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Admin\Orders(
                Factories\API\GraphQL_Factory::build(),
                Factories\API\Admin\Orders\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}