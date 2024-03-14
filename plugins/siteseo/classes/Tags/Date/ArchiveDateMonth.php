<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class ArchiveDateMonth implements GetTagValue {
	const NAME = 'archive_date_month';

	public static function getDescription() {
		return __('Month Archive Date', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = get_query_var('monthnum');

		return apply_filters('siteseo_get_tag_archive_date_month_value', $value, $context);
	}
}
