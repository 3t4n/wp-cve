<?php

namespace CodingChicken\Importer\JetEngine\FieldFactory\Fields;

if( !class_exists('CodingChicken\Importer\JetEngine\FieldFactory\Fields\Checkbox')) {
	class Checkbox {
		use Parts\importAsSimpleText;

		protected $add_on;
		protected $data;

		public function __construct(\Soflyy\WPAllImport\RapidAddon $addon_obj, $data){
			$this->add_on = $addon_obj;
			$this->data = $data;
			$this->validateData();
		}

		private function validateData(){
			// Ensure all required data values exist or set default.
			foreach( $this->data as $key => $datum){
				switch($key){
					case ('title'):
						if(empty($datum)){
							$this->data[$key] = $this->data['name']; // Name should always exist or something is broken.
						}
						break;

				}
			}
		}

		public function render(){
			// Provide placeholder for id if needed.
			$this->data['id'] = $this->data['id'] ?? '0000';

			// Check if CCT and add an indicator.
			$this->data['id'] .= (isset($this->data['is_cct']) && $this->data['is_cct']) ? '_cct' : '_null';

			// Check if repeater subfield.
			$this->data['id'] .= (isset($this->data['is_repeater']) && $this->data['is_repeater']) ? '_rep' : '_null';

			// Set any needed field options.
			if( isset($this->data['is_array']) && $this->data['is_array']){
				$field_options = 'array-1';
			}else {
				$field_options = 'array-0';
			}

			$options = $this->get_options();

			// Generate tooltip.
			$tooltip = 'Options currently configured for this field:';
			foreach($options as $key => $option){
				$tooltip .= ' '. $key . ',';
			}
			$tooltip = trim($tooltip, ',') . '.';

			// Add note for multiple checkbox values.
			$tooltip .= '<br/><br/>If importing multiple checkbox values use `Set with XPath` and separate values with commas: one,two,three';

			return $this->add_on->add_field( 'cc_jetengine_importer_0_'.$this->data['name'].'_0_'.$this->data['id'].'_0_'.$field_options.'_0_'.$this->data['type'], $this->data['title'], 'radio', $options, $tooltip );
		}

		private function prepareValue($fieldData){

			$values         = explode( ',', $fieldData['value'] );
			$values         = array_map('trim', $values);
			$formattedValue = [];

			if( isset($fieldData['field_options']['array']) && $fieldData['field_options']['array'] ){

				// When the option to store as an array is set we don't need to do anything more.
				$formattedValue = $values;

			}else {

				// Every option needs a true/false indicator to show if it's checked if not set to save as simple array.
				foreach ( $this->get_options() as $key => $option ) {
					if ( in_array( $key, $values ) ) {
						$formattedValue[ $key ] = 'true'; // Expects a text value.
					} else {
						$formattedValue[ $key ] = 'false'; // Expects a text value.
					}
				}

			}

			// Update fieldData.
			$fieldData['value'] = $formattedValue;

			return $fieldData;
		}

		private function get_options(){
			$options = [];

			foreach($this->data['options'] as $option){
				$options[$option['key']] = $option['value'];
			}

			return $options;
		}

	}

}