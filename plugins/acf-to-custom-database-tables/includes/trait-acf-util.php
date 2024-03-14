<?php


trait ACFUtil
{
	public static function get_acf_keys($field_group_id, $include_extra_fields = false)
	{
		if (empty($field_group_id) || function_exists('acf_get_fields') === false) {
			return [];
		}
		$fieldsMeta = $flatFields = [];

		$acfFields = acf_get_fields($field_group_id);
		$flatFields = self::prefix_acf_fields($acfFields, $include_extra_fields);

		foreach ($flatFields as $value) {
			$fieldsMeta[$value['key']] = array(
				'key' => $value['key'],
				'name' => $value['name'],
				'type' => $value['type'],
				'parent_key' => (array_key_exists('parent_key', $value)) ? $value['parent_key'] : null
			);
		}
		return $fieldsMeta;
	}

	protected static function prefix_acf_fields($fields, $include_extra_fields, $prefixedFields = [], $parent_name = false, $parent_type = false, $parent_key = null)
	{
		foreach ($fields as $field) {

			/*
			 * skip blacklisted types
			 */
			if (self::should_skip_field($field)) {
				continue;
			}

			$parentName = $field['name'];
			if ($parent_name) {
				$parentName = $parent_name . '_' . $parentName;
			}

			$parentKey = $parent_key ? $parent_key : $field['key'];

			if ($field['type'] === 'flexible_content') {
				if ($include_extra_fields) {
					foreach ($field['layouts'] as $layout) {
						$prefixedSubFields = self::prefix_acf_fields($layout['sub_fields'], $include_extra_fields, $prefixedFields, $parentName . '_' . $layout['name'], $field['type'], $parentKey);
						$prefixedFields = array_merge($prefixedFields, $prefixedSubFields);
					}
				}
			} else if (in_array($field['type'], ['repeater', 'group'])) {
				if ($include_extra_fields || $field['type'] === 'group' ) {
					$prefixedSubFields = self::prefix_acf_fields($field['sub_fields'], $include_extra_fields, $prefixedFields, $parentName, $field['type'], $parentKey);
					$prefixedFields = array_merge($prefixedFields, $prefixedSubFields);
				}
			} else {
				if ($parent_name) {
					$field['name'] = $parent_name . '_' . $field['name'];
				}
				$field['parent_key'] = $parent_key;
				array_push($prefixedFields, $field);
			}

			if (in_array($field['type'], self::$sub_field_containing_field)) {
				if ($include_extra_fields === true || in_array($field['type'], ['flexible_content', 'repeater'])) {
					$prefixedFields[] = array(
						'key' => $field['key'],
						'name' => $parentName,
						'type' => $field['type'],
						'parent_key' => $parent_key
					);
				}
			}

		}

		return $prefixedFields;
	}
}
