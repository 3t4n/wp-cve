<?php
/**
 * Transactions query class
 *
 * This class should be used for querying general things.
 *
 * @package     Church_Tithe_WP
 * @subpackage  Classes/General Query
 * @copyright   Copyright (c) 2018, Church Tithe WP
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Church_Tithe_WP_General_Query Class
 *
 * @since 1.0.0
 */
class Church_Tithe_WP_General_Query {

	/**
	 * SQL for database query.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	public $request;

	/**
	 * Date query container.
	 *
	 * @since  1.0.0
	 * @var    object WP_Date_Query
	 */
	public $date_query = false;

	/**
	 * Meta query container.
	 *
	 * @since  1.0.0
	 * @var    object WP_Meta_Query
	 */
	public $meta_query = false;

	/**
	 * Query vars set by the user.
	 *
	 * @since  1.0.0
	 * @var    array
	 */
	public $query_vars;

	/**
	 * Default values for query vars.
	 *
	 * @since  1.0.0
	 * @var    array
	 */
	public $query_var_defaults;

	/**
	 * List of items located by the query.
	 *
	 * @since  1.0.0
	 * @var    array
	 */
	public $items;

	/**
	 * The amount of found items for the current query.
	 *
	 * @since  1.0.0
	 * @var    int
	 */
	public $found_items = 0;

	/**
	 * The number of pages.
	 *
	 * @since  1.0.0
	 * @var    int
	 */
	public $max_num_pages = 0;

	/**
	 * SQL query clauses.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var    array
	 */
	protected $sql_clauses = array(
		'select'  => '',
		'from'    => '',
		'where'   => array(),
		'groupby' => '',
		'orderby' => '',
		'limits'  => '',
	);

	/**
	 * Metadata query clauses.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var array
	 */
	protected $meta_query_clauses = array();

	/**
	 * Church_Tithe_WP_DB_Transactions instance.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var Church_Tithe_WP_DB_Transactions
	 */
	protected $church_tithe_wp_db_items;

	/**
	 * The name of our database table.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var    string
	 */
	protected $table_name;

	/**
	 * The meta type.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var    string
	 */
	protected $meta_type;

	/**
	 * The name of the primary column.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var    string
	 */
	protected $primary_key;

	/**
	 * The name of the date column.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var    string
	 */
	protected $date_key;

	/**
	 * The name of the cache group.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @var    string
	 */
	protected $cache_group;

