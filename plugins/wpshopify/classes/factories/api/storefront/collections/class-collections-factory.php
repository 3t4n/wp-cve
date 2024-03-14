<?php

namespace ShopWP\Factories\API\Storefront\Collections;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API\Storefront;

class Collections_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Storefront\Collections(
				Factories\API\GraphQL_Factory::build(),
				Factories\API\Storefront\Collections\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}