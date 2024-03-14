<?php

namespace SiteSEO\Tags\Date;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class PostModifiedDate implements GetTagValue {
	const NAME = 'post_modified_date';

	public static function getDescription() {
		return __('Post Modified Date', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';

		if (isset($context['post'])) {
			$value = get_the_modified_date(get_option('date_format'), $context['post']->ID);
		}

		return apply_filters('siteseo_get_tag_post_modified_date_value', $value, $context);
	}
}
