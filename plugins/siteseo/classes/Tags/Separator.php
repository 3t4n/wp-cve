<?php

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class Separator implements GetTagValue {
	const NAME = 'sep';

	const DEFAULT_SEPARATOR = '-';

	public static function getDescription() {
		return __('Separator', 'siteseo');
	}

	public function getValue($args = null) {
		$context   = isset($args[0]) ? $args[0] : null;

		$separator = siteseo_get_service('TitleOption')->getSeparator();
		if (empty($separator)) {
			$separator = self::DEFAULT_SEPARATOR;
		}

		return apply_filters('siteseo_get_tag_separator_value', $separator, $context);
	}
}
