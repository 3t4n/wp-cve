<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class TargetKeyword implements GetTagValue {
	const NAME = 'target_keyword';

	public static function getDescription() {
		return __('Target Keywords', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;

		$value   = '';
		if (isset($context['post']->ID)) {
			$value = get_post_meta($context['post']->ID, '_siteseo_analysis_target_kw', true);
		}

		return apply_filters('siteseo_get_tag_target_keyword_value', $value, $context);
	}
}
