<?php

namespace SiteSEO\Tags\Schema\SocialAccount;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class Instagram implements GetTagValue {
	const NAME = 'social_account_instagram';

	public static function getDescription() {
		return __('Instagram URL', 'siteseo');
	}

	/**
	 * @since 4.5.0
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;

		$value   = siteseo_get_service('SocialOption')->getSocialAccountsInstagram();

		return apply_filters('siteseo_get_tag_schema_social_account_instagram', $value, $context);
	}
}
