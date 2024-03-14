<?php
/**
 * Name:    Dev4Press\v43\Core\Plugins\DB
 * Version: v4.3
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4Press Library
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v43\Core\Plugins;

use Dev4Press\v43\Core\DateTime;
use Dev4Press\v43\Core\Quick\Sanitize;
use wpdb;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string comments
 * @property string commentmeta
 * @property string posts
 * @property string postmeta
 * @property string options
 * @property string terms
 * @property string termmeta
 * @property string term_taxonomy
 * @property string term_relationships
 * @property string users
 * @property string usermeta
 * @property string site
 * @property string sitemeta
 * @property string blogs
 * @property string blogmeta
 * @property string blog_versions
 * @property string base_prefix
 * @property string prefix
 * @property int    blogid
 * @property int    insert_id
 * @property int    rows_affected
 * @method string|void            prepare( $query, ...$args )
 * @method int|bool               query( $query )
 * @method array|object|null      get_results( $query = null, $output = OBJECT )
 * @method array|object|null|void get_row( $query = null, $output = OBJECT, $y = 0 )
 * @method array                  get_col( $query, $x = 0 )
 * @method string|null            get_var( $query = null, $x = 0, $y = 0 )
 * @method int|false              insert( $table, $data, $format = null )
 * @method int|false              update( $table, $data, $where, $format = null, $where_format = null )
 * @method int|false              delete( $table, $where, $where_format = null )
 * @method void                   flush()
 */
abstract class DBLite {
	protected $plugin_name = 'dev4press-library';
	protected $plugin_instance = 'db';

	protected static $_queries_log = array();
	protected $_methods_log = array( 'query', 'get_results', 'get_row', 'get_var', 'insert', 'update', 'delete' );

	public function __construct() {
	}

	public function init() {
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function __get( $name ) {
		if ( property_exists( $this->wpdb(), $name ) || isset( $this->wpdb()->$name ) ) {
			return $this->wpdb()->$name;
		}

		return false;
	}

	public function __call( $name, $arguments ) {
		$result = null;

		if ( method_exists( $this->wpdb(), $name ) ) {
			$result = call_user_func_array( array( $this->wpdb(), $name ), $arguments );

			if ( in_array( $name, $this->_methods_log ) ) {
				$this->_copy_logged_query();
			}
		}

		return $result;
	}

	/**
	 * @return wpdb
	 *
	 * @global wpdb $wpdb
	 */
	public function wpdb() : wpdb {
		global $wpdb;

		return $wpdb;
	}

	public function clean_ids_list( $ids ) : array {
		return Sanitize::ids_list( $ids );
	}

	public function prepare_in_list( array $items, string $mod = '%s' ) : ?string {
		if ( empty( $items ) ) {
			return '';
		}

		$replace = array_fill( 0, count( $items ), $mod );

		return $this->prepare( join( ', ', $replace ), $items );
	}

	public function build_query( array $sql, bool $calc_found_rows = true ) : string {
		$defaults = array(
			'select' => array(),
			'from'   => array(),
			'where'  => array(),
			'group'  => '',
			'order'  => '',
			'limit'  => '',
		);

		$sql = wp_parse_args( $sql, $defaults );

		$_build = 'SELECT' . ( $calc_found_rows ? ' SQL_CALC_FOUND_ROWS' : '' );
		$_build .= ' ' . join( ', ', $sql['select'] );
		$_build .= ' FROM ' . join( ' ', $sql['from'] );

		if ( ! empty( $sql['where'] ) ) {
			$_build .= ' WHERE ' . join( ' AND ', $sql['where'] );
		}

		if ( ! empty( $sql['group'] ) ) {
			$_build .= ' GROUP BY ' . $sql['group'];
		}

		if ( ! empty( $sql['order'] ) ) {
			$_build .= ' ORDER BY ' . $sql['order'];
		}

		if ( ! empty( $sql['limit'] ) ) {
			$_build .= ' LIMIT ' . $sql['limit'];
		}

		return $_build;
	}

	public function run( string $query, string $output = OBJECT ) {
		$_value = $this->get_results( $query, $output );

		$this->_copy_logged_query();

		return $_value;
	}

	public function run_and_index( string $query, string $field, string $output = OBJECT ) : array {
		$raw = $this->get_results( $query, $output );

		$_value = $this->index( $raw, $field );

		$this->_copy_logged_query();

		return $_value;
	}

	public function get_number_int( string $query, int $default = 0 ) : int {
		$_value = $this->get_var( $query );

		return is_null( $_value ) ? $default : absint( $_value );
	}

	public function delete_by_ids( string $table, string $field, array $ids = array() ) {
		$ids = $this->clean_ids_list( $ids );

		if ( empty( $ids ) ) {
			return false;
		}

		$sql = "DELETE FROM `$table` WHERE $field IN (" . $this->prepare_in_list( $ids, '%d' ) . ')';

		return $this->query( $sql );
	}

	public function insert_meta_data( string $table, string $column, int $id, array $meta, bool $skip_empty_values = false, bool $json_serialization = false ) {
		foreach ( $meta as $key => $value ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				$insert = $json_serialization ? json_encode( $value ) : maybe_serialize( $value );
			} else {
				$insert = $value;
			}

			$add = true;
			if ( $skip_empty_values && empty( $value ) && $value !== 0 ) {
				$add = false;
			}

			if ( $add ) {
				$this->insert(
					$table,
					array(
						$column      => $id,
						'meta_key'   => $key, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						'meta_value' => $insert, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
					),
					array( '%d', '%s', '%s' )
				);
			}
		}
	}