	/**
	 * Constructor.
	 *
	 * Sets up the item query defaults and optionally runs a query.
	 *
	 * @since  1.0.0
	 *
	 * @param string|array $query {
	 *     Optional. Array or query string of item query parameters. Default empty.
	 *
	 *     @type int          $number         Maximum number of items to retrieve. Default 20.
	 *     @type int          $offset         Number of items to offset the query. Default 0.
	 *     @type string|array $orderby        Transactions status or array of statuses. To use 'meta_value'
	 *                                        or 'meta_value_num', `$meta_key` must also be provided.
	 *                                        To sort by a specific `$meta_query` clause, use that
	 *                                        clause's array key. Accepts 'id', 'user_id', 'name',
	 *                                        'email', 'payment_ids', 'purchase_value', 'purchase_count',
	 *                                        'notes', 'date_created', 'meta_value', 'meta_value_num',
	 *                                        the value of `$meta_key`, and the array keys of `$meta_query`.
	 *                                        Also accepts false, an empty array, or 'none' to disable the
	 *                                        `ORDER BY` clause. Default 'id'.
	 *     @type string       $order          How to order retrieved items. Accepts 'ASC', 'DESC'.
	 *                                        Default 'DESC'.
	 *     @type string|array $columns_values_included        String or array of item IDs to include. Default empty.
	 *     @type string|array $columns_values_excluded        String or array of item IDs to exclude. Default empty.
	 *                                        empty.
	 *     @type string       $search         Search term(s) to retrieve matching items for. Searches
	 *                                        through item names. Default empty.
	 *     @type string|array $search_columns Columns to search using the value of `$search`. Default 'name'.
	 *     @type string       $meta_key       Include items with a matching item meta key.
	 *                                        Default empty.
	 *     @type string       $meta_value     Include items with a matching item meta value.
	 *                                        Requires `$meta_key` to be set. Default empty.
	 *     @type array        $meta_query     Meta query clauses to limit retrieved items by.
	 *                                        See `WP_Meta_Query`. Default empty.
	 *     @type array        $date_query     Date query clauses to limit retrieved items by.
	 *                                        See `WP_Date_Query`. Default empty.
	 *     @type bool         $count          Whether to return a count (true) instead of an array of
	 *                                        item objects. Default false.
	 *     @type bool         $no_found_rows  Whether to disable the `SQL_CALC_FOUND_ROWS` query.
	 *                                        Default true.
	 * }
	 * @param array        $church_tithe_wp_db_items Data about the DB being queried.
	 */
	public function __construct( $query = '', $church_tithe_wp_db_items = null ) {
		if ( $church_tithe_wp_db_items ) {
			$this->church_tithe_wp_db_items = $church_tithe_wp_db_items;
		} else {
			return false;
		}

		$this->table_name  = $this->church_tithe_wp_db_items->table_name;
		$this->meta_type   = $this->church_tithe_wp_db_items->meta_type;
		$this->primary_key = $this->church_tithe_wp_db_items->primary_key;
		$this->date_key    = $this->church_tithe_wp_db_items->date_key;
		$this->cache_group = $this->church_tithe_wp_db_items->cache_group;

		$this->query_var_defaults = array(
			'number'        => 20,
			'offset'        => 0,
			'orderby'       => 'id',
			'order'         => 'DESC',
			'include'       => '',
			'exclude'       => '',
			'users_include' => '',
			'users_exclude' => '',
			'search'        => '',
			'meta_key'      => '',
			'meta_value'    => '',
			'meta_query'    => '',
			'date_query'    => null,
			'count'         => false,
			'no_found_rows' => true,
		);

		if ( ! empty( $query ) ) {
			$this->query( $query );
		}
	}

	/**
	 * Sets up the query for retrieving items.
	 *
	 * @since  1.0.0
	 *
	 * @see Church_Tithe_WP_General_Query::__construct()
	 *
	 * @param string|array $query Array or query string of parameters. See Church_Tithe_WP_General_Query::__construct().
	 * @return array|int List of items, or number of items when 'count' is passed as a query var.
	 */
	public function query( $query ) {

		$this->query_vars = wp_parse_args( $query );
		$this->parse_query();

		// Create a key using the query vars, which we'll use to store the cached results of this query on this page load.
		$key = hash( 'sha256', wp_json_encode( $this->query_vars ) );

		// Append the last changed date to the key as well.
		$last_changed = $this->church_tithe_wp_db_items->get_last_changed();
		$cache_key    = "query:$key:$last_changed";

		// Store these results so we don't run the exact same query twice on the same page load.
		$cache_value = wp_cache_get( $cache_key, $this->cache_group );

		if ( false === $cache_value ) {
			$items = $this->query_items();

			if ( $items ) {
				$this->set_found_items();
			}

			$cache_value = array(
				'items'       => $items,
				'found_items' => $this->found_items,
			);
			wp_cache_add( $cache_key, $cache_value, $this->cache_group );
		} else {
			$items             = $cache_value['items'];
			$this->found_items = $cache_value['found_items'];
		}

		if ( $this->found_items && $this->query_vars['number'] ) {
			$this->max_num_pages = ceil( $this->found_items / $this->query_vars['number'] );
		}

		// If querying for a count only, there's nothing more to do.
		if ( $this->query_vars['count'] ) {

			if ( isset( $items[0] ) ) {
				// $items is actually a count in this case.
				return intval( $items[0]->count );
			}
			return 0;
		}

		$this->items = $items;

		return $this->items;

	}

