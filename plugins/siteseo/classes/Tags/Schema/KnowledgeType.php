<?php

namespace SiteSEO\Tags\Schema;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class KnowledgeType implements GetTagValue {
	const NAME = 'knowledge_type';

	/**
	 * @since 4.5.0
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;

		$value = siteseo_get_service('SocialOption')->getSocialKnowledgeType();

		if (empty($value)) {
			$value = 'Organization';
		}

		return apply_filters('siteseo_get_tag_schema_knowledge_type', $value, $context);
	}
}
