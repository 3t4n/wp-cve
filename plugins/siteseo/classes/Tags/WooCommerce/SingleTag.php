<?php

namespace SiteSEO\Tags\WooCommerce;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class SingleTag implements GetTagValue {
	const NAME = 'wc_single_tag';

	public static function getDescription() {
		return __('Product Tag', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		if ( ! siteseo_get_service('WooCommerceActivate')->isActive()) {
			return '';
		}

		$value = '';

		if ( ! $context) {
			return $value;
		}

		if (is_singular(['product']) || $context['is_product']) {
			$terms = get_the_terms($context['post']->ID, 'product_tag');

			if ($terms && ! is_wp_error($terms)) {
				$singleTag = [];

				foreach ($terms as $term) {
					$singleTag[$term->term_id] = $term->name;
				}

				$value = stripslashes_deep(wp_filter_nohtml_kses(join(', ', $singleTag)));
			}
		}

		return apply_filters('siteseo_get_tag_wc_single_tag_value', $value, $context);
	}
}
