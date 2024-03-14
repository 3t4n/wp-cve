<?php
require_once dirname( __FILE__ ) . '/base/cf7-addons.parent.php';

if ( ! class_exists( 'CF7_ADDONS_SERVER_SIDE' ) ) {
	class CF7_ADDONS_SERVER_SIDE extends CF7_ADDONS_PARENT {

		protected $slug        = 'server-side';
		protected $title       = 'SERVER SIDE';
		protected $image       = 'server-side.addon/image.png';
		protected $description = 'Implements server-side functions to use with the server-side data source option.';
		protected $help_url    = 'https://cf7-datasource.dwbooster.com/server-side-addon';

		public function settings_interface() {      } // End settings_interface
	} // End class CF7_ADDONS_SERVER_SIDE
}

CF7_DATASOURCE_ADDONS::add( new CF7_ADDONS_SERVER_SIDE() );