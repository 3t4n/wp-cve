<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CurrentDay implements GetTagValue {
	const NAME = 'currentday';

	public static function getDescription() {
		return __('Current Day', 'siteseo');
	}

	public function getValue($args = null) {
		return date_i18n('j');
	}
}
