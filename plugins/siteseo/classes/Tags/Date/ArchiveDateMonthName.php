<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class ArchiveDateMonthName implements GetTagValue {
	const NAME = 'archive_date_month_name';

	public static function getDescription() {
		return __('Month Name Archive Date', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = get_query_var('monthnum');

		if (empty($value)) {
			return '';
		}
		try {
			$date   = DateTime::createFromFormat('!m', $value);

			$value = esc_attr(wp_strip_all_tags(($date->format('F'))));

			return apply_filters('siteseo_get_tag_archive_date_month_name_value', $value, $context);
		} catch (\Exception $e) {
			return apply_filters('siteseo_get_tag_archive_date_month_name_value', '', $context);
		}
	}
}