	/**
	 * Parses arguments passed to the item query with default query parameters.
	 *
	 * @access protected
	 * @since  1.0.0
	 */
	protected function parse_query() {
		$this->query_vars = wp_parse_args( $this->query_vars, $this->query_var_defaults );

		if ( $this->query_vars['number'] < 1 ) {
			$this->query_vars['number'] = 999999999999;
		}

		$this->query_vars['offset'] = absint( $this->query_vars['offset'] );

		if ( ! empty( $this->query_vars['date_query'] ) && is_array( $this->query_vars['date_query'] ) ) {
			$this->date_query = new WP_Date_Query( $this->query_vars['date_query'], $this->table_name . '.' . $this->date_key );
		}

		$this->meta_query = new WP_Meta_Query();
		$this->meta_query->parse_query_vars( $this->query_vars );

		if ( ! empty( $this->meta_query->queries ) ) {
			$this->meta_query_clauses = $this->meta_query->get_sql( $this->meta_type, $this->table_name, $this->primary_key, $this );
		}

	}

	/**
	 * Runs a database query to retrieve items.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @return @return array|int List of items, or number of items when 'count' is passed as a query var.
	 */
	protected function query_items() {
		global $wpdb;

		$fields = $this->construct_request_fields();
		$join   = $this->construct_request_join();

		$this->sql_clauses['where'] = $this->construct_request_where();

		$orderby = $this->construct_request_orderby();
		$limits  = $this->construct_request_limits();
		$groupby = $this->construct_request_groupby();

		$found_rows = ! $this->query_vars['no_found_rows'] ? 'SQL_CALC_FOUND_ROWS' : '';

		$where = implode( ' AND ', $this->sql_clauses['where'] );

		if ( $where ) {
			$where = "WHERE $where";
		}

		if ( $orderby ) {
			$orderby = "ORDER BY $orderby";
		}

		if ( $groupby ) {
			$groupby = "GROUP BY $groupby";
		}

		$this->sql_clauses['select']  = "SELECT $found_rows $fields";
		$this->sql_clauses['from']    = "FROM $this->table_name $join";
		$this->sql_clauses['groupby'] = $groupby;
		$this->sql_clauses['orderby'] = $orderby;
		$this->sql_clauses['limits']  = $limits;

		$this->request = "{$this->sql_clauses['select']} {$this->sql_clauses['from']} {$where} {$this->sql_clauses['groupby']} {$this->sql_clauses['orderby']} {$this->sql_clauses['limits']}";

		$results = $wpdb->get_results( $this->request );

		return $results;
	}

	/**
	 * Populates the found_items property for the current query if the limit clause was used.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 */
	protected function set_found_items() {
		global $wpdb;

		if ( $this->query_vars['number'] && ! $this->query_vars['no_found_rows'] ) {
			$this->found_items = (int) $wpdb->get_var( $found_items_query );
		}
	}

	/**
	 * Constructs the fields segment of the SQL request.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @return string SQL fields segment.
	 */
	protected function construct_request_fields() {
		if ( $this->query_vars['count'] ) {
			return "COUNT($this->primary_key) AS count";
		}

		return "$this->table_name.*";
	}

	/**
	 * Constructs the join segment of the SQL request.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @return string SQL join segment.
	 */
	protected function construct_request_join() {
		$join = '';

		if ( ! empty( $this->meta_query_clauses['join'] ) ) {
			$join .= $this->meta_query_clauses['join'];
		}

		if ( ! empty( $this->query_vars['email'] ) && ! is_array( $this->query_vars['email'] ) ) {
			$meta_table = _get_meta_table( $this->meta_type );

			$join_type = false !== strpos( $join, 'INNER JOIN' ) ? 'INNER JOIN' : 'LEFT JOIN';

			$join .= " $join_type $meta_table AS email_mt ON $this->table_name.$this->primary_key = email_mt.{$this->meta_type}_id";
		}

		return $join;
	}

