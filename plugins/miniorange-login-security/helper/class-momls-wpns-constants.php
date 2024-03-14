<?php
/** This file contains network security constants.
 *
 * @package miniorange-login-security/helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
require 'class-momls-wpns-utility.php';
if ( ! class_exists( 'Momls_Wpns_Constants' ) ) {
	/**
	 * This library is miniOrange Authentication Service.
	 * Contains Request Calls to Customer service.
	 **/
	class Momls_Wpns_Constants {

		const SUCCESS              = 'success';
		const FAILED               = 'failed';
		const PAST_FAILED          = 'pastfailed';
		const ACCESS_DENIED        = 'accessDenied';
		const LOGIN_TRANSACTION    = 'User Login';
		const DEFAULT_CUSTOMER_KEY = '16555';
		const DEFAULT_API_KEY      = 'fFd2XcvTGDemZvbw1bcUesNJWEqKbbUq';
		const DB_VERSION           = 147;
		const SUPPORT_EMAIL        = 'info@xecurify.com';
		const HOST_NAME            = 'https://login.xecurify.com';
		const FOOTER_LINK          = '<a style="display:none;" href="http://miniorange.com/cyber-security">Secured By miniOrange</a>';

		// plugins.
		const TWO_FACTOR_SETTINGS = 'miniorange-login-security/miniorange_2_factor_settings.php';
		const FAQ_PAYMENT_URL     = 'https://faq.miniorange.com/knowledgebase/all-i-want-to-do-is-upgrade-to-a-premium-licence/';
		const SETUPGUIDE          = 'https://www.youtube.com/watch?v=GRIYI_Gl3Ng';

		// arrays.
		/**
		 * Construct function
		 */
		public function __construct() {
			$this->momls_define_global();
		}
		/**
		 * Defining the global function
		 *
		 * @return void
		 */
		private function momls_define_global() {
			global $wpns_db_queries,$momls_wpns_utility,$mo2f_dir_name,$momlsdb_queries;
			$wpns_db_queries    = new Momls_Wpns_Db();
			$momls_wpns_utility = new Momls_Wpns_Utility();
			$mo2f_dir_name      = dirname( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR;
			$momlsdb_queries    = new Momls_Db();
		}

	}
	new Momls_Wpns_Constants();
}


