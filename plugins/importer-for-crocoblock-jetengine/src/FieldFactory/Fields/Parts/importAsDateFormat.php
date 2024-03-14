<?php

namespace CodingChicken\Importer\JetEngine\FieldFactory\Fields\Parts;

trait importAsDateFormat {
	public function import($fieldData){

		// If the calling class has a method to prepare the value we should call it.
		if(method_exists(__CLASS__, 'prepareValue')){
			$fieldData = $this->prepareValue($fieldData);
		}

		// Set format from field data.
		$this->dateFormat = isset($fieldData['field_options']['format']) && 'timestamp' == $fieldData['field_options']['format'] ? 'timestamp' : $this->dateFormat;

		// Fallback format if one hasn't been provided.
		$format = !empty($this->dateFormat) ? $this->dateFormat : 'Y-m-d';

		// If a value was provided we should process it.
		if( !empty($fieldData['value'])) {
			// Get the timestamp of the provided value if needed.
			$fieldData['value'] = ( is_numeric( $fieldData['value'] ) ) ? $fieldData['value'] : \strtotime( $fieldData['value'] );
		}else{
			// Otherwise we should set it to zero. In the future we may grab the default set in JetEngine based on feedback.
			$fieldData['value'] = 0;
		}

		// Format value before saving.
		if($format !== 'timestamp') {
			$fieldData['value'] = \date( $format, $fieldData['value'] );
		}else{
			$this->add_on->log( 'Field `'.$fieldData['name'].'` save value format set to timestamp in JetEngine configuration.' );
			// No additional processing is needed if saving as timestamp.
		}

		if((isset($fieldData['is_cct']) && $fieldData['is_cct']) || strpos($fieldData['jet_id'], '_cct') !== false){
			// Maybe save CCT fields.
			$cct_saved_value = apply_filters('cc_jetengine_importer_maybe_save_cct_field', false, $fieldData);

			// This will work as a saved value shouldn't ever be boolean false (it will be a falsey value instead).
			if($cct_saved_value === false) {
				$this->add_on->log( __( 'CCT support not implemented for field type: ' . esc_textarea( $fieldData['type'] ), 'codingchicken-jetengine-importer' ) );
			}else{
				return $cct_saved_value;
			}

		}else{
			switch($fieldData['import_type']){
				case 'post':
					\update_post_meta($fieldData['post_id'], $fieldData['name'], $fieldData['value']);
					break;
				case 'user':
					\update_user_meta($fieldData['post_id'], $fieldData['name'], $fieldData['value']);
					break;
				case 'taxonomy':
					\update_term_meta($fieldData['post_id'], $fieldData['name'], $fieldData['value']);
					break;
			}

			// Return value set.
			return $fieldData['value'];
		}
	}
}