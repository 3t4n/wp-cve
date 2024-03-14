<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class PostUrl implements GetTagValue {
	const NAME = 'post_url';

	public static function getDescription() {
		return __('Post URL', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';
		if ( ! $context) {
			return $value;
		}

		if ($context['is_single'] && ! empty($context['post'])) {
			$value = esc_url(get_permalink($context['post']));
		}

		return apply_filters('siteseo_get_tag_post_url_value', $value, $context);
	}
}
