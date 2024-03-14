<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class TermDescription implements GetTagValue {
	const NAME = 'term_description';

	public static function getDescription() {
		return __('Term Description', 'siteseo');
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
			$value   = term_description();
		}

		$value   = wp_trim_words(
			stripslashes_deep(
				wp_filter_nohtml_kses($value)
			), siteseo_get_service('TagsToString')->getExcerptLengthForTags()
		);

		return apply_filters('siteseo_get_tag_term_description_value', $value, $context);
	}
}
