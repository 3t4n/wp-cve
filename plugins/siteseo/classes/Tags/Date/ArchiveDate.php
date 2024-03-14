<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class ArchiveDate implements GetTagValue {
	const NAME = 'archive_date';

	public static function getDescription() {
		return __('Archive Date', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = sprintf('%s - %s', get_query_var('monthnum'), get_query_var('year'));

		return apply_filters('siteseo_get_tag_archive_date_value', $value, $context);
	}
}
