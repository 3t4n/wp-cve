<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class SiteTagline implements GetTagValue {
	const NAME = 'tagline';

	const ALIAS = ['sitedesc'];

	/**
	 * 4.8.0.
	 *
	 * @return string
	 */
	public static function getDescription() {
		return __('Site Tagline', 'siteseo');
	}

	public function getValue($args = null) {
		return get_bloginfo('description');
	}
}
