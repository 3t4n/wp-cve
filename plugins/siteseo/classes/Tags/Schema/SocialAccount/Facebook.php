<?php

namespace SiteSEO\Tags\Schema\SocialAccount;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class Facebook implements GetTagValue {
	const NAME = 'social_account_facebook';

	public static function getDescription() {
		return __('Facebook URL', 'siteseo');
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

		$value   = siteseo_get_service('SocialOption')->getSocialAccountsFacebook();

		return apply_filters('siteseo_get_tag_schema_social_account_facebook', $value, $context);
	}
}
