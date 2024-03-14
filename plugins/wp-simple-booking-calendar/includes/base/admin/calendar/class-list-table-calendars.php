<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * List table class outputter for Calendars
 *
 */
Class WPSBC_WP_List_Table_Calendars extends WPSBC_WP_List_Table {

	/**
	 * The number of calendars that should appear in the table
	 *
	 * @access private
	 * @var int
	 *
	 */
	private $items_per_page;

	/**
	 * The number of the page being displayed by the pagination
	 *
	 * @access private
	 * @var int
	 *
	 */
	private $paged;

	/**
	 * The data of the table
	 *
	 * @access public
	 * @var array
	 *
	 */
	public $data = array();


	/**
	 * Constructor
	 *
	 */
	public function __construct() {

		parent::__construct( array(
			'plural' 	=> 'wpsbc_calendars',
			'singular' 	=> 'wpsbc_calendar',
			'ajax' 		=> false
		));

		/**
		 * Filter the number of calendars shown in the table
		 *
		 * @param int
		 *
		 */
		$this->items_per_page = apply_filters( 'wpsbc_list_table_calendars_items_per_page', 20 );

		$this->paged = ( ! empty( $_GET['paged'] ) ? (int)$_GET['paged'] : 1 );

		$this->set_pagination_args( array(
            'total_items' => wpsbc_get_calendars( array( 'number' => -1, 'search'  => ( ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '' ) ), true ),
            'per_page'    => $this->items_per_page
        ));

		// Get and set table data
		$this->set_table_data();
		
		// Add column headers and table items
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$this->items 		   = $this->data;

	}


	/**
	 * Returns all the columns for the table
	 *
	 */
	public function get_columns() {

		$columns = array(
			'name'		    => __( 'Name', 'wp-simple-booking-calendar' ),
			'id' 		    => __( 'ID', 'wp-simple-booking-calendar' ),
			'date_created'  => __( 'Date Created', 'wp-simple-booking-calendar' ),
			'date_modified' => __( 'Date Modified', 'wp-simple-booking-calendar' )
		);

		/**
		 * Filter the columns of the calendars table
		 *
		 * @param array $columns
		 *
		 */
		return apply_filters( 'wpsbc_list_table_calendars_columns', $columns );

	}


	/**
     * Overwrites the parent class.
     * Define which columns are sortable
     *
     * @return array
     *
     */
    public function get_sortable_columns() {

        return array(
            'name' => array( 'name', false ),
            'id'   => array( 'id', false ),
            'date_created'  => array( 'date_created', false ),
			'date_modified' => array( 'date_modified', false )
        );

    }


	/**
     * Returns the possible views for the calendar list table
     *
     */
    protected function get_views() {

    	$calendar_status = ( ! empty( $_GET['calendar_status'] ) ? esc_attr( $_GET['calendar_status'] ) : 'active' );

        $views = array(
            'active' => '<a href="' . add_query_arg( array( 'page' => 'wpsbc-calendars', 'calendar_status' => 'active', 'paged' => 1, 's' => ( ! empty( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : '' ) ), admin_url( 'admin.php' ) ) . '" ' . ( $calendar_status == 'active' ? 'class="current"' : '' ) . '>' . __( 'Active', 'wp-simple-booking-calendar' ) . ' <span class="count">(' . wpsbc_get_calendars( array( 'status' => 'active', 'search'  => ( ! empty( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : '' ) ), true ) . ')</span></a>',
            'trash'  => '<a href="' . add_query_arg( array( 'page' => 'wpsbc-calendars', 'calendar_status' => 'trash', 'paged' => 1, 's' => ( ! empty( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : '' ) ), admin_url( 'admin.php' ) ) . '" ' . ( $calendar_status == 'trash' ? 'class="current"' : '' ) . '>' . __( 'Trash', 'wp-simple-booking-calendar' ) . ' <span class="count">(' . wpsbc_get_calendars( array( 'status' => 'trash', 'search'  => ( ! empty( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : '' ) ), true ) . ')</span></a>',
        );

		/**
		 * Filter the views of the calendars table
		 *
		 * @param array $views
		 *
		 */
		return apply_filters( 'wpsbc_list_table_calendars_views', $views );

    }


	/**
	 * Gets the calendars data and sets it
	 *
	 */
	private function set_table_data() {

		$calendar_args = array(
			'number'  => $this->items_per_page,
			'offset'  => ( $this->paged - 1 ) * $this->items_per_page,
			'status'  => ( ! empty( $_GET['calendar_status'] ) ? sanitize_text_field( $_GET['calendar_status'] ) : 'active' ),
			'orderby' => ( ! empty( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'id' ),
			'order'   => ( ! empty( $_GET['order'] ) ? sanitize_text_field( strtoupper( $_GET['order'] ) ) : 'DESC' ),
			'search'  => ( ! empty( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '' )
		);

		$calendars = wpsbc_get_calendars( $calendar_args );

		if( empty( $calendars ) )
			return;

		foreach( $calendars as $calendar ) {

			$row_data = $calendar->to_array();

			/**
			 * Filter the calendar row data
			 *
			 * @param array 		 $row_data
			 * @param WPSBC_Calendar $calendar
			 *
			 */
			$row_data = apply_filters( 'wpsbc_list_table_calendars_row_data', $row_data, $calendar );

			$this->data[] = $row_data;

		}
		
	}


	/**
	 * Returns the HTML that will be displayed in each columns
	 *
	 * @param array $item 			- data for the current row
	 * @param string $column_name 	- name of the current column
	 *
	 * @return string
	 *
	 */
	public function column_default( $item, $column_name ) {

		return isset( $item[ $column_name ] ) ? $item[ $column_name ] : '-';

	}


	/**
	 * Returns the HTML that will be displayed in the "name" column
	 *
	 * @param array $item - data for the current row
	 *
	 * @return string
	 *
	 */
	public function column_name( $item ) {

		if( $item['status'] == 'active' ) {

			$output  = '<strong><a class="row-title" href="' . add_query_arg( array( 'page' => 'wpsbc-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $item['id'] ) , admin_url( 'admin.php' ) ) . '">' . ( !empty( $item['name'] ) ? $item['name'] : '' ) . '</a></strong>';

			$actions = array(
				'edit_calendar' => '<a href="' . add_query_arg( array( 'page' => 'wpsbc-calendars', 'subpage' => 'edit-calendar', 'calendar_id' => $item['id'] ) , admin_url( 'admin.php' ) ) . '">' . __( 'Edit Calendar', 'wp-simple-booking-calendar' ) . '</a>',
				'view_legend'   => '<a href="' . add_query_arg( array( 'page' => 'wpsbc-calendars', 'subpage' => 'view-legend', 'calendar_id' => $item['id'] ) , admin_url( 'admin.php' ) ) . '">' . __( 'Edit Calendar Legend', 'wp-simple-booking-calendar' ) . '</a>',
				'trash' 		=> '<span class="trash"><a onclick="return confirm( \'' . __( "Are you sure you want to send this calendar to the trash?", "wp-simple-booking-calendar" ) . ' \' )" href="' . wp_nonce_url( add_query_arg( array( 'page' => 'wpsbc-calendars', 'wpsbc_action' => 'trash_calendar', 'calendar_id' => $item['id'] ) , admin_url( 'admin.php' ) ), 'wpsbc_trash_calendar', 'wpsbc_token' ) . '" class="submitdelete">' . __( 'Trash', 'wp-simple-booking-calendar' ) . '</a></span>'
			);

		}

		if( $item['status'] == 'trash' ) {

			$output  = '<strong>' . ( !empty( $item['name'] ) ? $item['name'] : '' ) . '</strong>';

			$actions = array(
				'restore_calendar' => '<a href="' . wp_nonce_url( add_query_arg( array( 'page' => 'wpsbc-calendars', 'wpsbc_action' => 'restore_calendar', 'calendar_id' => $item['id'] ) , admin_url( 'admin.php' ) ), 'wpsbc_restore_calendar', 'wpsbc_token' ) . '">' . __( 'Restore Calendar', 'wp-simple-booking-calendar' ) . '</a>',
				'delete' 		   => '<span class="trash"><a onclick="return confirm( \'' . __( "Are you sure you want to delete this calendar?", "wp-simple-booking-calendar" ) . ' \' )" href="' . wp_nonce_url( add_query_arg( array( 'page' => 'wpsbc-calendars', 'wpsbc_action' => 'delete_calendar', 'calendar_id' => $item['id'] ) , admin_url( 'admin.php' ) ), 'wpsbc_delete_calendar', 'wpsbc_token' ) . '" class="submitdelete">' . __( 'Delete Permanently', 'wp-simple-booking-calendar' ) . '</a></span>'
			);

		}

		/**
		 * Filter the row actions before adding them to the table
		 *
		 * @param array $actions
		 * @param array $item
		 *
		 */
		$actions = apply_filters( 'wpsbc_list_table_calendars_row_actions', $actions, $item );

		$output .= $this->row_actions( $actions );

		return $output;

	}


	/**
	 * Returns the HTML that will be displayed in the "date_created" column
	 *
	 * @param array $item - data for the current row
	 *
	 * @return string
	 *
	 */
	public function column_date_created( $item ) {

		$output = date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $item['date_created'] ) );

		return $output;

	}


	/**
	 * Returns the HTML that will be displayed in the "date_created" column
	 *
	 * @param array $item - data for the current row
	 *
	 * @return string
	 *
	 */
	public function column_date_modified( $item ) {

		$output = date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $item['date_modified'] ) );

		return $output;

	}


	/**
	 * HTML display when there are no items in the table
	 *
	 */
	public function no_items() {

		echo __( 'No calendars found.', 'wp-simple-booking-calendar' );

	}

}