<?php

namespace ShopWP\Factories\API\Items;

defined('ABSPATH') ?: die;

use ShopWP\API;
use ShopWP\Factories;

class Cart_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Items\Cart(
				$plugin_settings,
				Factories\API\Storefront\Cart\Cart_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
