<?php
require_once dirname( __FILE__ ) . '/base/cf7-addons.parent.php';

if ( ! class_exists( 'CF7_ADDONS_CSV_GENERATOR' ) ) {
	class CF7_ADDONS_CSV_GENERATOR extends CF7_ADDONS_PARENT {

		protected $slug        = 'csv-generator';
		protected $title       = 'CSV Generator';
		protected $image       = 'csv-generator.addon/image.png';
		protected $description = 'Generates CSV files with the information collected by the form.';
		protected $help_url    = 'https://cf7-datasource.dwbooster.com/csv-generator-addon';

		public function settings_interface() {      } // End settings_interface
	} // End class CF7_ADDONS_CSV_GENERATOR
}

CF7_DATASOURCE_ADDONS::add( new CF7_ADDONS_CSV_GENERATOR() );