	/**
	 * Constructs the where segment of the SQL request.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @return string SQL where segment.
	 */
	protected function construct_request_where() {
		global $wpdb;

		$where = array();

		// If we are querying by a specific column and column value.
		if ( ! empty( $this->query_vars['column_values_included'] ) ) {

			// Construct a where clause for each column passed in.
			foreach ( $this->query_vars['column_values_included'] as $column_slug => $column_values ) {

				// Wrap strings in quotes.
				if ( is_array( $column_values ) ) {
					foreach ( $column_values as $column_value ) {
						if ( 'string' === gettype( $column_value ) ) {
							$column_value             = '"' . $column_value . '"';
							$wheres_for_this_column[] = "$column_slug IN ( $column_value )";
						}
					}

					// Add of the options to accept for this column.
					$where[ $column_slug ] = '(' . implode( ' OR ', $wheres_for_this_column ) . ')';

				} else {
					if ( 'string' === gettype( $column_values ) ) {
						$column_values = '"' . $column_values . '"';
					}

					$where[ $column_slug ] = "$column_slug IN ( $column_values )";

				}
			}
		}

		// If we are querying by a specific column and column value to exclude.
		if ( ! empty( $this->query_vars['column_values_excluded'] ) ) {

			// Construct a where clause for each column passed in.
			foreach ( $this->query_vars['column_values_excluded'] as $column_slug => $column_values ) {

				// Wrap strings in quotes.
				if ( is_array( $column_values ) ) {
					foreach ( $column_values as $column_value ) {
						if ( 'string' === gettype( $column_value ) ) {
							$column_value             = '"' . $column_value . '"';
							$wheres_for_this_column[] = "$column_slug IN ( $column_value )";
						}
					}

					// Add of the options to accept for this column.
					$where[ $column_slug ] = '(' . implode( ' OR ', $wheres_for_this_column ) . ')';
				} else {
					if ( 'string' === gettype( $column_values ) ) {
						$column_values = '"' . $column_values . '"';
					}
				}

				$where[ $column_slug ] = "$column_slug NOT IN ( $column_values )";
			}
		}

		if ( strlen( $this->query_vars['search'] ) ) {
			if ( ! empty( $this->query_vars['search_columns'] ) ) {
				$search_columns = array_map( 'sanitize_key', (array) $this->query_vars['search_columns'] );
			} else {
				$search_columns = array( 'user_id' );
			}

			$where['search'] = $this->get_search_sql( $this->query_vars['search'], $search_columns );
		}

		if ( $this->date_query ) {
			$where['date_query'] = preg_replace( '/^\s*AND\s*/', '', $this->date_query->get_sql() );
		}

		return $where;
	}

	/**
	 * Constructs the orderby segment of the SQL request.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @return string SQL orderby segment.
	 */
	protected function construct_request_orderby() {
		if ( in_array( $this->query_vars['orderby'], array( 'none', array(), false ), true ) ) {
			return '';
		}

		if ( empty( $this->query_vars['orderby'] ) ) {
			return $this->primary_key . ' ' . $this->parse_order_string( $this->query_vars['order'] );
		}

		if ( is_string( $this->query_vars['orderby'] ) ) {
			$ordersby = array( $this->query_vars['orderby'] => $this->query_vars['order'] );
		} else {
			$ordersby = $this->query_vars['orderby'];
		}

		$orderby_array = array();

		foreach ( $ordersby as $orderby => $order ) {
			$parsed_orderby = $this->parse_orderby_string( $orderby );
			if ( ! $parsed_orderby ) {
				continue;
			}

			$parsed_order = $this->parse_order_string( $order, $orderby );

			if ( $parsed_order ) {
				$orderby_array[] = $parsed_orderby . ' ' . $parsed_order;
			} else {
				$orderby_array[] = $parsed_orderby;
			}
		}

		return implode( ', ', $orderby_array );
	}

	/**
	 * Constructs the limits segment of the SQL request.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @return string SQL limits segment.
	 */
	protected function construct_request_limits() {
		if ( $this->query_vars['number'] ) {
			if ( $this->query_vars['offset'] ) {
				return "LIMIT {$this->query_vars['offset']},{$this->query_vars['number']}";
			}

			return "LIMIT {$this->query_vars['number']}";
		}

		return '';
	}

