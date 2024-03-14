<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class to initiate admin functionality
 * Class WFOPP_Admin
 */
if ( ! class_exists( 'WFOPP_Admin' ) ) {
	#[AllowDynamicProperties]

  class WFOPP_Admin {

		private static $ins = null;
		private $funnel = null;

		/**
		 * WFOPP_Admin constructor.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'check_db_version' ), 990 );
		}

		/**
		 * @return WFOPP_Admin|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}


		/**
		 * Creating table if not exist
		 */
		public function check_db_version() {
			//needs checking

			$tables = WFOPP_DB_Tables::get_instance();

			$tables->add_if_needed();
		}

	}

	if ( class_exists( 'WFOPP_Core' ) ) {
		WFOPP_Core::register( 'admin', 'WFOPP_Admin' );
	}
}
