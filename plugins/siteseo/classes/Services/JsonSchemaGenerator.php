<?php

namespace SiteSEO\Services;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Compose\UseJsonSchema;

class JsonSchemaGenerator {
	use UseJsonSchema;

	public function __construct() {
		$this->tagsToString	  = siteseo_get_service('TagsToString');
		$this->variablesToString = siteseo_get_service('VariablesToString');
	}

	/**
	 * @since 4.5.0
	 *
	 * @param string $schema
	 * @param array  $context
	 * @param array  $options
	 *
	 * @return array
	 */
	public function getJsonFromSchema($schema, $context= [], $options = []) {
		$classJsonSchema = $this->getSchemaClass($schema);
		if (null === $classJsonSchema) {
			return null;
		}

		$jsonData		= $classJsonSchema->getJsonData($context);

		if (isset($context['variables'])) {
			$jsonData = $this->variablesToString->replaceDataToString($jsonData, $context['variables'], $options);
		}

		$jsonData = $this->tagsToString->replaceDataToString($jsonData, $context, $options);
		if ( ! empty($jsonData)) {
			$jsonData = $classJsonSchema->cleanValues($jsonData);
		}

		return $jsonData;
	}

	/**
	 * @since 4.5.0
	 *
	 * @param array $data
	 * @param array $context
	 */
	public function getJsons($data, $context = []) {
		$jsonsAvailable = $this->getSchemasAvailable();

		if ( ! is_array($data)) {
			return [];
		}

		foreach ($data as $key => $schema) {
			$context['key_get_json_schema']  = $key;
			$data[$key]					  = $this->getJsonFromSchema($schema, $context, ['remove_empty'=> true]);
		}

		return apply_filters('siteseo_json_schema_generator_get_jsons', $data);
	}

	/**
	 * @since 4.5.0
	 *
	 * @param array $data
	 * @param array $context
	 */
	public function getJsonsEncoded($data, $context = []) {
		if ( ! is_array($data)) {
			return [];
		}

		$data = $this->getJsons($data, $context);

		foreach ($data as $key => $value) {
			if (null === $value) {
				unset($data[$key]);
				continue;
			}
			$data[$key] = wp_json_encode($data[$key]);
		}

		return apply_filters('siteseo_json_schema_generator_get_jsons_encoded', $data);
	}
}
