<?php

namespace ShopWP\Factories\API\Storefront\Cart;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API\Storefront;

class Cart_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Storefront\Cart(
				Factories\API\GraphQL_Factory::build(),
				Factories\API\Storefront\Cart\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}