	public function pluck( $list, $field, $index_key = null ) : array {
		return wp_list_pluck( $list, $field, $index_key );
	}

	public function index( $list, $field, bool $is_integer = true ) : array {
		$new = array();

		foreach ( $list as $item ) {
			$id = is_array( $item ) ? $item[ $field ] : $item->$field;

			if ( $is_integer ) {
				$id = absint( $id );
			}

			$new[ $id ] = $item;
		}

		return $new;
	}

	public function mysqli() : bool {
		$use_mysqli = false;

		if ( function_exists( 'mysqli_connect' ) ) {
			$use_mysqli = true;

			if ( defined( 'WP_USE_EXT_MYSQL' ) ) {
				$use_mysqli = ! WP_USE_EXT_MYSQL;
			}
		}

		return $use_mysqli;
	}

	public function prefix() : string {
		return $this->wpdb()->prefix;
	}

	public function base_prefix() : string {
		return $this->wpdb()->base_prefix;
	}

	public function rows_affected() : int {
		return $this->wpdb()->rows_affected;
	}

	public function blog_id() : int {
		return $this->wpdb()->blogid;
	}

	public function get_insert_id() : int {
		return $this->wpdb()->insert_id;
	}

	public function get_found_rows() : int {
		return absint( $this->get_var( 'SELECT FOUND_ROWS()' ) );
	}

	public function save_queries() : bool {
		return defined( 'SAVEQUERIES' ) && SAVEQUERIES;
	}

	public function enable_save_queries() : bool {
		if ( ! defined( 'SAVEQUERIES' ) ) {
			define( 'SAVEQUERIES', true );

			return true;
		}

		return SAVEQUERIES === true;
	}

	public function log_get_queries() : array {
		return self::$_queries_log;
	}

	public function log_get_elapsed_time() {
		$time = 0;

		foreach ( self::$_queries_log as $q ) {
			$time += $q['time'];
		}

		return $time;
	}

	public function log_get_last_query( $what = 'sql' ) {
		if ( ! empty( self::$_queries_log ) ) {
			$id  = count( self::$_queries_log ) - 1;
			$log = self::$_queries_log[ $id ];

			return $log[ $what ] ?? $log;
		}

		return false;
	}

	public function timestamp( $gmt = true ) {
		return current_time( 'timestamp', $gmt );
	}

	public function datetime( $gmt = true ) {
		return current_time( 'mysql', $gmt );
	}

	public function check_table( $name ) : string {
		$row = $this->get_row( 'CHECK TABLE `' . $name . '`' );

		if ( ! is_null( $row ) ) {
			return (string) $row->Msg_text;
		} else {
			return '';
		}
	}

	public function analyze_table( $name ) {
		return $this->get_results( 'ANALYZE TABLE `' . $name . '`' );
	}

	public function alter_table_force( $name ) : array {
		$this->get_results( 'ALTER TABLE `' . $name . '` FORCE' );

		return array(
			'status' => 'OK',
		);
	}

	public function transient_query( $query, $key, $method, $output = OBJECT, $x = 0, $y = 0, $ttl = 86400 ) {
		$var = get_transient( $key );

		if ( $var === false ) {
			$var = $this->_run_transient_query( $query, $method, $output, $x, $y );

			if ( $var !== false ) {
				set_transient( $key, $var, $ttl );
			}
		}

		return $var;
	}

	public function transient_query_site( $query, $key, $method, $output = OBJECT, $x = 0, $y = 0, $ttl = 86400 ) {
		$var = get_site_transient( $key );

		if ( $var === false ) {
			$var = $this->_run_transient_query( $query, $method, $output, $x, $y );

			if ( $var !== false ) {
				set_site_transient( $key, $var, $ttl );
			}
		}

		return $var;
	}

	protected function _copy_logged_query() {
		if ( $this->save_queries() ) {
			$id = count( $this->wpdb()->queries ) - 1;

			if ( $id > - 1 && isset( $this->wpdb()->queries[ $id ] ) ) {
				$query = $this->wpdb()->queries[ $id ];

				self::$_queries_log[] = array(
					'sql'      => $query[0],
					'time'     => $query[1],
					'stack'    => $query[2],
					'start'    => $query[3],
					'data'     => $query[4],
					'id'       => $id,
					'plugin'   => $this->plugin_name,
					'instance' => $this->plugin_instance,
				);
			}
		}
	}

	protected function _run_transient_query( $query, $method, $output = OBJECT, $x = 0, $y = 0 ) {
		switch ( $method ) {
			case 'var':
				return $this->get_var( $query, $x, $y );
			case 'row':
				return $this->get_row( $query, $output, $y );
			case 'results':
				return $this->get_results( $query, $output );
		}

		return false;
	}

	/**
	 * @return float|int|mixed|null
	 * @deprecated Since 4.0, to be removed in 4.2
	 */
	public function gmt_offset() {
		_deprecated_function( __METHOD__, '4.0', '\Dev4Press\v43\Core\DateTime::instance()->offset()' );

		return DateTime::instance()->offset();
	}
}
