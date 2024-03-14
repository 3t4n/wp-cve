<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class contain data for experiments
 * Class WFFN_Funnels_DB
 */
if ( ! class_exists( 'WFFN_Funnels_DB' ) ) {
	class WFFN_Funnels_DB {

		static $primary_key = 'id';
		static $count = 20;
		static $query = [];
		/**
		 * @var wpdb
		 */
		static $wp_db;

		static function init() {
			global $wpdb;
			self::$wp_db = $wpdb;
		}
		public function clear_cache() {
			self::$query = [];
		}
		public function get( $value ) {
			$sql = self::_fetch_sql( $value );
			if ( true === apply_filters( 'wffn_funnel_data_cache', true ) && isset( self::$query[ md5( $sql ) ] ) ) {
				return self::$query[ md5( $sql ) ];
			}
			$result                     = self::$wp_db->get_row( $sql, ARRAY_A );
			self::$query[ md5( $sql ) ] = $result;

			return $result;
		}

		private static function _fetch_sql( $value ) {

			$sql = sprintf( 'SELECT * FROM %s WHERE %s = %%s', self::_table(), static::$primary_key );

			return self::$wp_db->prepare( $sql, $value );
		}

		private static function _table() {

			$table_name = 'bwf_funnels';

			return self::$wp_db->prefix . $table_name;
		}

		private static function _tablemeta() {

			$table_name = 'bwf_funnelmeta';

			return self::$wp_db->prefix . $table_name;
		}

		public function insert( $data ) {

			self::$wp_db->insert( self::_table(), $data );
		}

		public function update( $data, $where ) {

			return self::$wp_db->update( self::_table(), $data, $where );
		}

		public function delete( $value ) {

			$sql = sprintf( 'DELETE FROM %s WHERE %s = %%s', self::_table(), static::$primary_key );
			$meta_sql = sprintf( 'DELETE FROM %s WHERE bwf_funnel_id = %%s', self::_tablemeta() );
			self::$wp_db->query( self::$wp_db->prepare( $meta_sql, $value ) );
			return self::$wp_db->query( self::$wp_db->prepare( $sql, $value ) );
		}

		public function insert_id() {

			return self::$wp_db->insert_id;
		}

		public function now() {
			return current_time( 'mysql' );
		}

		public function time_to_date( $time ) {
			return gmdate( 'Y-m-d H:i:s', $time );
		}

		public function date_to_time( $date ) {
			return strtotime( $date . ' GMT' );
		}

		public function num_rows() {

			return self::$wp_db->num_rows;
		}

		public function get_specific_rows( $where_key, $where_value ) {
			$results = self::$wp_db->get_results( 'SELECT * FROM ' . self::_table() . " WHERE $where_key = '$where_value'", ARRAY_A );

			return $results;
		}

		public function get_specific_columns( $column_names, $where_pairs ) {

			$sql_query = 'SELECT ';

			if ( is_array( $column_names ) && count( $column_names ) > 0 ) {
				foreach ( $column_names as $column_name => $column_alias ) {
					$sql_query .= "$column_name as $column_alias ";
				}
			}

			$sql_query .= 'FROM ' . self::_table();

			if ( is_array( $where_pairs ) && count( $where_pairs ) > 0 ) {
				$sql_query .= ' WHERE 1 = 1';
				foreach ( $where_pairs as $where_key => $where_value ) {
					$sql_query .= ' AND ' . $where_key . " = '$where_value'";
				}
			}

			$results = self::$wp_db->get_row( $sql_query, ARRAY_A );

			return $results;
		}

		public function get_results( $query ) {

			$query   = str_replace( '{table_name}', self::_table(), $query );
			$query   = str_replace( '{table_name_meta}', self::_tablemeta(), $query );

			$results = self::$wp_db->get_results( $query, ARRAY_A );

			return $results;
		}

		public function get_row( $query ) {

			$query   = str_replace( '{table_name}', self::_table(), $query );
			$query   = str_replace( '{table_name_meta}', self::_tablemeta(), $query );
			$results = self::$wp_db->get_row( $query, ARRAY_A );

			return $results;
		}


		public function delete_multiple( $query ) {

			$query = str_replace( '{table_name}', self::_table(), $query );
			$query = str_replace( '{table_name_meta}', self::_tablemeta(), $query );
			self::$wp_db->query( $query );
		}

		public function update_multiple( $query ) {

			$query = str_replace( '{table_name}', self::_table(), $query );
			$query = str_replace( '{table_name_meta}', self::_tablemeta(), $query );
			self::$wp_db->query( $query );
		}

		public function get_last_error() {
			return self::$wp_db->error();
		}


		/**
		 * @param $contact_id
		 * @param $meta_key
		 * @param $meta_value
		 *
		 * @return int
		 */
		public function update_meta( $contact_id, $meta_key, $meta_value ) {
			include_once plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/db/class-wffn-db-tables.php';
			$tables = WFFN_DB_Tables::get_instance();
			$tables->define_tables();
			update_metadata( 'bwf_funnel', $contact_id, $meta_key, $meta_value );
		}

		/**
		 * Get contact meta for a given contact id and meta key
		 *
		 * @param $object_id
		 *
		 * @return string|null
		 */
		public function get_meta( $object_id, $meta_key = '' ) {
			include_once plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/db/class-wffn-db-tables.php';
			$tables = WFFN_DB_Tables::get_instance();
			$tables->define_tables();

			return get_metadata( 'bwf_funnel', $object_id, $meta_key, true );
		}

	}

	WFFN_Funnels_DB::init();
}
