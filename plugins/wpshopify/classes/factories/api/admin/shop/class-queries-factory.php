<?php

namespace ShopWP\Factories\API\Admin\Shop;

defined('ABSPATH') ?: die;

use ShopWP\API;

class Queries_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Admin\Shop\Queries();

		}

		return self::$instantiated;

	}

}