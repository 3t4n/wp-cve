<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CurrentTime implements GetTagValue {
	const NAME = 'currenttime';

	public static function getDescription() {
		return __('Current Time', 'siteseo');
	}

	public function getValue($args = null) {
		return current_time(get_option('time_format'));
	}
}
