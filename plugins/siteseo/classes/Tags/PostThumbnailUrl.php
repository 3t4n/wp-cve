<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class PostThumbnailUrl implements GetTagValue {
	const NAME = 'post_thumbnail_url';

	public static function getDescription() {
		return __('Post Thumbnail URL', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';

		if ( ! $context) {
			return $value;
		}

		if (isset($context['is_single'], $context['post']) && $context['is_single'] && ! empty($context['post'])) {
			$value = get_the_post_thumbnail_url($context['post'], 'full');
		}

		return apply_filters('siteseo_get_tag_post_thumbnail_url_value', $value, $context);
	}
}
