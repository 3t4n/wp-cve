<?php

namespace ShopWP\Factories;

use ShopWP\Transients;

if (!defined('ABSPATH')) {
	exit;
}

class Transients_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			$Transients = new Transients();

			self::$instantiated = $Transients;

		}

		return self::$instantiated;

	}

}
