<?php

namespace ShopWP\Factories\DB;

use ShopWP\DB;

if (!defined('ABSPATH')) {
	exit;
}

class Collects_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {
			self::$instantiated = new DB\Collects();
		}

		return self::$instantiated;

	}

}
