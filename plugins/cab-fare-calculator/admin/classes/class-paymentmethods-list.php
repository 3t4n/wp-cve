<?php
class Paymentmethods_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct(
			array(
				'singular' => __( 'Paymentmethod', 'sp' ), // singular name of the listed records
				'plural'   => __( 'Paymentmethods', 'sp' ), // plural name of the listed records
				'ajax'     => false, // does this table support ajax?
			)
		);
	}

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_paymentmethods( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		$defaults = array(
			'number'  => 20,
			'offset'  => 0,
			'orderby' => 'id',
			'order'   => 'ASC',
			'search'  => '',
		);

		$offset = ( $page_number - 1 ) * $per_page;
		$args   = array(
			'number' => $per_page,
			'offset' => $offset,
		);

		if ( ! empty( $_REQUEST['s'] ) ) {
			$args['search'] = sanitize_text_field( $_REQUEST['s'] );
		}

		if ( ! empty( $_REQUEST['orderby'] ) && ! empty( $_REQUEST['order'] ) ) {
			$args['orderby'] = sanitize_text_field( $_REQUEST['orderby'] );
			$args['order']   = sanitize_text_field( $_REQUEST['order'] );
		}

		$args = wp_parse_args( $args, $defaults );

		$where        = array();
		$where_string = '';

		if ( ! empty( $args['search'] ) ) {
			$search  = $args['search'];
			$where[] = "(title LIKE '%%{$search}%%')";
		}

		if ( ! empty( $where ) ) {
			$where_string = ' WHERE ' . implode( ' AND ', $where );
		}

		$result = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}tblight_paymentmethods 
				$where_string 
				ORDER BY {$args['orderby']} {$args['order']}
				LIMIT %d, %d",
				$args['offset'],
				$args['number']
			),
			'ARRAY_A'
		);

		return $result;
	}

	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_item( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}tblight_paymentmethods",
			array( 'id' => $id ),
			array( '%d' )
		);
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}tblight_paymentmethods";

		if ( ! empty( $_REQUEST['s'] ) ) {
			$search = sanitize_text_field( $_REQUEST['s'] );
			$sql   .= " WHERE (title LIKE '%%{$search}%%')";
		}

		return $wpdb->get_var( $wpdb->prepare( $sql ) );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		esc_attr_e( 'No paymentmethods avaliable.', 'cab-fare-calculator' );
	}

	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array  $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'payment_element':
			case 'state':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); // Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />',
			$item['id']
		);
	}

	/**
	 * Method for Pickup date column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	public function column_state( $item ) {

		$icon_waiting   = TBLIGHT_PLUGIN_DIR_URL . 'admin/images/publish_x.png';
		$icon_published = TBLIGHT_PLUGIN_DIR_URL . 'admin/images/icon-16-allow.png';

		if ( $item['state'] == 1 ) {
			$label = 'Published';
			$img   = '<img src="' . $icon_published . '" alt="Published" />';
		} else {
			$label = 'Unpublished';
			$img   = '<img src="' . $icon_waiting . '" alt="Unpublished" />';
		}

		return $img;
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	public function column_title( $item ) {

		$delete_nonce = wp_create_nonce( 'tblight_delete_paymentmethod' );
		$status_nonce = wp_create_nonce( 'tblight_status_paymentmethod' );

		$title = '<strong>' . sprintf( '<a href="?page=paymentmethods&action=%s&id=%s">' . $item['title'] . '</a>', 'edit', absint( $item['id'] ) ) . '</strong>';

		$actions = array(
			'edit'   => sprintf( '<a href="?page=paymentmethods&action=%s&id=%s">Edit</a>', 'edit', absint( $item['id'] ) ),
			'delete' => sprintf( '<a href="?page=paymentmethods&action=%s&id=%s&_wpnonce=%s">Delete</a>', 'delete', absint( $item['id'] ), $delete_nonce ),
		);

		return $title . $this->row_actions( $actions );
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'              => '<input type="checkbox" />',
			'title'           => __( 'Name', 'sp' ),
			'payment_element' => __( 'Element', 'sp' ),
			'state'           => __( 'Status', 'sp' ),
		);

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'title' => array( 'title', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		$per_page     = $this->get_items_per_page( 'items_per_page', 20 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args(
			array(
				'total_items' => $total_items, // WE have to calculate the total number of items
				'per_page'    => $per_page, // WE have to determine how many items to show on a page
			)
		);

		$this->items = self::get_paymentmethods( $per_page, $current_page );
	}
}
