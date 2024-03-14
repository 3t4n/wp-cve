<?php

namespace ShopWP\Factories\DB;

if (!defined('ABSPATH')) {
	exit;
}

use ShopWP\DB;

class Settings_General_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new DB\Settings_General();

		}

		return self::$instantiated;

	}

}
