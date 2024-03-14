<?php
/**
 * WCF7 Field Mapper class.
 *
 * @package add-on-cf7-for-notion
 */

namespace WPC_WPCF7_NTN;

defined( 'ABSPATH' ) || exit;

/**
 * WCF7 Field Mapper class.
 * Register supported WCF7 fields and map them to Notion's ones.
 * Allows reformatting WCF7 fields to Notion fields format.
 */
class WPCF7_Field_Mapper {

	/**
	 * WPCF7_Field_Mapper instance
	 *
	 * @var WPCF7_Field_Mapper $instance
	 */
	private static $instance;

	/**
	 * Returns WPCF7_Field_Mapper instance
	 *
	 * @return WPCF7_Field_Mapper
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Returns supported WPCF7/Notion fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		/**
		 * Returns supported WPCF7/Notion fields.
		 * E.g. How to map a WPCF7 "text" field to a Notion "number" database column.
		 * [
		 *  'text' => [
		 *      'number' => function ($field_value) {
		 *          return ['number' => (float) $field_value ]
		 *      }
		 *  ]
		 * ]
		 *
		 * @param array $fields The supported WPCF7/Notion fields.
		 */
		return apply_filters( 'add-on-cf7-for-notion/wpcf7-field-mapper/fields', array() );
	}

	/**
	 * Check if a WPCF7 field type is compatible with a Notion field type.
	 *
	 * @param string $wpcf7_field_type A WPCF7 field type.
	 * @param string $notion_field_type A Notion field type.
	 * @return bool
	 */
	public function check_field_compat( $wpcf7_field_type, $notion_field_type ) {
		$fields = $this->get_fields();
		return isset( $fields[ $wpcf7_field_type ][ $notion_field_type ] );
	}

	/**
	 * Returns all supported Notion field types.
	 *
	 * @return array
	 */
	public function get_supported_notion_types() {
		$fields          = $this->get_fields();
		$supported_types = array_reduce(
			$fields,
			function ( $types, $field_types ) {
				return array_merge( $types, array_keys( $field_types ) );
			},
			array()
		);
		return array_unique( $supported_types );
	}

	/**
	 * From a mapped tag list returns only compatible WPCF7 / Notion fields.
	 *
	 * @see WPC_WPCF7_NTN\Helpers\get_mapped_tags_from_contact_form
	 * @param array $mapped_tags Mapped tags.
	 * @return array
	 */
	public function filter_mapped_tags( $mapped_tags ) {
		return array_filter(
			$mapped_tags,
			function ( $field ) {
				return $this->check_field_compat( $field['type'], $field['notion_field_type'] );
			}
		);
	}

	/**
	 * Returns a formatted value from a field value based on a WPCF7 field type and a Notion's one.
	 *
	 * @param string $wpcf7_field_type A WPCF7 field type.
	 * @param string $notion_field_type A Notion field type.
	 * @param mixed  $wpcf7_field_value The formatted value for the Notion API or false if the WPCF7 field value can't be properly formatted.
	 * @return false|mixed
	 */
	public function get_formatted_field_value( $wpcf7_field_type, $notion_field_type, $wpcf7_field_value ) {
		if ( ! $this->check_field_compat( $wpcf7_field_type, $notion_field_type ) ) {
			return false;
		}
		$fields     = $this->get_fields();
		$formatters = $fields[ $wpcf7_field_type ][ $notion_field_type ];

		return $this->apply_formatters( $formatters, $wpcf7_field_value );
	}

	/**
	 * Applies a list of formatters (functions) on a value.
	 *
	 * @param callable|callable[] $formatters One or more formatters.
	 * @param mixed               $value The WPCF7 field value.
	 * @return mixed The formatted value.
	 */
	public function apply_formatters( $formatters, $value ) {
		if ( ! is_array( $formatters ) ) {
			$formatters = array( $formatters );
		}
		return array_reduce( $formatters, array( $this, 'apply_formatter' ), $value );
	}

	/**
	 * Applies a formatter (function) on a value.
	 *
	 * @param mixed    $result The formatted value or the initial one.
	 * @param callable $formatter A formatter.
	 * @return mixed The formatted value.
	 */
	public function apply_formatter( $result, $formatter ) {
		return $formatter( $result );
	}
}
