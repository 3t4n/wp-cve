<?php
require_once dirname( __FILE__ ) . '/base/cf7-addons.parent.php';

if ( ! class_exists( 'CF7_ADDONS_TO_DATABASE' ) ) {
	class CF7_ADDONS_TO_DATABASE extends CF7_ADDONS_PARENT {

		protected $slug        = 'to-database';
		protected $title       = 'To Database';
		protected $image       = 'to-database.addon/image.png';
		protected $description = 'Stores form submissions in the database. Inserts, updates or deletes rows from the database based on the information collected by the forms.';
		protected $help_url    = 'https://cf7-datasource.dwbooster.com/to-database-addon';

		public function settings_interface() {      } // End settings_interface
	} // End class CF7_ADDONS_TO_DATABASE
}

CF7_DATASOURCE_ADDONS::add( new CF7_ADDONS_TO_DATABASE() );
