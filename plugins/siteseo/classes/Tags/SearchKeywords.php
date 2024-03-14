<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class SearchKeywords implements GetTagValue {
	const NAME = 'search_keywords';

	public static function getDescription() {
		return __('Search Keywords', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = get_search_query();

		if ( ! empty($value)) {
			$value = esc_attr('"' . $value . '"');
		} else {
			$value = esc_attr('" "');
		}

		return apply_filters('siteseo_get_tag_search_keywords_value', $value, $context);
	}
}
