<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class AuthorWebsite implements GetTagValue {
	const NAME = 'author_website';

	public static function getDescription() {
		return __('Author Website', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';

		if ( ! $context) {
			return $value;
		}

		if ($context['is_single'] && isset($context['post']->post_author)) {
			$value	  = get_the_author_meta('url', $context['post']->post_author);
		}

		if ($context['is_author'] && is_int(get_queried_object_id())) {
			$user_info = get_userdata(get_queried_object_id());

			if (isset($user_info)) {
				$value = $user_info->url;
			}
		}

		$value = esc_attr($value);

		return apply_filters('siteseo_get_tag_author_website_value', $value, $context);
	}
}
