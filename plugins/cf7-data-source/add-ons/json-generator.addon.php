<?php
require_once dirname( __FILE__ ) . '/base/cf7-addons.parent.php';

if ( ! class_exists( 'CF7_ADDONS_JSON_GENERATOR' ) ) {
	class CF7_ADDONS_JSON_GENERATOR extends CF7_ADDONS_PARENT {

		protected $slug        = 'json-generator';
		protected $title       = 'JSON Generator';
		protected $image       = 'json-generator.addon/image.png';
		protected $description = 'Generates JSON files with the information collected by the form.';
		protected $help_url    = 'https://cf7-datasource.dwbooster.com/json-generator-addon';

		public function settings_interface() {      } // End settings_interface
	} // End class CF7_ADDONS_JSON_GENERATOR
}

CF7_DATASOURCE_ADDONS::add( new CF7_ADDONS_JSON_GENERATOR() );
