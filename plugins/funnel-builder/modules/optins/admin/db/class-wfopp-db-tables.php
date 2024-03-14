<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOPP_DB_Tables
 */
if ( ! class_exists( 'WFOPP_DB_Tables' ) ) {
	#[AllowDynamicProperties]

  class WFOPP_DB_Tables {

		/**
		 * instance of class
		 * @var null
		 */
		private static $ins = null;
		/**
		 * WPDB instance
		 *
		 * @since 2.0
		 *
		 * @var $wp_db
		 */
		protected $wp_db;
		/**
		 * Character collation
		 *
		 * @since 2.0
		 *
		 * @var string
		 */
		protected $charset_collate;
		/**
		 * Max index length
		 *
		 * @since 2.0
		 *
		 * @var int
		 */
		protected $max_index_length = 191;
		/**
		 * List of missing tables
		 *
		 * @since 2.0
		 *
		 * @var array
		 */
		protected $missing_tables;

		/**
		 * WFFN_DB_Tables constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db = $wpdb;
			$this->define_tables();
		}

		public function define_tables() {
			global $wpdb;
			$tables = $this->get_tables_list();
			foreach ( $tables as $table ) {
				$wpdb->$table = $wpdb->prefix . $table;
			}
		}

		/**
		 * Get the list of woofunnels tables, with wp_db prefix
		 *
		 * @return array
		 * @since 2.0
		 *
		 */
		protected function get_tables_list() {

			$tables = array(
				'bwf_optin_entries',
			);

			return $tables;
		}

		/**
		 * @return WFOPP_DB_Tables|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		/**
		 * Add bwf tables if they are missing
		 *
		 * @since 2.0
		 */
		public function add_if_needed() {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			$this->missing_tables = $this->find_missing_tables();

			if ( empty( $this->missing_tables ) ) {
				return;
			}

			$search = 'bwf_';
			foreach ( $this->missing_tables as $table ) {
				call_user_func( array( $this, str_replace( $search, '', $table ) ) );
			}
		}

		/**
		 * Find any missing BWF tables
		 *
		 * @return array
		 */
		protected function find_missing_tables() {

			$tables_in_db   = $this->get_tables_in_db();
			$missing_tables = array();
			foreach ( $this->get_tables_list() as $table ) {
				if ( ! in_array( $table, $tables_in_db, true ) ) {
					$missing_tables[] = $table;
				}
			}

			return $missing_tables;
		}

		public function get_tables_in_db() {
			$version           = get_option( '_wfopp_db_version', '0.0.0' );
			$table_of_versions = array(
				'5.0.0' => array( 'bwf_optin_entries' ),
			);

			$tables      = [];
			$need_update = false;
			foreach ( $table_of_versions as $ver => $conf_tables ) {
				if ( version_compare( $ver, $version, '<=' ) ) {
					$tables = array_merge( $tables, $conf_tables );
				} else {
					$need_update = $ver;

				}
			}

			if ( false !== $need_update ) {
				update_option( '_wfopp_db_version', $need_update, true );
			}

			return $tables;

		}

		/**
		 * Get list of missing tables
		 *
		 * @return array
		 * @since 2.0
		 *
		 */
		public function get_missing_tables() {
			return $this->missing_tables;
		}

		/**
		 * Add bwf_funnels table
		 *
		 *  Warning: check if it exists first, which could cause SQL errors.
		 */
		public function optin_entries() {
			$collate = '';

			if ( $this->wp_db->has_cap( 'collation' ) ) {
				$collate = $this->wp_db->get_charset_collate();
			}
			$values_table = 'CREATE TABLE `' . $this->wp_db->prefix . "bwf_optin_entries` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`step_id` bigint(20) unsigned NOT NULL,
				`funnel_id` bigint(20) unsigned NOT NULL,
				`cid` bigint(20) unsigned NOT NULL,
				`opid` varchar(255) NOT NULL,
				`email` varchar(100) NOT NULL,
				`data` LONGTEXT NULL DEFAULT NULL,
 		        `date` datetime NOT NULL,
				PRIMARY KEY (`id`),
				KEY `id` (`id`),			
				KEY `date` (`date`)
                ) " . $collate . ';';
			dbDelta( $values_table );

			if ( ! empty( $this->wp_db->last_error ) ) {
				WFFN_Core()->logger->log( "bwf failed create table bwf_optin_entries : " . print_r( $this->wp_db->last_error, true ), 'woofunnel-failed-actions', true );
			}
		}
	}
}
