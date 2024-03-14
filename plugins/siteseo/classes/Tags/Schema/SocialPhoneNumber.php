<?php

namespace SiteSEO\Tags\Schema;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class SocialPhoneNumber implements GetTagValue
{
	const NAME = 'social_phone_number';

	/**
	 * @since 4.5.0
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;

		$value   = siteseo_get_service('SocialOption')->getSocialKnowledgePhone();

		return apply_filters('siteseo_get_tag_schema_social_phone_number', $value, $context);
	}
}
