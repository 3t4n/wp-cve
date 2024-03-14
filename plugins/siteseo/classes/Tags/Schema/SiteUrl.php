<?php

namespace SiteSEO\Tags\Schema;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class SiteUrl implements GetTagValue {
	const NAME = 'siteurl';

	public static function getDescription() {
		return __('Site URL', 'siteseo');
	}

	public function getValue($args = null) {
		$value = site_url();

		return apply_filters('siteseo_get_tag_site_url_value', $value);
	}
}
