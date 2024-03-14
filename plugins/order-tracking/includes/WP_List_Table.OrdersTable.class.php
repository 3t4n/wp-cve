<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( !class_exists( 'ewdotpOrdersTable' ) ) {
/**
 * Orders Table Class
 *
 * Extends WP_List_Table to display the list of orders in a format similar to
 * the default WordPress post tables.
 *
 * @h/t Easy Digital Downloads by Pippin: https://easydigitaldownloads.com/
 * @since 3.0.0
 */
class ewdotpOrdersTable extends WP_List_Table {

	/**
	 * Number of results to show per page
	 *
	 * @var string
	 * @since 3.0.0
	 */
	public $per_page = 30;

	/**
	 * URL of this page
	 *
	 * @var string
	 * @since 3.0.0
	 */
	public $base_url;

	/**
	 * Array of order counts by total and status
	 *
	 * @var array
	 * @since 3.0.0
	 */
	public $order_counts;

	/**
	 * Array of orders
	 *
	 * @var array
	 * @since 3.0.0
	 */
	public $orders;

	/**
	 * Current date filters
	 *
	 * @var string
	 * @since 3.0.0
	 */
	public $filter_start_date = null;
	public $filter_end_date = null;

	/**
	 * Current time filters
	 *
	 * @var string
	 * @since 3.0.0
	 */
	public $filter_start_time = null;
	public $filter_end_time = null;

	/**
	 * Current order number
	 *
	 * @var int
	 * @since 3.0.0
	 */
	public $filter_order_number = '';

	/**
	 * Current order number
	 *
	 * @var int
	 * @since 3.0.0
	 */
	public $filter_include_hidden_orders = false;

	/**
	 * Current order status
	 *
	 * @var int
	 * @since 3.0.0
	 */
	public $filter_order_status = '';

	/**
	 * Current query string
	 *
	 * @var string
	 * @since 3.0.0
	 */
	public $query_string;

	/**
	 * Results of a bulk or quick action
	 *
	 * @var array
	 * @since 1.4.6
	 */
	public $action_result = array();

	/**
	 * Type of bulk or quick action last performed
	 *
	 * @var string
	 * @since 1.4.6
	 */
	public $last_action = '';

	/**
	 * Stored reference to visible columns
	 *
	 * @var string
	 * @since 3.0.0
	 */
	public $visible_columns = array();

	/**
	 * Stored reference to extra arguments based in for order matching
	 *
	 * @var string
	 * @since 3.0.0
	 */
	public $order_args = array();

	/**
	 * Initialize the table and perform any requested actions
	 *
	 * @since 3.0.0
	 */
	public function __construct( $args = array() ) {

		global $status, $page;

		$this->order_args = $args;

		// Set parent defaults
		parent::__construct( array(
			'singular'  => __( 'Order', 'order-tracking' ),
			'plural'    => __( 'Orders', 'order-tracking' ),
			'ajax'      => false
		) );

		// Set the date filter
		$this->set_date_filter();

		$this->set_per_page();

		// Strip unwanted query vars from the query string or ensure the correct
		// vars are used
		$this->query_string_maintenance();

		// Run any bulk action requests
		$this->process_bulk_action();

		// Retrieve a count of the number of orders by status
		$this->get_order_counts();

		// Retrieve orders data for the table
		$this->orders_data();

		$this->base_url = admin_url( 'admin.php?page=ewd-otp-orders' );
	}

	/**
	 * Set the correct date filter
	 *
	 * $_POST values should always overwrite $_GET values
	 *
	 * @since 3.0.0
	 */
	public function set_date_filter( $start_date = null, $end_date = null, $start_time = null, $end_time = null ) {

		if ( !empty( $_GET['action'] ) && $_GET['action'] == 'clear_date_filters' ) {
			$this->filter_start_date 	= null;
			$this->filter_end_date 		= null;
			$this->filter_start_time 	= null;
			$this->filter_end_time 		= null;
		}

		$this->filter_start_date 	= $start_date;
		$this->filter_end_date 		= $end_date;
		$this->filter_start_time 	= $start_time;
		$this->filter_end_time 		= $end_time;

		if ( $start_date === null ) {
			$this->filter_start_date = !empty( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : null;
			$this->filter_start_date = !empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : $this->filter_start_date;
		}

		if ( $end_date === null ) {
			$this->filter_end_date = !empty( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : null;
			$this->filter_end_date = !empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : $this->filter_end_date;
		}

		if ( $start_time === null ) {
			$this->filter_start_time = !empty( $_GET['start_time'] ) ? sanitize_text_field( $_GET['start_time'] ) : null;
			$this->filter_start_time = !empty( $_POST['start_time'] ) ? sanitize_text_field( $_POST['start_time'] ) : $this->filter_start_time;
		}

		if ( $end_time === null ) {
			$this->filter_end_time = !empty( $_GET['end_time'] ) ? sanitize_text_field( $_GET['end_time'] ) : null;
			$this->filter_end_time = !empty( $_POST['end_time'] ) ? sanitize_text_field( $_POST['end_time'] ) : $this->filter_end_time;
		}
	}

	/**
	 * Sets the per page property based on the screen option
	 *
	 * @since 3.0.0
	 */
	public function set_per_page() {

		$screen_option = get_current_screen()->get_option( 'per_page', 'option' );

		$per_page = ! empty( $screen_option ) ? get_user_meta( get_current_user_id(), $screen_option, true ) : false;

		$this->per_page = empty( $per_page ) ? $this->per_page : $per_page;
	}

	/**
	 * Get the current date range
	 *
	 * @since 3.0.0
	 */
	public function get_current_date_range() {

		$range = empty( $this->filter_start_date ) ? _x( '*', 'No date limit in a date range, eg 2014-* would mean any date from 2014 or after', 'order-tracking' ) : $this->filter_start_date;
		$range .= empty( $this->filter_start_date ) || empty( $this->filter_end_date ) ? '' : _x( '&mdash;', 'Separator between two dates in a date range', 'order-tracking' );
		$range .= empty( $this->filter_end_date ) ? _x( '*', 'No date limit in a date range, eg 2014-* would mean any date from 2014 or after', 'order-tracking' ) : $this->filter_end_date;

		return $range;
	}

	/**
	 * Strip unwanted query vars from the query string or ensure the correct
	 * vars are passed around and those we don't want to preserve are discarded.
	 *
	 * @since 3.0.0
	 */
	public function query_string_maintenance() {

		$this->query_string = remove_query_arg( array( 'action', 'start_date', 'end_date' ) );

		if ( $this->filter_start_date !== null ) {
			$this->query_string = add_query_arg( array( 'start_date' => $this->filter_start_date ), $this->query_string );
		}

		if ( $this->filter_end_date !== null ) {
			$this->query_string = add_query_arg( array( 'end_date' => $this->filter_end_date ), $this->query_string );
		}

		if ( $this->filter_start_time !== null ) {
			$this->query_string = add_query_arg( array( 'start_time' => $this->filter_start_time ), $this->query_string );
		}

		if ( $this->filter_end_time !== null ) {
			$this->query_string = add_query_arg( array( 'end_time' => $this->filter_end_time ), $this->query_string );
		}

		$this->filter_order_number = ! isset( $_GET['order_number'] ) ? '' : sanitize_text_field( $_GET['order_number'] );
		$this->filter_order_number = ! isset( $_POST['order_number'] ) ? $this->filter_order_number : sanitize_text_field( $_POST['order_number'] );
		$this->query_string = remove_query_arg( 'order_number', $this->query_string );
		if ( !empty( $this->filter_order_number ) ) {
			$this->query_string = add_query_arg( array( 'order_number' => $this->filter_order_number ), $this->query_string );
		}

		$this->filter_include_hidden_orders = ! isset( $_GET['include_hidden_orders'] ) ? false : true;
		$this->filter_include_hidden_orders = ! isset( $_POST['include_hidden_orders'] ) ? $this->filter_include_hidden_orders : true;
		$this->query_string = remove_query_arg( 'include_hidden_orders', $this->query_string );
		if ( !empty( $this->filter_include_hidden_orders ) ) {
			$this->query_string = add_query_arg( array( 'include_hidden_orders' => $this->filter_include_hidden_orders ), $this->query_string );
		}

		$this->filter_order_status = ! isset( $_GET['status'] ) ? '' : sanitize_text_field( $_GET['status'] );
		$this->filter_order_status = ! isset( $_POST['status'] ) ? $this->filter_order_status : sanitize_text_field( $_POST['status'] );
		$this->query_string = remove_query_arg( 'status', $this->query_string );
		if ( !empty( $this->filter_order_status ) ) {
			$this->query_string = add_query_arg( array( 'status' => $this->filter_order_status ), $this->query_string );
		}
	}

	/**
	 * Show the time views, date filters and the search box
	 * @since 3.0.0
	 */
	public function advanced_filters() {

		// Show the date_range views (today, week, all)
		if ( !empty( $_GET['date_range'] ) ) {
			$date_range = sanitize_text_field( $_GET['date_range'] );
		} else {
			$date_range = '';
		}

		// Use a custom date_range if a date range has been entered
		if ( $this->filter_start_date !== null || $this->filter_end_date !== null ) {
			$date_range = 'custom';
		}

		// Strip out existing date filters from the date_range view urls
		$date_range_query_string = remove_query_arg( array( 'date_range', 'start_date', 'end_date' ), $this->query_string );

		$views = array(
			'all'		=> sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( array( 'paged' => FALSE ), remove_query_arg( array( 'date_range' ), $date_range_query_string ) ) ), $date_range === '' ? ' class="current"' : '', __( 'All', 'order-tracking' ) ),
			'today'	    => sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( array( 'date_range' => 'today', 'paged' => FALSE ), $date_range_query_string ) ), $date_range === 'today' ? ' class="current"' : '', __( 'Today', 'order-tracking' ) ),
			'past'	    => sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( array( 'date_range' => 'week', 'paged' => FALSE ), $date_range_query_string ) ), $date_range === 'week' ? ' class="current"' : '', __( 'This Week', 'order-tracking' ) ),
		);

		if ( $date_range == 'custom' ) {
			$views['date'] = '<span class="date-filter-range current">' . $this->get_current_date_range() . '</span>';
			$views['date'] .= '<a id="ewd-otp-date-filter-link" href="#"><span class="dashicons dashicons-calendar"></span> <span class="ewd-otp-date-filter-label">Change date range</span></a>';
		} else {
			$views['date'] = '<a id="ewd-otp-date-filter-link" href="#">' . esc_html__( 'Specific Date(s)/Time', 'order-tracking' ) . '</a>';
		}

		$views = apply_filters( 'ewd_otp_orders_table_views_date_range', $views, $date_range_query_string );
		?>

		<div id="ewd-otp-filters">
			<ul class="subsubsub ewd-otp-views-date_range">
				<li><?php echo join( ' | </li><li>', $views ); ?></li>
			</ul>

			<div class="date-filters">
				<div class="ewd-otp-admin-bookings-filters-start">
					<label for="start-date" class="screen-reader-text"><?php _e( 'Start Date:', 'order-tracking' ); ?></label>
					<input type="date" id="start-date" name="start_date" class="datepicker" value="<?php echo esc_attr( $this->filter_start_date ); ?>" placeholder="<?php _e( 'Start Date', 'order-tracking' ); ?>" />
					<input type="text" id="start-time" name="start_time" class="timepicker" value="<?php echo esc_attr( $this->filter_start_time ); ?>" placeholder="<?php _e( 'Start Time', 'order-tracking' ); ?>" />
				</div>
				<div class="ewd-otp-admin-bookings-filters-end">
					<label for="end-date" class="screen-reader-text"><?php _e( 'End Date:', 'order-tracking' ); ?></label>
					<input type="date" id="end-date" name="end_date" class="datepicker" value="<?php echo esc_attr( $this->filter_end_date ); ?>" placeholder="<?php _e( 'End Date', 'order-tracking' ); ?>" />
					<input type="text" id="end-time" name="end_time" class="timepicker" value="<?php echo esc_attr( $this->filter_end_time ); ?>" placeholder="<?php _e( 'Start Time', 'order-tracking' ); ?>" />
				</div>
				<input type="submit" class="button button-secondary" value="<?php _e( 'Apply', 'order-tracking' ); ?>"/>
				<?php if( !empty( $this->filter_start_date ) || !empty( $this->filter_end_date ) || !empty( $this->filter_start_time ) || !empty( $this->filter_end_time ) ) : ?>
				<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'clear_date_filters' ) ) ); ?>" class="button button-secondary"><?php _e( 'Clear Filter', 'order-tracking' ); ?></a>
				<?php endif; ?>
			</div>

			<?php if( !empty( $_GET['status'] ) ) : ?>
				<input type="hidden" name="status" value="<?php echo esc_attr( sanitize_text_field( $_GET['status'] ) ); ?>"/>
			<?php endif; ?>
		</div>

