<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CurrentYear implements GetTagValue {
	const NAME = 'currentyear';

	public static function getDescription() {
		return __('Current Year', 'siteseo');
	}

	public function getValue($args = null) {
		return date('Y');
	}
}
