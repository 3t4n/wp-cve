<?php

namespace ShopWP\Factories\DB;

use ShopWP\DB;

if (!defined('ABSPATH')) {
	exit;
}

class Settings_Connection_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB\Settings_Connection();

		}

		return self::$instantiated;

	}

}
