<?php

namespace ShopWP\Factories\API\Storefront\Shop;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API\Storefront;

class Shop_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Storefront\Shop(
				Factories\API\GraphQL_Factory::build(),
				Factories\API\Storefront\Shop\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}