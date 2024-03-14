<?php
require_once dirname( __FILE__ ) . '/base/cf7-addons.parent.php';

if ( ! class_exists( 'CF7_ADDONS_USER_REGISTRATION' ) ) {
	class CF7_ADDONS_USER_REGISTRATION extends CF7_ADDONS_PARENT {

		protected $slug        = 'user-registration';
		protected $title       = 'User Registration';
		protected $image       = 'user-registration.addon/image.png';
		protected $description = "Registers new website's users with the information collected by the CF7 form.";
		protected $help_url    = 'https://cf7-datasource.dwbooster.com/user-registration-addon';
		private $settings;

		public function settings_interface() {      } // End settings_interface
	} // End class CF7_ADDONS_USER_REGISTRATION
}

CF7_DATASOURCE_ADDONS::add( new CF7_ADDONS_USER_REGISTRATION() );
