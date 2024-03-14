<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will be extended by all all single optin form field(like first_name, last_name, optin_email, optin_phone) to register different form fields
 * Class WFFN_Optin_Form_Field
 */
if ( ! class_exists( 'WFFN_Optin_Form_Field_Interface' ) ) {
	interface WFFN_Optin_Form_Field_Interface {
		public function get_field_output($field_data);
	}
}
