<?php

namespace SiteSEO\JsonSchemas;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetJsonData;
use SiteSEO\Models\JsonSchemaValue;

class Image extends JsonSchemaValue implements GetJsonData {
	const NAME = 'image';

	protected function getName() {
		return self::NAME;
	}

	/**
	 * @since 4.6.0
	 *
	 * @param array $context
	 *
	 * @return string|array
	 */
	public function getJsonData($context = null) {
		$data = $this->getArrayJson();

		return apply_filters('siteseo_get_json_data_image', $data);
	}
}
