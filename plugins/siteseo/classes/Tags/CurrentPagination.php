<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CurrentPagination implements GetTagValue {
	const NAME = 'current_pagination';

	public static function getDescription() {
		return __('Current Number Page', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';

		if ( ! $context) {
			return $value;
		}

		if ($context['paged'] > '1') {
			$value = $context['paged'];
		}

		return apply_filters('siteseo_get_tag_current_pagination_value', $value, $context);
	}
}
