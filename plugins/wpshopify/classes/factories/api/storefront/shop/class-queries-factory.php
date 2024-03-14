<?php

namespace ShopWP\Factories\API\Storefront\Shop;

defined('ABSPATH') ?: die;

use ShopWP\API;

class Queries_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Storefront\Shop\Queries();

		}

		return self::$instantiated;

	}

}