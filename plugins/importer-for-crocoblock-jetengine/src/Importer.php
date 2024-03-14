<?php

namespace CodingChicken\Importer\JetEngine;

if(!class_exists('CodingChicken\Importer\JetEngine\Importer')) {
	class Importer {
		protected $add_on;
		protected $fieldBuilder;
		static protected $data;
		static $current_record;

		public function __construct() {
			$this->add_on = \CodingChicken\Importer\JetEngine\IMPORTER_JETENGINE_Plugin::getAddon();

			$this->fieldBuilder = new FieldFactory\Builder( $this->add_on );

		}

		static public function get_field_value($name){
			// Return value if it exists, otherwise return false.
			return self::$data[$name] ?? false;
		}

		public function import( $post_id, $data, $import_options, $article ) {
			// Save the data for use as needed.
			self::$data = $data;

			// Save the article data for use as needed.
			self::$current_record = $article;

			// Indicate in the log that we're starting the JetEngine portion of the import.
			$this->add_on->log( '<b>' . __( 'Importing JetEngine Data', 'codingchicken-jetengine-importer' ) . ':</b>' );
//error_log(print_r($data, true));

			// Filter to allow custom field processing. Must return an array.
			$data = apply_filters('cc_jetengine_importer_custom_import_data_processing', $data, $post_id, $import_options, $article);

			// Process each data item.
			foreach ( $data as $key => $datum ) {

				$field = self::build_field_import_array($key, $post_id, $datum);

				// Skip data fields as they aren't meant to be processed directly.
				if(str_replace('data-', '', $field['type']) !== $field['type']){
					continue;
				}

				// Check that the field type is valid.
				if ( ! $this->fieldBuilder->validateField( $field['type'] ) ) {
					// Try to get the field value differently.
					/*future: identify field type using an alternative method*/
					$this->add_on->log( __( 'Failed to identify field type for: ' . esc_textarea( $key ), 'codingchicken-jetengine-importer' ) );
					continue;
				}

				// Only update field if it's a new record or the field is allowed to be updated.
				// future: update checking for CCT
				if ( empty( $article['ID'] ) || $this->add_on->can_update_meta( $field['name'], $import_options ) ) {
					$value = $this->fieldBuilder->import( $field );

					if ( $value !== false ) {
						// Log field was set/updated.
						$this->add_on->log( __( 'Field `' . esc_textarea( $field['name'] ) . '` was updated with value: ' . $value, 'codingchicken-jetengine-importer' ) );
					} else {
						$this->add_on->log( __( 'Field `' . esc_textarea( $field['name'] ) . '` failed to update.', 'codingchicken-jetengine-importer' ) );
					}
				}else{
					$this->add_on->log( __( 'Field `' . esc_textarea( $field['name'] ) . '` skipped due to import settings.', 'codingchicken-jetengine-importer' ) );
				}
			}
		}

		/**
		 * @param $key
		 * @param $post_id
		 * @param $value
		 *
		 * @return array
		 */
		static public function build_field_import_array($key, $post_id, $value): array {
			// Split field into individual data pieces.
			$parts = explode( '_0_', $key );

			// Get field type.
			$type = end( $parts );

			// Include details on the expected save format if it's specified for the given field.


			// Format data parts into named array.
			return [    'jet_id' => $parts[2],
						'type' => $type,
						'name' => $parts[1],
						'post_id' => $post_id,
						'field_options' => self::convert_key_value_string_to_array($parts[3]),
						'value' => $value
				];

		}

		/**
		 * @param $string
		 * @param $delim1
		 * @param $delim2
		 *
		 * @return array
		 */
		static private function convert_key_value_string_to_array($string, $delim1 = '__opt__', $delim2 = '-'): array {
			// Split each key/value pair into separate array elements.
			$key_val_pairs = explode($delim1, $string);

			// Array to hold key/value pairs.
			$result = [];

			// Only continue processing if we have pairs to process.
			if( !empty($key_val_pairs[0])) {

				// Save each key value pair to the new array.
				for ( $i = 0; $i < count( $key_val_pairs ); $i ++ ) {
					$key_value                = explode( $delim2, $key_val_pairs [ $i ] );
					$result[ $key_value [0] ] = $key_value [1];
				}
			}

			return $result;
		}
	}
}