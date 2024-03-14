<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_DB_Optin
 */
if ( ! class_exists( 'WFFN_DB_Optin' ) ) {
	#[AllowDynamicProperties]

  class WFFN_DB_Optin {
		/**
		 * @var $ins
		 */
		public static $ins;

		/**
		 * @var $wp_db
		 */
		public $wp_db;

		/**
		 * @var $contact_tbl
		 */
		public $optin_tbl;

		/**
		 * WFFN_DB_Optin constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db     = $wpdb;
			$this->optin_tbl = $this->wp_db->prefix . 'bwf_optin_entries';
		}

		/**
		 * @return WFFN_DB_Optin
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * Inserting a new row in bwf_optins table
		 *
		 * @param $optin
		 *
		 * @return int
		 * @SuppressWarnings(PHPMD.DevelopmentCodeFragment)
		 */
		public function insert_optin( $optin ) {
			$optin_data = array(
				'step_id'   => $optin['step_id'],
				'funnel_id' => $optin['funnel_id'],
				'cid'       => $optin['cid'],
				'opid'      => $optin['opid'],
				'email'     => $optin['email'],
				'data'      => wp_json_encode( $optin['data'] ),
				'date'      => current_time( 'mysql' ),
			);

			$inserted = $this->wp_db->insert( $this->optin_tbl, $optin_data );

			$lastId = 0;
			if ( $inserted ) {
				$lastId = $this->wp_db->insert_id;
			}
			if ( ! empty( $this->wp_db->last_error ) ) {
				WFFN_Core()->logger->log( 'Get last error in insert_contact: ' . print_r( $this->wp_db->last_error, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}

			return $lastId;
		}




		/**
		 * Get contact for given opid if it exists
		 */
		public function get_contact_by_opid( $opid ) {
			$sql = "SELECT * FROM `$this->optin_tbl` WHERE `opid` = '$opid' ";

			$contact = $this->wp_db->get_row( $sql ); //WPCS: unprepared SQL ok

			return $contact;
		}

		/**
		 * Get contact for given id if it exists
		 */
		public function get_contact( $id ) {
			$sql = "SELECT * FROM `$this->optin_tbl` WHERE `id` = '$id' ";

			$contact = $this->wp_db->get_row( $sql ); //WPCS: unprepared SQL ok

			return $contact;
		}

		/**
		 * Get contact for given funnel id if it exists
		 */
		public function get_contact_by_funnels( $funnel_id ) {
			$sql = "SELECT * FROM `$this->optin_tbl` WHERE `funnel_id` = '$funnel_id' ORDER BY `$this->optin_tbl`.`id` ASC";

			$contact = $this->wp_db->get_results( $sql, ARRAY_A ); //WPCS: unprepared SQL ok

			return $contact;
		}


	}

	WFFN_DB_Optin::get_instance();
}