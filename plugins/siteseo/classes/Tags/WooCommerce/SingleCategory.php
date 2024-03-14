<?php

namespace SiteSEO\Tags\WooCommerce;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class SingleCategory implements GetTagValue {
	const NAME = 'wc_single_cat';

	public static function getDescription() {
		return __('Product Category', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;

		$value = '';

		if ( ! siteseo_get_service('WooCommerceActivate')->isActive()) {
			return $value;
		}

		if ( ! $context) {
			return $value;
		}

		if (is_singular(['product']) || $context['is_product']) {
			$terms = get_the_terms($context['post']->ID, 'product_cat');

			if ($terms && ! is_wp_error($terms)) {
				$wooSingleCat = [];
				foreach ($terms as $term) {
					$wooSingleCat[$term->term_id] = $term->name;
				}

				$value = stripslashes_deep(wp_filter_nohtml_kses(join(', ', $wooSingleCat)));
			}
		}

		return apply_filters('siteseo_get_tag_wc_single_cat_value', $value, $context);
	}
}
