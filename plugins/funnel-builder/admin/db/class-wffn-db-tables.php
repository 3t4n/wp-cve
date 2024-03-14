<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_DB_Tables
 */

if ( ! class_exists( 'WFFN_DB_Tables' ) ) {
	#[AllowDynamicProperties]

class WFFN_DB_Tables {

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
				'bwf_funnels',
				'bwf_funnelmeta',
			);

			return $tables;
		}

		/**
		 * @return WFFN_DB_Tables|null
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


			return $this->get_tables_list();
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
		public function funnels() {
			$collate = '';

			if ( $this->wp_db->has_cap( 'collation' ) ) {
				$collate = $this->wp_db->get_charset_collate();
			}
			$values_table = 'CREATE TABLE `' . $this->wp_db->prefix . "bwf_funnels` (
				`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				`title` text NOT NULL,
				`desc` text NOT NULL,
				`date_added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                `steps` LONGTEXT NULL DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `id` (`id`)				
                ) " . $collate . ';';
			dbDelta( $values_table );

			if ( ! empty( $this->wp_db->last_error ) ) {
				WFFN_Core()->logger->log( "bwf failed create table bwf_funnels : " . print_r( $this->wp_db->last_error, true ), 'woofunnel-failed-actions', true );
			}
		}

		public function funnelmeta() {
			$collate = '';

			if ( $this->wp_db->has_cap( 'collation' ) ) {
				$collate = $this->wp_db->get_charset_collate();
			}
			$max_index_length = 191;
			$_meta_table      = "CREATE TABLE {$this->wp_db->prefix}bwf_funnelmeta (
			meta_id bigint(20) unsigned NOT NULL auto_increment,
			bwf_funnel_id bigint(20) unsigned NOT NULL default '0',
			meta_key varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY bwf_funnel_id (bwf_funnel_id),
			KEY meta_key (meta_key($max_index_length))
		) $collate;";

			dbDelta( $_meta_table );

			if ( ! empty( $this->wp_db->last_error ) ) {
				WFFN_Core()->logger->log( "bwf failed create table bwf_funnelmeta : " . print_r( $this->wp_db->last_error, true ), 'woofunnels-failed-actions', true );
			}
		}
	}
}
