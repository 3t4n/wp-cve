<?php

namespace ShopWP\Factories\Processing;

use ShopWP\Processing;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
	exit;
}

class Media_Uploader_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = $Async_Processing_Media_Uploader = new Processing\Media_Uploader(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\DB\Images_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
