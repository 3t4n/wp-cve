<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since      1.5.0
 * @package    Piramid
 * @subpackage Courtres/includes
 * @author     Webmühle e.U. <office@webmuehle.at>
 */

class Courtres_Entity_Base {

	protected static $instance = null;
	private $id;
	private static $cnt;
	public const LIMIT = 1000;
	private $db_data;

	public function __construct( $id ) {
		$this->id      = $id;
		$this->db_data = self::get_by_id( $id );
	}

	public static function get_instance( $id ) {
		if ( ! empty( static::$instance ) ) {
			static::$instance = null;
		}
		static::$instance = new static( $id );
		return static::$instance;
	}

	public function get_id() {
		return $this->id;
	}

	/**
	 * @return array of data or empty array
	 */
	public function get_db_data() {
		return $this->db_data;
	}

	public static function get_charset_collate() {
		global $wpdb;
		return $wpdb->get_charset_collate();
	}

	// as in CourtresBase class
	protected function getTable( $table ) {
		global $wpdb;
		return "{$wpdb->prefix}courtres_{$table}";
	}

	public static function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . static::$table_name;
	}

	public static function get_child_db_fields() {
		return static::get_db_fields();
	}

	public static function get_db_fields_defaults() {
		return wp_list_pluck( self::get_child_db_fields(), 'default_value' );
	}

	public static function get_admin_table_columns() {
		$db_fields = static::get_db_fields();
		$columns   = array();
		foreach ( $db_fields as $key => $db_field ) {
			if ( $db_field['show_in_admin'] ) {
				$columns[ $db_field['code'] ] = $db_field;
			}
		}
		return $columns;
	}

	static function is_dbtable_column( array $params ) {
		global $wpdb;
		$defaults = array(
			'name' => false,
		);
		$params   = wp_parse_args( $params, $defaults );
		if ( $params['name'] ) {
			$dbname    = $wpdb->dbname;
			$sql       = sprintf( "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `table_name` = '%1\$s' AND `TABLE_SCHEMA` = '%2\$s' AND `COLUMN_NAME` = '%3\$s'", self::get_table_name(), $dbname, $params['name'] );
			$is_column = $wpdb->get_results( $sql, ARRAY_A );
			return $is_column;
		} else {
			return -1;
		}
	}

	static function add_dbtable_column( array $params ) {
		global $wpdb;
		$defaults = array(
			'name'  => false,
			'type'  => false,
			'after' => false,
		);
		$params   = wp_parse_args( $params, $defaults );
		if ( $params['name'] && $params['type'] ) {
			$dbname    = $wpdb->dbname;
			$is_column = self::is_dbtable_column( $params );
			if ( empty( $is_column ) ) {
				$sql = sprintf( 'ALTER TABLE `%1$s` ADD `%2$s` %3$s NULL DEFAULT NULL AFTER `%4$s`', self::get_table_name(), $params['name'], $params['type'], $params['after'] );
				$wpdb->query( $sql );
			}
		}
	}


	/**
	 * @return array $items or empty array
	 */
	static function get_list( $params = array() ) {
		 global $wpdb;
		$defaults = array(
			'n_page'      => 0,
			'limit'       => self::LIMIT,
			'where'       => false,
			'sql_where'   => false,
			'sort'        => self::get_table_name() . '.`id` DESC',
			'result_type' => 'ARRAY_A',
		);
		$params   = wp_parse_args( $params, $defaults );
		$offset   = ( $params['n_page'] ? $params['n_page'] - 1 : $params['n_page'] ) * $params['limit'];
		$sql_sort = $params['sort'] ? ' ORDER BY ' . $params['sort'] : '';

		if ( $params['sql_where'] ) {
			$sql_where = $params['sql_where'];
		} else {
			$sql_where = '';
			if ( $params['where'] ) {
				$conditions = isset( $params['where']['conditions'] ) ? $params['where']['conditions'] : false;
				if ( $conditions ) {
					$logic      = isset( $params['where']['logic'] ) ? $params['where']['logic'] : 'AND';
					$conditions = array_map( 'self::add_aliases', $conditions );
					$sql_where  = ' WHERE ' . implode( ' ' . $logic . ' ', $conditions );
				}
			}
		}

		$sql_join        = '';
		$sql_select_more = '';

		$items = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT ' . self::get_table_name() . '.*'
				. $sql_select_more
				. ' FROM ' . self::get_table_name()
				. $sql_join
				. $sql_where
				. $sql_sort
				. ' LIMIT %d OFFSET %d',
				$params['limit'],
				$offset
			),
			$params['result_type']
		);

		// fppr($wpdb->last_query, __FILE__.' $wpdb->last_query');
		// fppr($items, __FILE__.' $items');

		// to count a quantity for paging
		$res       = $wpdb->get_row(
			'SELECT COUNT(*) AS cnt '
			. ' FROM ' . self::get_table_name()
			. $sql_join
			. $sql_where
		);
		self::$cnt = $res ? $res->cnt : 0;
				return $items ? $items : array();
	}


	/**
	 * get last query in get_list function
	 *
	 * @return int $cnt or 0
	 */
	static function get_last_query_length() {
		return self::$cnt;
	}


	/**
	 * get count of query
	 *
	 * @return int $cnt or 0
	 */
	static function count( $where = array() ) {
		global $wpdb;
		$defaults = array(
			'conditions' => false,
			'logic'      => false,
			'sql_where'  => false,
		);
		$where    = wp_parse_args( $where, $defaults );

		if ( $where['sql_where'] ) {
			$sql_where = $where['sql_where'];
		} else {
			$sql_where  = '';
			$conditions = $where['conditions'] ? $where['conditions'] : false;
			if ( $conditions ) {
				$logic     = $where['logic'] ? $where['logic'] : 'AND';
				$sql_where = ' WHERE ' . implode( ' ' . $logic . ' ', $conditions );
			}
		}

				$res = $wpdb->get_row(
					'SELECT COUNT(*) AS cnt '
					. ' FROM ' . self::get_table_name()
					. $sql_where
				);
		// fppr($wpdb->last_query, __FILE__.' $wpdb->last_query');
		// fppr($res, __FILE__.' $res');

		$cnt = $res ? $res->cnt : 0;
		return $cnt;
	}


	/**
	 * @return array $item or empty array
	 */
	static function get_by_id( $id ) {
		$items      = self::get_list(
			$params = array(
				'where' => array(
					'conditions' => array( 'ID = ' . $id ),
				),
			)
		);
		return $items && is_array( $items ) ? $items[0] : array();
	}


	/**
	 * add aliases to sql queries
	 */
	static function add_aliases( $item ) {
		global $wpdb;
		if ( strpos( $item, '.' ) === false ) {
			$result = self::get_table_name() . '.' . $item;
		} else {
			$result = $item;
		}
		return $result;
	}


	/**
	 *  Add
	 *
	 *  @param array with keys = db fields,
	 * @return id of inserted row or false
	 */
	static function insert( array $args ) {
		global $wpdb;

		$defaults = self::get_db_fields_defaults();
		$args     = wp_parse_args( $args, $defaults );
		$args     = array_intersect_key( $args, $defaults );

		$res = $wpdb->insert( self::get_table_name(), $args );
		if ( $res ) {
			$id = $wpdb->insert_id;
			return $id;
		} else {
			return false;
		}
	}


	/**
	 *  Update
	 *
	 *  @param array with keys = data, where, format, where_format
	 *  look $wpdb->update() params for details
	 * @return true||false
	 */
	static function update( array $args ) {
		global $wpdb;

		$defaults     = array(
			'data'         => false,
			'where'        => false,
			'format'       => false,
			'where_format' => false,
		);
		$args         = wp_parse_args( $args, $defaults );
		$args['data'] = array_intersect_key( $args['data'], self::get_db_fields_defaults() );

		$res = $wpdb->update( self::get_table_name(), $args['data'], $args['where'], $args['format'], $args['where_format'] );
		if ( $res !== false ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 *  Delete rows
	 *
	 * @return true||false
	 */
	static function delete( array $args, $logic = 'OR' ) {
		global $wpdb;
		$result     = false;
		$sql_wheres = false;
		if ( $args ) {
			if ( isset( $args['where'] ) ) {
				$conditions = isset( $args['where']['conditions'] ) ? $args['where']['conditions'] : false;
				if ( $conditions ) {
					$logic      = isset( $args['where']['logic'] ) ? $args['where']['logic'] : 'AND';
					$sql_wheres = $args['where']['conditions'];
				}
			} else {
				foreach ( $args as $key => $value ) {
					$sql_wheres[] = sprintf( "(`%s` = '%s')", $key, $value );
				}
			}
			if ( $sql_wheres ) {
				$sql = sprintf( 'DELETE FROM `%s` WHERE (%s)', self::get_table_name(), implode( ' ' . $logic . ' ', $sql_wheres ) );
				$res = $wpdb->query( $sql );
			}
			if ( $res ) {
				$result = true;
			}
		}
		return $result;
	}


	/**
	 * Delete by id
	 *
	 * @return true||false
	 */
	public function delete_by_id() {
		return self::delete( array( 'id' => $this->get_id() ) );
	}


	/**
	 * Delete by id
	 *
	 * @return true||false
	 */
	public static function static_delete_by_id( int $id ) {
		return self::delete( array( 'id' => $id ) );
	}


	/**
	 * Get setting "Half-hour reservation"
	 *
	 * @return bool
	 */
	public function ishalfhour() {
		global $wpdb;
		$table_name       = $this->getTable( 'settings' );
		$option_half_hour = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'half_hour_reservation'" );
		if ( ! isset( $option_half_hour ) ) {
			return false;
		}
		return $option_half_hour->option_value === '1' ? true : false;
	}


}
