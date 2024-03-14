<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class PostCategory implements GetTagValue {
	const NAME = 'post_category';

	public static function getDescription() {
		return __('Post Category', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';

		if ( ! $context) {
			return $value;
		}

		if ($context['is_single'] && $context['has_category'] && isset($context['post']->ID)) {
			$terms			   = get_the_terms($context['post']->ID, 'category');
			$value			   = $terms[0]->name;
		}

		return apply_filters('siteseo_get_tag_post_category_value', $value, $context);
	}
}
