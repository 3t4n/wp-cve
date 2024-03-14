<?php

namespace CodingChicken\Importer\JetEngine\FieldFactory\Fields;

if( !class_exists('CodingChicken\Importer\JetEngine\FieldFactory\Fields\Unsupported')) {
	class Unsupported {

		protected $add_on;
		protected $data;
		protected $pro_fields = [
									'repeater',
									'media',
									'gallery',
									'posts',
									'map'
								];

		public function __construct(\Soflyy\WPAllImport\RapidAddon $addon_obj, $data){
			$this->add_on = $addon_obj;
			$this->data = $data;
			$this->validateData();
		}

		private function validateData(){

		}

		public function render(){

			// Show the proper message based on Pro Pack support.
			if( in_array($this->data['type'], $this->pro_fields) ){
				return $this->add_on->add_field( 'cc_importer_' . $this->data['name'] . '_' . $this->data['id'], '<span style="font-weight:500;display:block;padding-top:10px;padding-bottom:20px;" class="cc_jetengine_importer_unsupported_field_type">Install the Coding Chicken - JetEngine Importer Pro Pack to add '.$this->data['type'].' support: <a href="https://codingchicken.com/downloads/crocoblock-jetengine-importer-pro-pack/" target="_blank">Click Here</a></span>', 'plain_text' );


			}else {

				return $this->add_on->add_field( 'cc_importer_' . $this->data['name'] . '_' . $this->data['id'], '<span style="font-weight:500;display:block;padding-top:10px;padding-bottom:20px;" class="cc_jetengine_importer_unsupported_field_type">The \'' . $this->data['type'] . '\' field type is unsupported. Contact support@codingchicken.com with the field type and a link to the plugin that supplies it.</span>', 'plain_text' );
			}
		}

		public function import(){

		}

	}

}