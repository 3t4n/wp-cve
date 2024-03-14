<?php
/*
FIP_grid_table Class
*/
if ( ! class_exists( 'WP_List_Table_Local' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if ( !class_exists( 'FIP_grid_table' ) ):
	//error_reporting( ~E_NOTICE );
	class FIP_grid_table extends WP_List_Table_Local {
	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Grid', 'featured-image-pro' ), //singular name of the listed records
			'plural'   => __( 'Grids', 'featured-image-pro' ), //plural name of the listed records
			'ajax'     => true //does this table support ajax?
			] );
	}
	/**
	 * Retrieve grids data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	function get_grids( $per_page, $page ) {
		global $wpdb;
		$offset = ( $page * ($per_page-1) ) + 1 ;

		$db_name = $wpdb->prefix . 'proto_masonry_grids';
		$sql = "SELECT * FROM $db_name";
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
			$sql .= " LIMIT $per_page OFFSET $page";
		}
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	function record_count() {
		global $wpdb;
		$db_name = $wpdb->prefix . 'proto_masonry_grids';
		$sql = "SELECT COUNT(*) FROM $db_name";
		return $wpdb->get_var( $sql );
	}
	/** Text displayed when no grid data is available */
	public function no_items() {
		_e( 'No grids available.', 'featured-image-pro' );
	}
	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
		case 'description':
			return $item[ $column_name ];
		case 'shortcode':
			return "[featured_image_pro id='" . $item[ 'id' ] . "']";
		default:
			return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}
	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
		);
	}
	/**
	 * column_options function.
	 *
	 * @access public
	 * @param array $item an array of DB data
	 * @return shortcode text
	 */
	function column_options( $item )
	{
		$options = $item[ 'options' ];
		$values = json_decode( $options, true );
		$shortcode = '';
		foreach ( $values as $key=>$value )
		{
			$value = preg_replace('/\\\"/',"\"", $value);
			$value          = str_replace( '\\"', '"', $value );//strip out extra backslssh characters
			$value          = str_replace( "\'", "'", $value );//strip out extra backslssh characters
			$values[$key] = $value;
			$shortcode .= "$key=$value ";
		}
		return $shortcode;
	}
	/**
	 * Method for id column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_id( $item ) {
		$delete_nonce = wp_create_nonce( 'FIP_delete_id' );
		$edit_nonce = wp_create_nonce('FIP_edit_id');
		$title = '<strong>' . $item['id'] . '</strong>';
		$url = admin_url('/options-general.php?page=featured-image-pro-admin', 'http');

		$actions = [
		'edit' => sprintf( '<a href="%s&action=%s&grid=%s&_wpnonce=%s&tabindex=0">Edit</a>', $url, 'edit', absint( $item['id'] ), $edit_nonce ),
		'delete' => sprintf( '<a href="%s&action=%s&grid=%s&_wpnonce=%s&tabindex=1">Delete</a>', $url, 'delete', absint( $item['id'] ), $delete_nonce ),
		'duplicate' => sprintf( '<a href="%s&action=%s&grid=%s&_wpnonce=%s&tabindex=0">Duplicate</a>', $url, 'duplicate', absint( $item['id'] ), $edit_nonce )
		];
		return $title . $this->row_actions( $actions );
	}
	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
		'cb'      => '<input type="checkbox" />',
		'id'    => __( 'ID', 'featured-image-pro' ),
		'description' => __( 'Description', 'featured-image-pro' ),
		'shortcode' => __('Shortcode', 'featured-image-pro'),
		'options'    => __( 'Options', 'featured-image-pro' )
		];
		return $columns;
	}
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'id' => array( 'id', true ),
			'description' => array( 'description', false )
		);
		return $sortable_columns;
	}
	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
		'bulk-delete' => 'Delete'
		];
		return $actions;
	}
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	function prepare_items() {
		$per_page     = $this->get_items_per_page( 'grids_per_page', 5 );
		/**
		 * REQUIRED. Now we need to define our column headers. This includes a complete
		 * array of columns to be displayed (slugs & titles), a list of columns
		 * to keep hidden, and a list of columns that are sortable. Each of these
		 * can be defined in another method (as we've done here) before being
		 * used to build the value for our _column_headers property.
		 */
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		/**
		 * REQUIRED. Finally, we build an array to be used by the class for column
		 * headers. The $this->_column_headers property takes an array which contains
		 * 3 other arrays. One for all columns, one for hidden columns, and one
		 * for sortable columns.
		 */
		$this->_column_headers = array($columns, $hidden, $sortable);
		/**
		 * Optional. You can handle your bulk actions however you see fit. In this
		 * case, we'll handle them within our package just to keep things clean.
		 */
		//$this->process_bulk_action();
		$page = isset( $_GET['paged'] ) ? $_GET['paged'] : 1;
		$data = $this->get_grids($per_page, $page);
		/**
		 * This checks for sorting input and sorts the data in our array accordingly.
		 *
		 * In a real-world situation involving a database, you would probably want
		 * to handle sorting by passing the 'orderby' and 'order' values directly
		 * to a custom query. The returned data will be pre-sorted, and this array
		 * sorting technique would be unnecessary.
		 */
		function usort_reorder( $a,$b ){
			$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'id'; //If no sort, default to title
			$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to desc
			$result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
			return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
		}
		usort($data, 'usort_reorder');
		/***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         *
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         *
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
		/**
		 * REQUIRED for pagination. Let's figure out what page the user is currently
		 * looking at. We'll need this later, so you should always include it in
		 * your own package classes.
		 */
		$current_page = $this->get_pagenum();
		/**
		 * REQUIRED for pagination. Let's check how many items are in our data array.
		 * In real-world use, this would be the total number of items in your database,
		 * without filtering. We'll need this later, so you should always include it
		 * in your own package classes.
		 */
		$total_items = count( $data );
		/**
		 * The WP_List_Table_Local class does not handle pagination for us, so we need
		 * to ensure that the data is trimmed to only the current page. We can use
		 * array_slice() to
		 */
		$data = array_slice( $data,( ( $current_page-1 )*$per_page ),$per_page );
		/**
		 * REQUIRED. Now we can add our *sorted* data to the items property, where
		 * it can be used by the rest of the class.
		 */
		$this->items = $data;
		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
				'total_items' => $total_items,                  //WE have to calculate the total number of items
				'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
				'total_pages' => ceil($total_items/$per_page),   //WE have to calculate the total number of pages
				'orderby'     => ! empty( $_REQUEST['orderby'] ) && '' != $_REQUEST['orderby'] ? $_REQUEST['orderby'] : 'id',
				'order'      => ! empty( $_REQUEST['order'] ) && '' != $_REQUEST['order'] ? $_REQUEST['order'] : 'asc'
			) );
	}
	public function process_bulk_action() {
		//Detect when a bulk action is being triggered...
	}
	/*
		 * @Override of display method
	 */
}
endif;