<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CurrentDate implements GetTagValue {
	const NAME = 'currentdate';

	public static function getDescription() {
		return __('Current Date', 'siteseo');
	}

	public function getValue($args = null) {
		return date_i18n(get_option('date_format'));
	}
}