<?php
	}

	/**
	 * Retrieve the view types
	 * @since 3.0.0
	 */
	public function get_views() {
		global $ewd_otp_controller; 

		$current = isset( $_GET['status'] ) ? $_GET['status'] : '';

		$views = array(
			'all'		=> sprintf( '<a href="%s"%s>%s</a>', esc_url( remove_query_arg( array( 'status', 'paged' ), $this->query_string ) ), $current === 'all' || $current == '' ? ' class="current"' : '', __( 'All', 'order-tracking' ) . ' <span class="count">(' . $this->order_counts['total'] . ')</span>' ),
		);

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		foreach ( $statuses as $status ) {

			$sanitized_status = sanitize_title( $status->status, '', 'ewd_otp' );

			$views[ $sanitized_status ] = sprintf( '<a href="%s"%s>%s</a>', esc_url( add_query_arg( array( 'status' => $sanitized_status, 'paged' => FALSE ), $this->query_string ) ), $current === $sanitized_status ? ' class="current"' : '', $status->status . ' <span class="count">(' . $this->order_counts[ $sanitized_status ] . ')</span>' );
		} 

		return apply_filters( 'ewd_otp_orders_table_views_status', $views );
	}

	/**
	 * Generates content for a single row of the table
	 * @since 3.0.0
	 */
	public function single_row( $item ) {
		static $row_alternate_class = '';
		$row_alternate_class = ( $row_alternate_class == '' ? 'alternate' : '' );

		$row_classes = ! empty( $item->post_status ) ? array( esc_attr( $item->post_status ) ) : array();

		if ( !empty( $row_alternate_class ) ) {
			$row_classes[] = $row_alternate_class;
		}

		$row_classes = apply_filters( 'ewd_otp_admin_orders_list_row_classes', $row_classes, $item );

		echo '<tr class="' . implode( ' ', $row_classes ) . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

	/**
	 * Retrieve the table columns
	 *
	 * @since 3.0.0
	 */
	public function get_columns() {
		global $ewd_otp_controller;

		// Prevent the lookup from running over and over again on a single
		// page load
		if ( !empty( $this->visible_columns ) ) {
			return $this->visible_columns;
		}

		$all_default_columns = $this->get_all_default_columns();
		$all_columns = $this->get_all_columns();

		$visible_columns = $ewd_otp_controller->settings->get_setting( 'orders-table-columns' );
		if ( empty( $visible_columns ) ) {
			$columns = $all_default_columns;
		} else {
			$columns = array();
			$columns['cb'] = $all_default_columns['cb'];
			$columns['date'] = $all_default_columns['date'];

			foreach( $all_columns as $key => $column ) {
				if ( in_array( $key, $visible_columns ) ) {
					$columns[$key] = $all_columns[$key];
				}
			}
			$columns['details'] = $all_default_columns['details'];
		}

		$this->visible_columns = apply_filters( 'ewd_otp_orders_table_columns', $columns );

		return $this->visible_columns;
	}

	/**
	 * Retrieve all default columns
	 *
	 * @since 3.0.0
	 */
	public function get_all_default_columns() {
		global $ewd_otp_controller;

		$columns = array(
			'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
			'number'   	=> __( 'Order Number', 'order-tracking' ),
			'name'  	=> __( 'Name', 'order-tracking' ),
			'status'  	=> __( 'Status', 'order-tracking' ),
			'notes'  	=> __( 'Customer Notes', 'order-tracking' ),
			'updated' 	=> __( 'Updated', 'order-tracking' ),
		);

		if ( $ewd_otp_controller->settings->get_setting( 'allow-order-payments' ) ) { $columns['payment'] = __( 'Payment Made', 'order-tracking' ) ; }

		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			if ( ! $custom_field->display ) { continue; }

			$columns[ $custom_field->slug ] = $custom_field->name ;
		}

		return $columns;
	}

	/**
	 * Retrieve all available columns
	 *
	 * This is used to get all columns including those deactivated and filtered
	 * out via get_columns().
	 *
	 * @since 3.0.0
	 */
	public function get_all_columns() {
		$columns = $this->get_all_default_columns();

		return apply_filters( 'ewd_otp_orders_all_table_columns', $columns );
	}

	/**
	 * Retrieve the table's sortable columns
	 * @since 3.0.0
	 */
	public function get_sortable_columns() {
		global $ewd_otp_controller;

		$columns = array(
			'number'	=> array( 'number', true ),
			'name' 		=> array( 'name', true ),
			'status' 	=> array( 'status', true ),
			'notes' 	=> array( 'customer_notes', true ),
			'updated' 	=> array( 'status_updated', true ),
		);

		if ( $ewd_otp_controller->settings->get_setting( 'allow-order-payments' ) ) { $columns['payment'] = array( 'payment_completed', true ) ; }

		return apply_filters( 'ewd_otp_orders_table_sortable_columns', $columns );
	}

	/**
	 * This function renders most of the columns in the list table.
	 * @since 3.0.0
	 */
	public function column_default( $order, $column_name ) {
		global $ewd_otp_controller;

		switch ( $column_name ) {

			case 'number' :
				
				$value = $order->number;

				$value .= '<div class="actions">';
				$value .= '<a href="admin.php?page=ewd-otp-add-edit-order&order_id=' . $order->id . '" data-id="' . esc_attr( $order->id ) . '">' . __( 'Edit', 'order-tracking' ) . '</a>';
				$value .= ' | <a href="#" class="hide" data-id="' . esc_attr( $order->id ) . '" data-action="hide">' . __( 'Hide', 'order-tracking' ) . '</a>';
				$value .= ' | <a href="#" class="delete" data-id="' . esc_attr( $order->id ) . '" data-action="delete">' . __( 'Delete', 'order-tracking' ) . '</a>';
				$value .= '</div>';

				break;

			case 'name' :
				$value = esc_html( $order->name );
				break;

			case 'status' :
				$value = esc_html( $order->status );
				break;

			case 'notes' :
				$value = esc_html( $order->customer_notes );
				break;

			case 'updated' :
				$value = esc_html( date( $ewd_otp_controller->settings->get_setting( 'date-format' ), strtotime( $order->status_updated_fmtd ) ) );
				break;

			case 'payment' :
				$value = $order->payment_completed ? __( 'Yes', 'order-tracking' ) : __( 'No', 'order-tracking' );
				break;

			default:
				$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

				foreach ( $custom_fields as $custom_field ) {
		
					if ( $custom_field->slug != $column_name ) { continue; }
		
					$value = isset( $order->custom_fields[ $custom_field->id ] ) ? esc_html( $order->custom_fields[ $custom_field->id ] ) : '';
				}

				break;

		}

		return apply_filters( 'ewd_otp_orders_table_column', $value, $order, $column_name );
	}

	/**
	 * Render the checkbox column
	 * @since 3.0.0
	 */
	public function column_cb( $order ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			'orders',
			$order->id
		);
	}

	/**
	 * Retrieve the bulk actions
	 * @since 3.0.0
	 */
	public function get_bulk_actions() {
		global $ewd_otp_controller;

		$actions = array(
			'delete'   => __( 'Delete',		'order-tracking' ),
		);

		if ( $ewd_otp_controller->settings->get_setting( 'allow-order-payments' ) ) { 

			$actions['set-status-payment-received'] = __( 'Set To Payment Received', 'order-tracking' );
		}

		$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

		foreach ( $statuses as $status ) {

			$sanitized_status = sanitize_title( $status->status, '', 'ewd_otp' );

			$actions[ $sanitized_status ] = __( 'Set To ', 'order-tracking' ) . $status->status;
		}

		return apply_filters( 'ewd_otp_orders_table_bulk_actions', $actions );
	}

	/**
	 * Process the bulk actions
	 * @since 3.0.0
	 */
	public function process_bulk_action() {
		global $ewd_otp_controller;

		$ids    = isset( $_POST['orders'] ) ? $_POST['orders'] : false;
		$action = isset( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : false;

		// Check bulk actions selector below the table
		$action = $action == '-1' && isset( $_POST['action2'] ) ? sanitize_text_field( $_POST['action2'] ) : $action;

		if( empty( $action ) || $action == '-1' ) {
			return;
		}

		if ( ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) {
			return;
		}

		if ( ! is_array( $ids ) ) {
			$ids = array( $ids );
		}

		$results = array();
		foreach ( $ids as $id ) {

			if ( 'delete' === $action ) {
				$results[$id] = $ewd_otp_controller->order_manager->delete_order( intval( $id ) );
			}

			elseif ( 'set-status-payment-received' === $action ) {
				$results[$id] = $ewd_otp_controller->order_manager->set_order_paid( intval( $id ) );
			}

			elseif ( isset( $action ) ) {

				$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

				foreach ( $statuses as $status ) {

					if ( sanitize_title( $status->status, '', 'ewd_otp' ) != $action ) { continue; }

					$results[$id] = $ewd_otp_controller->order_manager->set_order_status( intval( $id ), $status->status );
				}
			}

			$results = apply_filters( 'ewd_otp_orders_table_bulk_action', $results, $id, $action );
		}

		if( count( $results ) ) {
			$this->action_result = $results;
			$this->last_action = $action;
			add_action( 'ewd_otp_orders_table_top', array( $this, 'admin_notice_bulk_actions' ) );
		}
	}

	/**
	 * Display an admin notice when a bulk action is completed
	 * @since 3.0.0
	 */
	public function admin_notice_bulk_actions() {
		global $ewd_otp_controller;

		$success = 0;
		$failure = 0;
		foreach( $this->action_result as $id => $result ) {
			if ( $result === true || $result === null ) {
				$success++;
			} else {
				$failure++;
			}
		}

		if ( $success > 0 ) :
		?>

		<div id="ewd-otp-admin-notice-bulk-<?php esc_attr( $this->last_action ); ?>" class="updated">

			<?php if ( $this->last_action == 'delete' ) : ?>
			<p><?php echo sprintf( _n( '%d order deleted successfully.', '%d orders deleted successfully.', $success, 'order-tracking' ), $success ); ?></p>

			<?php elseif ( $this->last_action == 'set-status-payment-received' ) : ?>
			<p><?php echo sprintf( _n( '%d order set to payment received.', '%d orders set to payment received.', $success, 'order-tracking' ), $success ); ?></p>

			<?php elseif ( isset( $this->last_action ) ) : ?>

				<?php $statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) ); ?>
				<?php foreach ( $statuses as $status ) { ?>

					<?php if ( sanitize_title( $status->status, '', 'ewd_otp' ) != $this->last_action ) { continue; } ?>

					<p><?php echo sprintf( _n( '%d order set to status %s.', '%d orders set to status %s.', $success, 'order-tracking' ), $success, $status->status ); ?></p>
				<?php } ?>

			<?php endif; ?>
		</div>

		<?php
		endif;

		if ( $failure > 0 ) :
		?>

		<div id="ewd-otp-admin-notice-bulk-<?php esc_attr( $this->last_action ); ?>" class="error">
			<p><?php echo sprintf( _n( '%d order had errors and could not be processed.', '%d orders had errors and could not be processed.', $failure, 'order-tracking' ), $failure ); ?></p>
		</div>

		<?php
		endif;
	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * This outputs a separate set of options above and below the table, in
	 * order to make room for the locations, services and providers.
	 *
	 * @since 3.0.0
	 */
	public function display_tablenav( $which ) {
		global $ewd_otp_controller;

		// Just call the parent method for the bottom nav
		if ( 'bottom' == $which ) {
			parent::display_tablenav( $which );
			return;
		}
		?>

		<?php $this->add_notification(); ?>

		<div class="ewd-otp-table-header-controls">
			<div class="ewd-otp-table-header-controls-left">
				<?php if ( $this->has_items() ) : ?>
					<div class="actions bulkactions">
						<?php $this->bulk_actions( $which ); ?>
					</div>
				<?php else : ?>
					<input type="submit" class="hidden" value="Apply">
				<?php endif; ?>
				<a id="ewd-otp-table-header-search-filter" href="#"><?php esc_html_e( 'Search and Filter', 'order-tracking' ); ?></a>
				<div class='ewd-otp-admin-table-filter-div ewd-otp-hidden'>
					<div class='ewd-otp-admin-table-filter-label'><?php esc_html_e( 'Order Number', 'order-tracking' ); ?></div>
					<input type='text' class='ewd-otp-orders-table-filter ewd-otp-order-number ewd-otp-admin-table-filter-field' name='order_number' value='<?php echo ( empty( $this->filter_order_number ) ? esc_attr( $this->filter_order_number ) : '' ); ?>' />
				</div>
				<div class='ewd-otp-admin-table-filter-div ewd-otp-hidden'>
					<div class='ewd-otp-admin-table-filter-label'><?php esc_html_e( 'Include Hidden Orders', 'order-tracking' ); ?></div>
					<fieldset>
						<div class='sap-admin-hide-radios'>
							<input type='checkbox' class='ewd-otp-orders-table-filter ewd-otp-include-hidden-orders ewd-otp-admin-table-filter-field' name='include_hidden_orders' value='1' <?php echo ( ! empty( $this->filter_include_hidden_orders ) ? 'checked' : '' ); ?> />
						</div>
						<label class='sap-admin-switch'>
							<input type='checkbox' class='sap-admin-option-toggle' data-inputname='include_hidden_orders' <?php echo ( ! empty( $this->filter_include_hidden_orders ) ? 'checked' : '' ); ?> />
							<span class='sap-admin-switch-slider round'></span>
						</label>
					</fieldset>
				</div>
			</div>
			<div class="ewd-otp-table-header-controls-right">
				<div class="tablenav top ewd-otp-top-actions-wrapper">
					<?php wp_nonce_field( 'bulk-orders' ); ?>
					<?php $this->extra_tablenav( $which ); ?>
					<?php parent::pagination( $which ); ?>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination
	 *
	 * @param string pos Position of this tablenav: `top` or `btm`
	 * @since 1.4.1
	 */
	public function extra_tablenav( $pos ) {

		do_action( 'ewd_otp_orders_table_actions', $pos );
	}

	/**
	 * Add notifications above the table to indicate which orders are
	 * being shown.
	 * @since 1.3
	 */
	public function add_notification() {

		global $ewd_otp_controller;

		$notifications = array();

		$status = '';
		if ( !empty( $_GET['status'] ) ) {
			if ( $status == 'paid' ) {
				$notifications['paid'] = __( "You're viewing orders that have been paid.", 'order-tracking' );
			}
			else {
				$notifications['status'] = __( "You're viewing orders that have a particular status.", 'order-tracking' );
			}
		}

		if ( !empty( $this->filter_start_date ) || !empty( $this->filter_end_date ) ) {
			$notifications['date'] = sprintf( _x( 'Only orders from %s are being shown.', 'Notification of booking date range, eg - orders from 2014-12-02-2014-12-05', 'order-tracking' ), $this->get_current_date_range() );
		} elseif ( !empty( $_GET['date_range'] ) && $_GET['date_range'] == 'today' ) {
			$notifications['date'] = __( "Only today's orders are being shown.", 'order-tracking' );
		}

		$notifications = apply_filters( 'ewd_otp_admin_orders_table_filter_notifications', $notifications );

		if ( !empty( $notifications ) ) :
		?>

			<div class="ewd-otp-notice <?php echo esc_attr( $status ); ?>">
				<?php echo join( ' ', $notifications ); ?>
			</div>

		<?php
		endif;
	}

	/**
	 * Retrieve the counts of orders
	 * @since 3.0.0
	 */
	public function get_order_counts() {
		global $ewd_otp_controller;

		$args = array(
			'display'			=> $this->filter_include_hidden_orders ? false : true,
		);

		if ( $this->filter_start_date !== null || $this->filter_end_date !== null ) {

			if ( $this->filter_start_date !== null ) {

				$start_date = new DateTime( $this->filter_start_date . ' ' . $this->filter_start_time );
				$args['after'] = $start_date->format( 'Y-m-d H:i:s' );
			}

			if ( $this->filter_end_date !== null ) {

				$end_date = new DateTime( $this->filter_end_date . ' ' . $this->filter_end_time );
				$args['before'] = $end_date->format( 'Y-m-d H:i:s' );
			}

		} 
		elseif ( !empty( $_GET['date_range'] ) ) {

			$args['date_range'] = sanitize_text_field( $_GET['date_range'] );
		}
		else {
			$args['date_range']	= 'all';
		}

		if ( $this->filter_order_number ) { $args['number'] = sanitize_text_field( $this->filter_order_number ); }

		$this->order_counts = $ewd_otp_controller->order_manager->get_order_counts( $args );
	}

	/**
	 * Retrieve all the data for all the orders
	 * @since 3.0.0
	 */
	public function orders_data() {
		global $ewd_otp_controller;

		$args = array(
			'orders_per_page'	=> $this->per_page,
			'display'			=> $this->filter_include_hidden_orders ? false : true,
		);

		if ( array_key_exists( 'paged', $_REQUEST ) ) {
			
			$args['paged'] = intval( $_REQUEST['paged'] );
		}

		if ( ! empty( $this->order_args['sales_rep'] ) ) { 

			$args['sales_rep'] = $this->order_args['sales_rep'];
		}

		if ( $this->filter_start_date !== null || $this->filter_end_date !== null ) {

			if ( !empty( $this->filter_start_date ) ) {

				$start_date = new DateTime( $this->filter_start_date . ' ' . $this->filter_start_time );
				$args['after'] = $start_date->format( 'Y-m-d H:i:s' );
			}
		
			if ( !empty( $this->filter_end_date ) ) {
			
				$end_date = new DateTime( $this->filter_end_date . ' ' . $this->filter_end_time );
				$args['before'] = $end_date->format( 'Y-m-d H:i:s' );
			}
		}
		elseif ( !empty( $_GET['date_range'] ) ) {

			$args['date_range'] = sanitize_text_field( $_GET['date_range'] );
		}

		if ( ! empty( $_GET['status'] ) ) {

			if ( $_GET['status'] == 'paid' ) { $args['paid'] = 'Yes'; }
		}

		if ( ! empty( $_GET['orderby'] ) ) {

			if ( $_GET['orderby'] == 'status_updated' ) { $args['orderby'] = 'Order_Status_Updated'; }
			elseif ( $_GET['orderby'] == 'customer_notes' ) { $args['orderby'] = 'Order_Customer_Notes'; }
			elseif ( $_GET['orderby'] == 'status' ) { $args['orderby'] = 'Order_Status'; }
			elseif ( $_GET['orderby'] == 'name' ) { $args['orderby'] = 'Order_Name'; }
			elseif ( $_GET['orderby'] == 'number' ) { $args['orderby'] = 'Order_Number'; }

			$args['order'] = ! empty( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'asc';
		}

		if ( $this->filter_order_number ) { $args['number'] = sanitize_text_field( $this->filter_order_number ); }

		if ( $this->filter_order_status ) { 

			$statuses = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );

			foreach ( $statuses as $status ) {

				if ( $this->filter_order_status != sanitize_title( $status->status, '', 'ewd_otp' ) ) { continue; }

				$args['status'] = $status->status; 
			}
		}

		$this->orders = $ewd_otp_controller->order_manager->get_matching_orders( $args );
	}

	/**
	 * Setup the final data for the table
	 * @since 3.0.0
	 */
	public function prepare_items() {

		$columns  = $this->get_columns();
		$hidden   = array(); // No hidden columns
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->items = $this->orders;

		$total_items   = empty( $_GET['status'] ) ? $this->order_counts['total'] : $this->order_counts[$_GET['status']];

		$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $this->per_page,
				'total_pages' => ceil( $total_items / $this->per_page )
			)
		);
	}

}
} // endif;
