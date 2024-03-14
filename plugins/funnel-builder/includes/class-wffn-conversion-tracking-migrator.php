<?php

if ( ! class_exists( 'WFFN_Conversion_Tracking_Migrator' ) ) {
	class WFFN_Conversion_Tracking_Migrator extends WooFunnels_Background_Updater {
		public static $_instance = null;
		protected $prefix = 'bwf_conversion_1';
		protected $action = 'migrator';

		public function __construct() {
			parent::__construct();
		}

		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}


		public function maybe_re_dispatch_background_process() {
			if ( $this->is_queue_empty() ) {
				return;
			}
			if ( $this->is_process_running() ) {
				return;
			}
			$this->dispatch();
		}

		public function get_action() {
			return $this->action;
		}

		/**
		 * Kill process.
		 *
		 * Stop processing queue items, clear cronjob and delete all batches.
		 */
		public function kill_process() {
			$this->kill_process_safe();
		}

		public function get_last_offsets() {
			return array();
		}

		public function manage_last_offsets() {

		}

		protected function complete() {
			$migrate_total = get_option( '_bwf_conversion_offset', 0 );
			$this->set_upgrade_state( 3 );
			WFFN_Core()->logger->log( 'migration process complete for total number of order ' . $migrate_total, 'fk_conv_migration', true );
			update_option( '_bwf_conversion_offset', 0 );
			update_option( '_bwf_optin_conversion_offset', 0 );
			do_action( 'bwf_conversion_tracking_index_completed' );
		}

		public static function update_multiple_conversion_rows( $data ) {
			global $wpdb;
			if ( ! is_array( $data ) || empty( $data ) ) {
				return;
			}

			$table_name = $wpdb->prefix . 'bwf_conversion_tracking';

			foreach ( $data as $item ) {
				$sql        = '';
				$primary_id = $item['id'];
				unset( $item['id'] );

				$update_data   = $item;
				$primary_value = $primary_id;
				$placeholders  = array();
				$update_query  = "UPDATE $table_name SET ";
				foreach ( $update_data as $key => $value ) {
					$placeholders[] = "$key = %s";
				}
				$update_query       .= implode( ', ', $placeholders );
				$update_query       .= " WHERE id = %d;";
				$update_data_values = array_merge( array_values( $update_data ), array( $primary_value ) );
				$sql                .= $wpdb->prepare( $update_query, $update_data_values );
				$wpdb->query( $sql );

				if ( ! empty( $wpdb->last_error ) ) {
					WFFN_Core()->logger->log( 'migration process wffn_test_update_multiple_conversion_rows error ' . $wpdb->last_error . ' last query ' . $wpdb->last_query, 'fk_conv_migration', true );
				}
			}

		}

		public function get_upgrade_state() {

			/**
			 * 0: default state, nothing set
			 * 1: upgrade is available
			 * 2: upgrade is in process
			 * 3: upgrade is completed successfully
			 * 4: upgrade is unavailable
			 */
			return absint( get_option( '_fk_conversion_upgrade', '0' ) );
		}

		public function set_upgrade_state( $state ) {

			update_option( '_fk_conversion_upgrade', $state );
		}

		public static function insert_multiple_conversion_rows( $data ) {
			global $wpdb;
			if ( ! is_array( $data ) || empty( $data ) ) {
				return;
			}

			$table_name = $wpdb->prefix . 'bwf_conversion_tracking';

			$first_row     = reset( $data );
			$columns       = array_keys( $first_row );
			$placeholders  = array_fill( 0, count( $data ), '(' . rtrim( str_repeat( '%s, ', count( $columns ) ), ', ' ) . ')' );
			$query         = "INSERT INTO $table_name (" . implode( ', ', $columns ) . ") VALUES " . implode( ', ', $placeholders );
			$insert_values = [];
			foreach ( $data as $row ) {
				$insert_values = array_merge( $insert_values, array_values( $row ) );
			}
			$sql = $wpdb->prepare( $query, $insert_values );
			$wpdb->query( $sql );

			if ( ! empty( $wpdb->last_error ) ) {
				WFFN_Core()->logger->log( 'migration process insert_multiple_conversion_rows error ' . $wpdb->last_error . ' last query ' . $wpdb->last_query, 'fk_conv_migration', true );
			}

		}
	}

	if ( ! function_exists( 'wffn_conversion_tracking_migrator' ) ) {
		function wffn_conversion_tracking_migrator() {  //@codingStandardsIgnoreLine
			return WFFN_Conversion_Tracking_Migrator::get_instance();
		}
	}

	wffn_conversion_tracking_migrator();
}

