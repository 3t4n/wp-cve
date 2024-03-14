<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class ArchiveDateYear implements GetTagValue {
	const NAME = 'archive_date_year';

	public static function getDescription() {
		return __('Year Archive Date', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = get_query_var('year');

		return apply_filters('siteseo_get_tag_archive_date_year_value', $value, $context);
	}
}
