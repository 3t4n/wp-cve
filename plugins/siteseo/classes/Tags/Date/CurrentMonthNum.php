<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CurrentMonthNum implements GetTagValue {
	const NAME = 'currentmonth_num';

	public static function getDescription() {
		return __('Current Month Number', 'siteseo');
	}

	public function getValue($args = null) {
		return date_i18n('n');
	}
}
