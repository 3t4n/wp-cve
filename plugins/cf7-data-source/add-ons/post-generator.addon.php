<?php
require_once dirname( __FILE__ ) . '/base/cf7-addons.parent.php';

if ( ! class_exists( 'CF7_ADDONS_POST_GENERATOR' ) ) {
	class CF7_ADDONS_POST_GENERATOR extends CF7_ADDONS_PARENT {

		protected $slug        = 'post-generator';
		protected $title       = 'Post Generator';
		protected $image       = 'post-generator.addon/image.png';
		protected $description = 'Generates new posts with the information collected by the CF7 form.';
		protected $help_url    = 'https://cf7-datasource.dwbooster.com/post-generator-addon';

		public function settings_interface() {      } // End settings_interface
	} // End class CF7_ADDONS_POST_GENERATOR
}

CF7_DATASOURCE_ADDONS::add( new CF7_ADDONS_POST_GENERATOR() );
