<?php

namespace EasyBooking;

/**
*
* Admin: "Bookings" reports page table.
* @version 3.0.4
*
**/

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class List_Bookings extends \WP_List_Table {

	protected $max_items;

	public function __construct() {

		parent::__construct( array(
			'singular'  => __( 'Report', 'woocommerce' ),
			'plural'    => __( 'Reports', 'woocommerce' ),
			'ajax'		=> true
		) );

	}

	/**
	*
	* Display filters and pagination.
	*
	**/
	protected function display_tablenav( $which ) {

		?>

		<div class="tablenav <?php echo esc_attr( $which ); ?>">

			<?php
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>

			<br class="clear" />
		</div>

		<?php

	}

	/**
	*
	* Display filters.
	*
	**/
	protected function extra_tablenav( $which ) {

		$filter_status     = isset( $_GET['status'] ) ? stripslashes( $_GET['status'] ) : '';
		$filter_id         = isset( $_GET['product_ids'] ) ? stripslashes( $_GET['product_ids'] ) : '';
		$filter_start_date = isset( $_GET['start_date'] ) ? stripslashes( $_GET['start_date'] ) : '';
		$filter_end_date   = isset( $_GET['end_date'] ) ? stripslashes( $_GET['end_date'] ) : '';
		
		if ( ! empty( $filter_id ) ) {
			$_product = wc_get_product( $filter_id );
		}

		$product = isset( $_product ) && is_object( $_product ) ? $_product->get_formatted_name() : '';

		include_once( 'views/html-wceb-reports-filters.php' );

	}

	/**
	*
	* Set reports columns
	*
	**/
	public function get_columns() {

		$columns = apply_filters( 'easy_booking_reports_columns', array(
			'booking_status' => esc_html__( 'Status', 'woocommerce' ),
			'order_id'       => esc_html__( 'Order', 'woocommerce' ),
			'product'        => esc_html__( 'Product', 'woocommerce' ),
			'start_date'     => esc_html( apply_filters( 'easy_booking_start_text', __( 'Start', 'woocommerce-easy-booking-system' ) ) ),
			'end_date'       => esc_html( apply_filters( 'easy_booking_end_text', __( 'End', 'woocommerce-easy-booking-system' ) ) ),
			'qty_booked'     => esc_html__( 'Quantity booked', 'woocommerce-easy-booking-system' )
		) );

		/*
		*
		* Filter to insert custom columns in the Reports page.
		* Each column must be an associative array with 'position', 'id' and 'name'.
		*
		**/
		$custom_columns = apply_filters( 'easy_booking_reports_custom_columns', array() );

		if ( $custom_columns ) foreach ( $custom_columns as $custom_column ) {

			// Sanitize.
			$id = sanitize_html_class( $custom_column['id'] );
			$custom_column['content'][$id] = esc_html( $custom_column['name'] );

			// Insert custom column at the right position.
			$columns = $this->insert_custom_column( $columns, absint( $custom_column['position'] ), $custom_column['content'] );

		}

		return $columns;

	}

	/**
	*
	* Insert custom column content at the right position.
	*
	**/
	private function insert_custom_column( &$columns, $position, $content ) {

		$split_columns = array_splice( $columns, 0, $position );
		$columns = array_merge( $split_columns, $content, $columns );

		return $columns;

	}

	/**
	*
	* Set reports sortable columns.
	*
	**/
	protected function get_sortable_columns() {

		$sortable_columns = apply_filters( 'easy_booking_reports_sortable_columns', array(
			'booking_status' => array( 'booking_status', true ),
			'order_id'       => array( 'order_id', true ),
			'product'        => array( 'product_id', true ),
			'start_date'     => array( 'start_date', false ),
			'end_date'       => array( 'end_date', false )
		) );

		return $sortable_columns;

	}

	/**
	*
	* Booking status column content
	*
	**/
	protected function column_booking_status( $item ) {

		if ( isset( $item['status'] ) ) {

			$display_status = str_replace( 'wceb-', '', $item['status'] );
			$display_status = apply_filters( 'easy_booking_display_status_' . $display_status, ucfirst( $display_status ) );

			printf( '<mark class="order-status %s tips" data-tip="%s">%s</mark>', esc_attr( sanitize_html_class( $item['status'] ) ), esc_attr( $display_status ), '<span>' . esc_html( $display_status ) . '</span>' );
			
		}

	}

	/**
	*
	* Order column content
	*
	**/
	protected function column_order_id( $item ) {

		$order = ! empty( $item['order_id'] ) ? wc_get_order( $item['order_id'] ) : false;

		if ( $order ) {

			$buyer = '';

			if ( $order->get_billing_first_name() || $order->get_billing_last_name() ) {

				/* translators: 1: first name 2: last name */
				$buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $order->get_billing_first_name(), $order->get_billing_last_name() ) );

			} elseif ( $order->get_billing_company() ) {

				$buyer = trim( $order->get_billing_company() );

			} elseif ( $order->get_customer_id() ) {

				$user  = get_user_by( 'id', $order->get_customer_id() );
				$buyer = ucwords( $user->display_name );

			}

			echo '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $order->get_id() ) ) . '&action=edit' ) . '" class="order-view"><strong>#' . esc_attr( $order->get_order_number() ) . ' ' . esc_html( $buyer ) . '</strong></a>';

		} else {

			esc_html_e( 'Imported booking', 'woocommerce-easy-booking-system' );

		}

	}

	/**
	*
	* Product column content
	*
	**/
	protected function column_product( $item ) {

		$product = wc_get_product( $item['product_id'] );

		if ( ! $product ) {
			return;
		}

		echo wp_kses_post( $product->get_formatted_name() );

	}

	/**
	*
	* Start date column content
	*
	**/
	protected function column_start_date( $item ) {

		echo date_i18n( get_option( 'date_format' ), strtotime( $item['start'] ) );

	}

	/**
	*
	* End date column content
	*
	**/
	protected function column_end_date( $item ) {

		if ( isset( $item['end'] ) ) {
			echo date_i18n( get_option( 'date_format' ), strtotime( $item['end'] ) );
		}

	}

	/**
	*
	* Quantity booked column content
	*
	**/
	protected function column_qty_booked( $item ) {

		echo esc_html( $item['qty'] );

	}

	/**
	*
	* Custom columns content
	*
	**/
	protected function column_default( $item, $column_name ) {

		echo apply_filters(
			'easy_booking_reports_display_custom_column',
			isset( $item[$column_name] ) ? esc_html( $item[$column_name] ) : '',
			$column_name,
			$item
		);
		
	}

	/**
	*
	* Prepare items.
	*
	**/
	public function prepare_items() {

		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$current_page          = absint( $this->get_pagenum() );
		$per_page              = 20;

		// Get items
		$this->get_items( $current_page, $per_page );

		// Pagination
		$this->set_pagination_args( array(
			'total_items' => $this->max_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $this->max_items / $per_page )
		) );

	}

	/**
	*
	* Get items
	*
	**/
	protected function get_items( $current_page, $per_page ) {

		$this->max_items = 0;
		$this->items     = array();

		// Filter bookings
		$filters  = $_GET;
		$bookings = wceb_get_filtered_bookings( $filters );

		// Sort results
		usort( $bookings, array( $this, 'sort_items' ) );

		$total_items = count( $bookings );
		$min         = ( $current_page - 1 ) * $per_page;
		$max         = $min + $per_page;

		$set_max = $total_items < $max ? $total_items : $max;

		$items = array();
		for ( $i = $min; $i < $set_max; $i++ ) {
			$items[] = $bookings[$i];
		}

		$this->items     = $items;
		$this->max_items = $total_items;

	}

	/**
	*
	* Sort reports.
	*
	**/
	public function sort_items( $a, $b ) {

		// If no order (asc pr desc), default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';

		// If no sort, default to order ID
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'order_id';	

		// Determine sort order
		switch ( $orderby ) {

			case 'booking_status' :
				$result = ( $a['status'] > $b['status'] ) ? -1 : 1;
			break;

			case 'order_id' :

				if ( isset( $a['order_id'] ) && isset( $b['order_id'] ) ) {
					$result = ( $a['order_id'] > $b['order_id'] ) ? -1 : 1;
				} else {
					$result = -1;
				}

			break;

			case 'product_id' :
				$result = ( $a['product_id'] > $b['product_id'] ) ? -1 : 1;
			break;

			case 'start_date' :
				$a_start_date = strtotime( $a['start'] );
				$b_start_date = strtotime( $b['start'] );

				$result = ( $a_start_date > $b_start_date ) ? -1 : 1;
			break;

			case 'end_date' :
				$a_end_date = strtotime( $a['end'] );
				$b_end_date = strtotime( $b['end'] );

				$result = ( $a_end_date > $b_end_date ) ? -1 : 1;
			break;

			default:
				$result = apply_filters( 'easy_booking_sort_reports_columns', -1, $orderby, $a, $b);
			break;

		}
		
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;

	}

}