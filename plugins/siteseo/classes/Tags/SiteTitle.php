<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class SiteTitle implements GetTagValue {
	const NAME = 'sitetitle';

	const ALIAS = ['sitename'];

	/**
	 * 4.8.0.
	 *
	 * @return string
	 */
	public static function getDescription() {
		return __('Site Title', 'siteseo');
	}

	public function getValue($args = null) {
		return get_bloginfo('name');
	}
}
