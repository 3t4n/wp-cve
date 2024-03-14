<?php
require_once dirname( __FILE__ ) . '/base/cf7-addons.parent.php';

if ( ! class_exists( 'CF7_ADDONS_PDF_GENERATOR' ) ) {
	class CF7_ADDONS_PDF_GENERATOR extends CF7_ADDONS_PARENT {

		protected $slug        = 'pdf-generator';
		protected $title       = 'PDF Generator';
		protected $image       = 'pdf-generator.addon/image.png';
		protected $description = 'Generates PDF files with the information collected by the CF7 form.';
		protected $help_url    = 'https://cf7-datasource.dwbooster.com/pdf-generator-addon';

		public function settings_interface() {      } // End settings_interface
	} // End class CF7_ADDONS_PDF_GENERATOR
}

CF7_DATASOURCE_ADDONS::add( new CF7_ADDONS_PDF_GENERATOR() );
