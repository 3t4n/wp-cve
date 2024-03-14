<?php

trait Acfct_formatters
{

	use Acfct_pro_formatters;

	public function get_formatted_value($field_type, $acfKeyMap, $key, $value)
	{
		$formatted_value = $value;

		if ($field_type === 'flexible_content') {
			$formatted_value = $this->get_flexible_content_formatted_value($acfKeyMap, $key, $value);
		} else if ($field_type === 'repeater') {
			$formatted_value = $this->get_repeater_formatted_value($acfKeyMap, $key, $value);
		}

		if (in_array($field_type, array_merge(Acfct_utils::$array_output_fields, ['flexible_content', 'repeater']), true)) {
			return acf_ct_serialize($formatted_value);
		}

		return $formatted_value;
	}

	public function get_acf_formatted_value($acfFields, $key, $field, $value)
	{
		$field_type = $field['type'];
		/**
		 * Flexible content and repeater has almost same structure.
		 * So Reusing flexible content logic for repeater
		 */
		if ($field_type === 'flexible_content' || $field_type === 'repeater') {
			$fc = acf_ct_unserialize($value);
			$fc_values = $this->get_flexible_content_acf_values($acfFields, $key, $field, $fc);
			return $fc_values;
		} else if (in_array($field_type, Acfct_utils::$array_output_fields)) { //array output fields
			return acf_ct_unserialize($value);
		}

		return $value;

	}

}
