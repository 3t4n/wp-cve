<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CurrentShortMonth implements GetTagValue {
	const NAME = 'currentmonth_short';

	public static function getDescription() {
		return __('Current Month in 3 letters', 'siteseo');
	}

	public function getValue($args = null) {
		return date_i18n('M');
	}
}
