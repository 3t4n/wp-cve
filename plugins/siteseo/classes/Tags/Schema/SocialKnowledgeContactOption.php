<?php

namespace SiteSEO\Tags\Schema;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class SocialKnowledgeContactOption implements GetTagValue
{
	const NAME = 'social_knowledge_contact_option';

	/**
	 * @since 4.5.0
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;

		$value   = siteseo_get_service('SocialOption')->getSocialKnowledgeContactOption();
		if ('None' === $value) {
			$value = '';
		}

		return apply_filters('siteseo_get_tag_schema_social_knowledge_contact_option', $value, $context);
	}
}
