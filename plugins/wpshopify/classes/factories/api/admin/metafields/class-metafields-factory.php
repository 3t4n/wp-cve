<?php

namespace ShopWP\Factories\API\Admin\Metafields;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API\Admin;

class Metafields_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Admin\Metafields(
                Factories\API\GraphQL_Factory::build(),
                Factories\API\Admin\Metafields\Queries_Factory::build()
			);

		}

		return self::$instantiated;

	}

}