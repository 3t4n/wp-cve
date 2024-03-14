<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CurrentMonth implements GetTagValue {
	const NAME = 'currentmonth';

	public static function getDescription() {
		return __('Current Month', 'siteseo');
	}

	public function getValue($args = null) {
		return date_i18n('F');
	}
}
