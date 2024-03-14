<?php

namespace CodingChicken\Importer\JetEngine\FieldFactory\Fields;

if( !class_exists('CodingChicken\Importer\JetEngine\FieldFactory\Fields\Textarea')) {
	class Textarea {
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
			$field_options = '';

			return $this->add_on->add_field( 'cc_jetengine_importer_0_'.$this->data['name'].'_0_'.$this->data['id'].'_0_'.$field_options.'_0_'.$this->data['type'], $this->data['title'], 'textarea' );
		}

	}

}