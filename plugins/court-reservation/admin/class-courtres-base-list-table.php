<?php

// WP_List_Table is not loaded automatically so we need to load it in our application
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Courtres_Base_List_Table extends WP_List_Table {

	/**
	 * Prepare the items for the table to process
	 *
	 * @return Void
	 */

	private $columns          = array();
	private $sortable_columns = array();
	private $data             = array();
	private $limit            = 10;

	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->get_table_data();

		usort( $data, array( &$this, 'sort_data' ) );

		$perPage     = $this->get_limit();
		$currentPage = $this->get_pagenum();
		$totalItems  = count( $data );

		$this->set_pagination_args(
			array(
				'total_items' => $totalItems,
				'per_page'    => $perPage,
			)
		);

		$data = array_slice( $data, ( ( $currentPage - 1 ) * $perPage ), $perPage );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}

	/**
	 * Set the columns head
	 *
	 * @param array of column titles
	 */
	public function set_columns( array $columns ) {
		$this->columns = $columns;
	}

	/**
	 * Set number items on the page for paging
	 *
	 * @param int
	 */
	public function set_limit( int $limit ) {
		$this->limit = $limit;
	}

	/**
	 * Get number items on the page for paging
	 *
	 * @param int
	 */
	private function get_limit() {
		return $this->limit;
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return Array
	 */
	public function get_columns() {
		 return $this->columns;
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Set the sortable columns
	 *
	 * @param array sortable_columns for example: sortable_columns = array('title' => array('title', false))
	 */
	public function set_sortable_columns( array $sortable_columns ) {
		$this->sortable_columns = $sortable_columns;
	}

	/**
	 * Define the sortable columns
	 *
	 * @return Array
	 */
	public function get_sortable_columns() {
		return $this->sortable_columns;
	}

	/**
	 * Set the table data
	 *
	 * @return Array
	 */
	public function set_table_data( array $data ) {
		$this->data = $data;
	}

	/**
	 * Get the table data
	 *
	 * @return Array
	 */
	private function get_table_data() {
		 return $this->data;
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  Array  $item        Data
	 * @param  String $column_name - Current column name
	 *
	 * @return Mixed
	 */
	public function column_default( $item, $column_name ) {
		 return $item[ $column_name ];
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 *
	 * @return Mixed
	 */
	private function sort_data( $a, $b ) {
		// Set defaults
		$sortable_keys = array_keys( $this->sortable_columns );
		$orderby       = 'id';
		$order         = 'desc';

		// If orderby is set, use this as the sort column
		if ( ! empty( $_GET['orderby'] ) ) {
			$orderby = sanitize_text_field( $_GET['orderby'] );
		}

		// If order is set use this as the order
		if ( ! empty( $_GET['order'] ) ) {
			$order = sanitize_text_field( $_GET['order'] );
		}

		$result = strnatcmp( $a[ $orderby ], $b[ $orderby ] );

		if ( $order === 'asc' ) {
			return $result;
		}

		return -$result;
	}
}