	/**
	 * Constructs the groupby segment of the SQL request.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @return string SQL groupby segment.
	 */
	protected function construct_request_groupby() {
		if ( ! empty( $this->meta_query_clauses['join'] ) || ( ! empty( $this->query_vars['email'] ) && ! is_array( $this->query_vars['email'] ) ) ) {
			return "$this->table_name.$this->primary_key";
		}

		return '';
	}

	/**
	 * Used internally to generate an SQL string for searching across multithele columns.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @global wpdb  $wpdb WordPress database abstraction object.
	 *
	 * @param string $string  Search string.
	 * @param array  $columns Columns to search.
	 * @return string Search SQL.
	 */
	protected function get_search_sql( $string, $columns ) {
		global $wpdb;

		// If we are searching for multithele values, separated by *.
		if ( false !== strpos( $string, '*' ) ) {

			$likes = explode( '*', $string );

		} else {

			$likes = array();

			// If we are searching for a single value.
			$likes[] = '%' . $wpdb->esc_like( $string ) . '%';
		}

		$searches = array();

		// Loop through each column we are searching.
		foreach ( $columns as $column ) {

			// Loop through each value we are searching this column for.
			foreach ( $likes as $like ) {

				// Don't include searches for blank values.
				if ( empty( $like ) ) {
					continue;
				}

				// Escape the value we are searching for.
				$like = '%' . $wpdb->esc_like( $like ) . '%';

				// Add the search to our list of searches for this column.
				$searches[] = $wpdb->prepare( "$column LIKE %s", $like );

			}
		}

		$search_query = '(' . implode( ' OR ', $searches ) . ')';

		return $search_query;
	}

	/**
	 * Parses a single orderby string.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @param string $orderby Orderby string.
	 * @return string Parsed orderby string to use in the SQL request, or an empty string.
	 */
	protected function parse_orderby_string( $orderby ) {
		if ( 'include' === $orderby ) {
			if ( empty( $this->query_vars['include'] ) ) {
				return '';
			}

			$ids = implode( ',', wp_parse_id_list( $this->query_vars['include'] ) );

			return "FIELD( $this->table_name.$this->primary_key, $ids )";
		}

		if ( ! empty( $this->meta_query_clauses['where'] ) ) {
			$meta_table = _get_meta_table( $this->meta_type );

			if ( $this->query_vars['meta_key'] === $orderby || 'meta_value' === $orderby ) {
				return "$meta_table.meta_value";
			}

			if ( 'meta_value_num' === $orderby ) {
				return "$meta_table.meta_value+0";
			}

			$meta_query_clauses = $this->meta_query->get_clauses();

			if ( isset( $meta_query_clauses[ $orderby ] ) ) {
				return sprintf( 'CAST(%s.meta_value AS %s)', esc_sql( $meta_query_clauses[ $orderby ]['alias'] ), esc_sql( $meta_query_clauses[ $orderby ]['cast'] ) );
			}
		}

		$allowed_keys = $this->get_allowed_orderby_keys();

		if ( in_array( $orderby, $allowed_keys, true ) ) {
			/* This column needs special handling here. */
			if ( 'purchase_value' === $orderby ) {
				return "$this->table_name.purchase_value+0";
			}

			return "$this->table_name.$orderby";
		}

		return '';
	}

	/**
	 * Parses a single order string.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @param  string $order Order string.
	 * @param  string $orderby Order string.
	 * @return string Parsed order string to use in the SQL request, or an empty string.
	 */
	protected function parse_order_string( $order, $orderby ) {
		if ( 'include' === $orderby ) {
			return '';
		}

		if ( ! is_string( $order ) || empty( $order ) ) {
			return 'DESC';
		}

		if ( 'ASC' === strtoupper( $order ) ) {
			return 'ASC';
		} else {
			return 'DESC';
		}
	}

	/**
	 * Returns the basic allowed keys to use for the orderby clause.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @return array Allowed keys.
	 */
	protected function get_allowed_orderby_keys() {
		return array_keys( $this->church_tithe_wp_db_items->get_columns() );
	}
}
