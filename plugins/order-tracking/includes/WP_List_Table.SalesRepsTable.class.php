<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( !class_exists( 'ewdotpSalesRepsTable' ) ) {
/**
 * SalesReps Table Class
 *
 * Extends WP_List_Table to display the list of sales reps in a format similar to
 * the default WordPress post tables.
 *
 * @h/t Easy Digital Downloads by Pippin: https://easydigitaldownloads.com/
 * @since 3.0.0
 */
class ewdotpSalesRepsTable extends WP_List_Table {

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
	 * Array of sales rep counts by total and status
	 *
	 * @var array
	 * @since 3.0.0
	 */
	public $sales_rep_counts;

	/**
	 * Array of sales reps
	 *
	 * @var array
	 * @since 3.0.0
	 */
	public $sales_reps;

	/**
	 * Current sales rep first name
	 *
	 * @var int
	 * @since 1.6
	 */
	public $filter_sales_rep_first_name = '';

	/**
	 * Current sales rep last name
	 *
	 * @var int
	 * @since 1.6
	 */
	public $filter_sales_rep_last_name = '';

	/**
	 * Current sales rep email
	 *
	 * @var int
	 * @since 1.6
	 */
	public $filter_sales_rep_email = '';

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
	 * Initialize the table and perform any requested actions
	 *
	 * @since 3.0.0
	 */
	public function __construct() {

		global $status, $page;

		// Set parent defaults
		parent::__construct( array(
			'singular'  => __( 'Sales Rep', 'order-tracking' ),
			'plural'    => __( 'Sales Reps', 'order-tracking' ),
			'ajax'      => false
		) );

		// Strip unwanted query vars from the query string or ensure the correct
		// vars are used
		$this->query_string_maintenance();

		// Run any bulk action requests
		$this->process_bulk_action();

		// Retrieve a count of the number of sales reps by status
		$this->get_sales_rep_counts();

		// Retrieve sales reps data for the table
		$this->sales_reps_data();

		$this->base_url = admin_url( 'admin.php?page=ewd-otp-sales-reps' );
	}

	/**
	 * Strip unwanted query vars from the query string or ensure the correct
	 * vars are passed around and those we don't want to preserve are discarded.
	 *
	 * @since 3.0.0
	 */
	public function query_string_maintenance() {

		$this->query_string = remove_query_arg( array( 'action', 'start_date', 'end_date' ) );

		$this->filter_sales_rep_number = ! isset( $_GET['sales_rep_number'] ) ? '' : sanitize_text_field( $_GET['sales_rep_number'] );
		$this->filter_sales_rep_number = ! isset( $_POST['sales_rep_number'] ) ? $this->filter_sales_rep_number : sanitize_text_field( $_POST['sales_rep_number'] );
		$this->query_string = remove_query_arg( 'sales_rep_number', $this->query_string );
		if ( !empty( $this->filter_sales_rep_number ) ) {
			$this->query_string = add_query_arg( array( 'sales_rep_number' => $this->filter_sales_rep_number ), $this->query_string );
		}

		$this->filter_sales_rep_first_name = ! isset( $_GET['sales_rep_first_name'] ) ? '' : sanitize_text_field( $_GET['sales_rep_first_name'] );
		$this->filter_sales_rep_first_name = ! isset( $_POST['sales_rep_first_name'] ) ? $this->filter_sales_rep_first_name : sanitize_text_field( $_POST['sales_rep_first_name'] );
		$this->query_string = remove_query_arg( 'sales_rep_first_name', $this->query_string );
		if ( !empty( $this->filter_sales_rep_first_name ) ) {
			$this->query_string = add_query_arg( array( 'sales_rep_first_name' => $this->filter_sales_rep_first_name ), $this->query_string );
		}

		$this->filter_sales_rep_last_name = ! isset( $_GET['sales_rep_last_name'] ) ? '' : sanitize_text_field( $_GET['sales_rep_last_name'] );
		$this->filter_sales_rep_last_name = ! isset( $_POST['sales_rep_last_name'] ) ? $this->filter_sales_rep_last_name : sanitize_text_field( $_POST['sales_rep_last_name'] );
		$this->query_string = remove_query_arg( 'sales_rep_last_name', $this->query_string );
		if ( !empty( $this->filter_sales_rep_last_name ) ) {
			$this->query_string = add_query_arg( array( 'sales_rep_last_name' => $this->filter_sales_rep_last_name ), $this->query_string );
		}

		$this->filter_sales_rep_email = ! isset( $_GET['sales_rep_email'] ) ? '' : sanitize_text_field( $_GET['sales_rep_email'] );
		$this->filter_sales_rep_email = ! isset( $_POST['sales_rep_email'] ) ? $this->filter_sales_rep_email : sanitize_email( $_POST['sales_rep_email'] );
		$this->query_string = remove_query_arg( 'sales_rep_email', $this->query_string );
		if ( !empty( $this->filter_sales_rep_email ) ) {
			$this->query_string = add_query_arg( array( 'sales_rep_email' => $this->filter_sales_rep_email ), $this->query_string );
		}
	}

	/**
	 * No advanced filters for sales reps
	 * @since 3.0.0
	 */
	public function advanced_filters() {}

	/**
	 * Retrieve the view types
	 * @since 3.0.0
	 */
	public function get_views() {
		global $ewd_otp_controller; 

		$current = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '';

		$views = array(
			'all'		=> sprintf( '<a href="%s"%s>%s</a>', esc_url( remove_query_arg( array( 'status', 'paged' ), $this->query_string ) ), $current === 'all' || $current == '' ? ' class="current"' : '', __( 'All', 'order-tracking' ) . ' <span class="count">(' . $this->sales_rep_counts['total'] . ')</span>' ),
		);

		return apply_filters( 'ewd_otp_sales_reps_table_views_status', $views );
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

		$row_classes = apply_filters( 'ewd_otp_admin_sales_reps_list_row_classes', $row_classes, $item );

		echo '<tr class="' . esc_attr( implode( ' ', $row_classes ) ) . '">';
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

		$visible_columns = $ewd_otp_controller->settings->get_setting( 'sales-reps-table-columns' );
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

		$this->visible_columns = apply_filters( 'ewd_otp_sales_reps_table_columns', $columns );

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
			'cb'        	=> '<input type="checkbox" />', //Render a checkbox instead of text
			'sales_rep_id' 	=> __( 'Sales Rep ID', 'order-tracking' ),
			'number'   		=> __( 'Number', 'order-tracking' ),
			'first_name'   	=> __( 'First Name', 'order-tracking' ),
			'last_name'   	=> __( 'Last Name', 'order-tracking' ),
			'email' 		=> __( 'Email', 'order-tracking' ),
		);

		$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

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

		return apply_filters( 'ewd_otp_sales_reps_all_table_columns', $columns );
	}

	/**
	 * Retrieve the table's sortable columns
	 * @since 3.0.0
	 */
	public function get_sortable_columns() {
		global $ewd_otp_controller;

		$columns = array(
			'number' 		=> array( 'number', true ),
			'first_name' 	=> array( 'first_name', true ),
			'last_name' 	=> array( 'last_name', true ),
			'email' 		=> array( 'email', true ),
		);

		return apply_filters( 'ewd_otp_sales_reps_table_sortable_columns', $columns );
	}

	/**
	 * This function renders most of the columns in the list table.
	 * @since 3.0.0
	 */
	public function column_default( $sales_rep, $column_name ) {
		global $ewd_otp_controller;

		switch ( $column_name ) {

			case 'sales_rep_id' :
				$value = esc_html( $sales_rep->id );
				break;

			case 'number' :
				
				$value = esc_html( $sales_rep->number );

				$value .= '<div class="actions">';
				$value .= '<a href="admin.php?page=ewd-otp-add-edit-sales-rep&sales_rep_id=' . $sales_rep->id . '" data-id="' . esc_attr( $sales_rep->id ) . '">' . __( 'Edit', 'order-tracking' ) . '</a>';
				$value .= ' | <a href="#" class="delete" data-id="' . esc_attr( $sales_rep->id ) . '" data-action="delete">' . __( 'Delete', 'order-tracking' ) . '</a>';
				$value .= '</div>';

				break;

			case 'first_name' :
				
				$value = esc_html( $sales_rep->first_name );

				break;

			case 'last_name' :
				$value = esc_html( $sales_rep->last_name );
				break;

			case 'email' :
				$value = esc_html( $sales_rep->email );
				break;

			default:
				$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

				foreach ( $custom_fields as $custom_field ) {
		
					if ( $custom_field->slug != $column_name ) { continue; }
		
					$value = isset( $sales_rep->custom_fields[ $custom_field->id ] ) ? esc_html( $sales_rep->custom_fields[ $custom_field->id ] ) : '';
				}

				break;

		}

		return apply_filters( 'ewd_otp_sales_reps_table_column', $value, $sales_rep, $column_name );
	}

	/**
	 * Render the checkbox column
	 * @since 3.0.0
	 */
	public function column_cb( $sales_rep ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			'sales_reps',
			$sales_rep->id
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

		return apply_filters( 'ewd_otp_sales_reps_table_bulk_actions', $actions );
	}

	/**
	 * Process the bulk actions
	 * @since 3.0.0
	 */
	public function process_bulk_action() {
		global $ewd_otp_controller;

		$ids    = isset( $_POST['sales_reps'] ) ? $_POST['sales_reps'] : false;
		$action = isset( $_POST['action'] ) ? $_POST['action'] : false;

		// Check bulk actions selector below the table
		$action = $action == '-1' && isset( $_POST['action2'] ) ? $_POST['action2'] : $action;

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
				$results[$id] = $ewd_otp_controller->sales_rep_manager->delete_sales_rep( intval( $id ) );
			}

			$results = apply_filters( 'ewd_otp_sales_reps_table_bulk_action', $results, $id, $action );
		}

		if( count( $results ) ) {
			$this->action_result = $results;
			$this->last_action = $action;
			add_action( 'ewd_otp_sales_reps_table_top', array( $this, 'admin_notice_bulk_actions' ) );
		}
	}

	/**
	 * Display an admin notice when a bulk action is completed
	 * @since 3.0.0
	 */
	public function admin_notice_bulk_actions() {

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
			<p><?php echo sprintf( _n( '%d sales rep deleted successfully.', '%d sales reps deleted successfully.', $success, 'order-tracking' ), $success ); ?></p>

			<?php endif; ?>
		</div>

		<?php
		endif;

		if ( $failure > 0 ) :
		?>

		<div id="ewd-otp-admin-notice-bulk-<?php esc_attr( $this->last_action ); ?>" class="error">
			<p><?php echo sprintf( _n( '%d sales rep had errors and could not be processed.', '%d sales reps had errors and could not be processed.', $failure, 'order-tracking' ), $failure ); ?></p>
		</div>

		<?php
		endif;
	}

	/**
	 * Generate the table navigation above or below the table
	 *
	 * This outputs a separate set of options above and below the table, in
	 * sales rep to make room for the locations, services and providers.
	 *
	 * @since 1.6
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
				<a id="ewd-otp-table-header-search-filter" href="#"><?php esc_html_e( 'Search', 'order-tracking' ); ?></a>
				<div class='ewd-otp-admin-table-filter-div ewd-otp-hidden'>
					<label class='ewd-otp-admin-table-filter-label'><?php esc_html_e( 'Sales Rep Number', 'order-tracking' ); ?></label>
					<input type='text' name='sales_rep_number' class='ewd-otp-sales-reps-table-filter ewd-otp-sales-rep-number ewd-otp-admin-table-filter-field' value='<?php echo ( empty( $this->filter_sales_rep_number ) ? esc_attr( $this->filter_sales_rep_number ) : '' ); ?>' />
				</div>
				<div class='ewd-otp-admin-table-filter-div ewd-otp-hidden'>
					<label class='ewd-otp-admin-table-filter-label'><?php esc_html_e( 'Sales Rep First Name', 'order-tracking' ); ?></label>
					<input type='text' name='sales_rep_first_name' class='ewd-otp-sales-reps-table-filter ewd-otp-sales-rep-first-name ewd-otp-admin-table-filter-field' value='<?php echo ( empty( $this->filter_sales_rep_first_name ) ? esc_attr( $this->filter_sales_rep_first_name ) : '' ); ?>' />
				</div>
				<div class='ewd-otp-admin-table-filter-div ewd-otp-hidden'>
					<label class='ewd-otp-admin-table-filter-label'><?php esc_html_e( 'Sales Rep Last Name', 'order-tracking' ); ?></label>
					<input type='text' name='sales_rep_last_name' class='ewd-otp-sales-reps-table-filter ewd-otp-sales-rep-last-name ewd-otp-admin-table-filter-field' value='<?php echo ( empty( $this->filter_sales_rep_last_name ) ? esc_attr( $this->filter_sales_rep_last_name ) : '' ); ?>' />
				</div>
				<div class='ewd-otp-admin-table-filter-div ewd-otp-hidden'>
					<label class='ewd-otp-admin-table-filter-label'><?php esc_html_e( 'Sales Rep Email', 'order-tracking' ); ?></label>
					<input type='text' name='sales_rep_email' class='ewd-otp-sales-reps-table-filter ewd-otp-sales-rep-email ewd-otp-admin-table-filter-field' value='<?php echo ( empty( $this->filter_sales_rep_email ) ? esc_attr( $this->filter_sales_rep_email ) : '' ); ?>' />
				</div>
			</div>
			<div class="ewd-otp-table-header-controls-right">
				<div class="tablenav top ewd-otp-top-actions-wrapper">
					<?php wp_nonce_field( 'bulk-' . $this->_args['plural'] ); ?>
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
		do_action( 'ewd_otp_sales_reps_table_actions', $pos );
	}

	/**
	 * Add notifications above the table to indicate which sales reps are
	 * being shown.
	 * @since 1.3
	 */
	public function add_notification() {

		global $ewd_otp_controller;

		$notifications = array();

		$notifications = apply_filters( 'ewd_otp_admin_sales_reps_table_filter_notifications', $notifications );

		if ( !empty( $notifications ) ) :
		?>

			<div class="ewd-otp-notice <?php echo esc_attr( $status ); ?>">
				<?php echo join( ' ', $notifications ); ?>
			</div>

		<?php
		endif;
	}

	/**
	 * Retrieve the counts of sales reps
	 * @since 3.0.0
	 */
	public function get_sales_rep_counts() {
		global $ewd_otp_controller;

		$args = array();

		if ( $this->filter_sales_rep_number ) { $args['number'] = sanitize_text_field( $this->filter_sales_rep_number ); }

		if ( $this->filter_sales_rep_first_name ) { $args['first_name'] = sanitize_text_field( $this->filter_sales_rep_first_name ); }

		if ( $this->filter_sales_rep_last_name ) { $args['last_name'] = sanitize_text_field( $this->filter_sales_rep_last_name ); }

		if ( $this->filter_sales_rep_email ) { $args['email'] = sanitize_text_field( $this->filter_sales_rep_email ); }

		$this->sales_rep_counts = $ewd_otp_controller->sales_rep_manager->get_sales_rep_counts( $args );
	}

	/**
	 * Retrieve all the data for all the sales reps
	 * @since 3.0.0
	 */
	public function sales_reps_data() {
		global $ewd_otp_controller;

		$args = array(
			'sales_reps_per_page'	=> $this->per_page,
		);

		if ( array_key_exists( 'paged', $_GET ) ) {
			
			$args['paged'] = intval( $_GET['paged'] );
		}

		if ( ! empty( $_GET['orderby'] ) ) {

			if ( $_GET['orderby'] == 'number' ) { $args['orderby'] = 'Sales_Rep_Number'; }
			elseif ( $_GET['orderby'] == 'first_name' ) { $args['orderby'] = 'Sales_Rep_First_Name'; }
			elseif ( $_GET['orderby'] == 'last_name' ) { $args['orderby'] = 'Sales_Rep_Last_Name'; }
			elseif ( $_GET['orderby'] == 'email' ) { $args['orderby'] = 'Sales_Rep_Email'; }

			$args['order'] = ! empty( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'asc';
		}

		if ( $this->filter_sales_rep_number ) { $args['number'] = sanitize_text_field( $this->filter_sales_rep_number ); }

		if ( $this->filter_sales_rep_first_name ) { $args['first_name'] = sanitize_text_field( $this->filter_sales_rep_first_name ); }

		if ( $this->filter_sales_rep_last_name ) { $args['last_name'] = sanitize_text_field( $this->filter_sales_rep_last_name ); }

		if ( $this->filter_sales_rep_email ) { $args['email'] = sanitize_text_field( $this->filter_sales_rep_email ); }

		$this->sales_reps = $ewd_otp_controller->sales_rep_manager->get_matching_sales_reps( $args );
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

		$this->items = $this->sales_reps;

		$total_items   = empty( $_GET['status'] ) ? $this->sales_rep_counts['total'] : $this->sales_rep_counts[$_GET['status']];

		$this->set_pagination_args( array(
				'total_items' => $total_items,
				'per_page'    => $this->per_page,
				'total_pages' => ceil( $total_items / $this->per_page )
			)
		);
	}

}
} // endif;
