<?php

namespace ShopWP\Factories\Processing;

use ShopWP\Processing;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Webhooks_Deletions_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new Processing\Webhooks_Deletions(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\Shopify_API_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
