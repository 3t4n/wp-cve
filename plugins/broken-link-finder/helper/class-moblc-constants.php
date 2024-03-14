<?php
/**
 * This file contains constants.
 *
 * @package broken-link-finder/helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'MOBLC_Constants' ) ) {
	/**
	 * This is constants class.
	 */
	class MOBLC_Constants {

		const MOBLC_DB_VERSION = 1001;
		/**
		 * Constructor
		 */
		public function __construct() {
			$this->moblc_define_global();
		}
		/**
		 * Function for defining global variables.
		 *
		 * @return void
		 */
		public function moblc_define_global() {
			global $moblc_db_queries, $moblc_dir_path, $moblc_main_dir, $moblc_dirname;
			$moblc_db_queries = new MOBLC_DATABASE();
			$moblc_dir_path   = plugin_dir_path( dirname( __FILE__ ) );
			$moblc_main_dir   = plugin_dir_url( dirname( __FILE__ ) );
			$moblc_dirname    = dirname( __FILE__ );
		}

	}
	new MOBLC_Constants();
}




