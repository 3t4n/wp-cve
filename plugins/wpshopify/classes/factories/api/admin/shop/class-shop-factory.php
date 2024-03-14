<?php

namespace ShopWP\Factories\API\Admin\Shop;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API\Admin;

class Shop_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Admin\Shop(
                Factories\API\GraphQL_Factory::build(),
                Factories\API\Admin\Shop\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}