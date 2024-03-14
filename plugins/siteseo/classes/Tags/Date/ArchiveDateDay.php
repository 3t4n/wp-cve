<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class ArchiveDateDay implements GetTagValue {
	const NAME = 'archive_date_day';

	public static function getDescription() {
		return __('Day Archive Date', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = get_query_var('day');

		return apply_filters('siteseo_get_tag_archive_date_day_value', $value, $context);
	}
}
