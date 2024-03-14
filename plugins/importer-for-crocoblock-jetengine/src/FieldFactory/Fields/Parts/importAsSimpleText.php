<?php

namespace CodingChicken\Importer\JetEngine\FieldFactory\Fields\Parts;

trait importAsSimpleText {
	public function import($fieldData){

		// If the calling class has a method to prepare the value we should call it.
		if(method_exists(__CLASS__, 'prepareValue')){
			$fieldData = $this->prepareValue($fieldData);
		}

		if((isset($fieldData['is_cct']) && $fieldData['is_cct']) || strpos($fieldData['jet_id'], '_cct') !== false){
			// Maybe save CCT fields.
			$cct_saved_value = apply_filters('cc_jetengine_importer_maybe_save_cct_field', false, $fieldData);

			// This will work as a saved value shouldn't ever be boolean false (it will be a falsey value instead).
			if($cct_saved_value === false) {
				$this->add_on->log( __( 'CCT support not implemented for field type: ' . esc_textarea( $fieldData['type'] ), 'codingchicken-jetengine-importer' ) );
			}else{
				return \maybe_serialize($cct_saved_value);
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
			return \maybe_serialize($fieldData['value']);
		}
	}
}