<?php

namespace CodingChicken\Importer\JetEngine\FieldFactory\Fields;

if( !class_exists('CodingChicken\Importer\JetEngine\FieldFactory\Fields\None')) {
	class None {

		protected $add_on;
		protected $data;

		public function __construct( \Soflyy\WPAllImport\RapidAddon $addon_obj, $data ) {
			$this->add_on = $addon_obj;
			$this->data   = $data;
			$this->validateData();
		}

		private function validateData() {

		}

		public function render() {
			return $this->add_on->add_field( 'cc_importer_no_fields', '<span style="font-weight:500;display:block;padding-top:10px;padding-bottom:20px;" class="cc_jetengine_importer_unsupported_field_type">No Meta fields were found for this import\'s target.</span>', 'plain_text' );
		}

		public function import() {

		}

	}
}
