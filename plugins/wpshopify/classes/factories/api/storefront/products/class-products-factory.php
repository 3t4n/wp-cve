<?php

namespace ShopWP\Factories\API\Storefront\Products;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API\Storefront;

class Products_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Storefront\Products(
				Factories\API\GraphQL_Factory::build(),
				Factories\API\Storefront\Products\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}