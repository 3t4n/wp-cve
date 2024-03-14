<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class Page implements GetTagValue {
	const NAME = 'page';

	public static function getDescription() {
		return __('Page number with context', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		global $wp_query;

		$value = '';

		if ( ! $context) {
			return $value;
		}

		if (isset($wp_query->max_num_pages)) {
			if ($context['paged'] > 1) {
				$currentPage = get_query_var('paged');
			} else {
				$currentPage = 1;
			}

			$value = sprintf(__('Page %d of %2$d', 'siteseo'), $currentPage, $wp_query->max_num_pages);
		}

		return apply_filters('siteseo_get_tag_page_value', $value, $context);
	}
}
