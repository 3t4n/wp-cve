<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class CategoryTitle implements GetTagValue {
	const NAME = '_category_title';

	public static function getDescription() {
		return __('Category Title', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';

		if (null !== $context['term_id']) {
			$value = get_term_field('name', $context['term_id']);
			if (is_wp_error($value)) {
				$value = '';
			}
		} else {
			$value = single_cat_title('', false);
		}

		return apply_filters('siteseo_get_tag_category_title_value', $value, $context);
	}
}
