<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdotpAdminOrders' ) ) {
/**
 * Class to handle the admin orders page for Order Tracking
 *
 * @since 3.0.0
 */
class ewdotpAdminOrders {

	/**
	 * The orders table
	 *
	 * This is only instantiated on the orders admin page at the moment when
	 * it is generated.
	 *
	 * @see self::show_admin_orders_page()
	 * @see WP_List_table.BookingsTable.class.php
	 * @since 3.0.0
	 */
	public $orders_table;

	public function __construct() {

		// Add the admin menu
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

		// Hide the 'Add New' item from the side menu
		add_action( 'admin_head', array( $this, 'hide_add_new_menu_item' ) );

		// Saves the orders per page screen option
		add_filter( 'set-screen-option', array( $this, 'save_screen_options' ), 10, 3 );
	}

	/**
	 * Add the top-level admin menu page
	 * @since 3.0.0
	 */
	public function add_menu_page() {
		global $ewd_otp_controller;

		$orders_page = add_menu_page(
			_x( 'Orders', 'Title of admin page that lists orders', 'order-tracking' ),
			_x( 'Tracking', 'Title of orders admin menu item', 'order-tracking' ),
			$ewd_otp_controller->settings->get_setting( 'access-role' ),
			'ewd-otp-orders',
			array( $this, 'show_admin_orders_page' ),
			'dashicons-location',
			'50.8'
		);

		add_action( 'load-' . $orders_page, array( $this, 'add_screen_options' ) );

		add_submenu_page( 
			'ewd-otp-orders', 
			_x( 'Add/Edit Order', 'Title of admin page that lets you add or edit an order', 'order-tracking' ),
			_x( 'Add New', 'Title of the add/edit order admin menu item', 'order-tracking' ), 
			'read', 
			'ewd-otp-add-edit-order', 
			array( $this, 'add_edit_order' )
		);
	}

	/**
	 * Hide the 'Add New' admin page from the WordPress sidebar menu
	 * @since 3.0.0
	 */
	public function hide_add_new_menu_item() {

		remove_submenu_page( 'ewd-otp-orders', 'ewd-otp-add-edit-order' );
	}

	/**
	 * Display the admin orders page
	 * @since 3.0.0
	 */
	public function show_admin_orders_page() {

		require_once( EWD_OTP_PLUGIN_DIR . '/includes/WP_List_Table.OrdersTable.class.php' );
		$this->orders_table = new ewdotpOrdersTable();
		$this->orders_table->prepare_items();
		?>

		<div class="wrap">
			<h1>
				<?php _e( 'Orders', 'order-tracking' ); ?>
				<a href="admin.php?page=ewd-otp-add-edit-order" class="add-new-h2 page-title-action add-order"><?php _e( 'Add New', 'order-tracking' ); ?></a>
			</h1>

			<?php do_action( 'ewd_otp_orders_table_top' ); ?>
			<form id="ewd-otp-orders-table" method="POST" action="">
				<input type="hidden" name="page" value="ewd-otp-orders">

				<div class="ewd-otp-primary-controls clearfix">
					<div class="ewd-otp-views">
						<?php $this->orders_table->views(); ?>
					</div>
					<?php $this->orders_table->advanced_filters(); ?>
				</div>

				<?php $this->orders_table->display(); ?>
			</form>
			<?php do_action( 'ewd_otp_orders_table_bottom' ); ?>
		</div>

		<?php
	}

	/**
	 * Adds screen options to the "Orders" screen
	 * @since 3.0.10
	 */
	public function add_screen_options() {

		$args = array(
			'label'		=> __( 'Orders per page', 'order-tracking' ),
			'default'	=> 30,
			'option'	=> 'ewd_otp_orders_per_page'
		);

		add_screen_option( 'per_page', $args );
	}

	/**
	 * Save the screen options for the "Orders" screen
	 * @since 3.0.10
	 */
	public function save_screen_options( $status, $option, $value ) {

		if ( $option == 'ewd_otp_orders_per_page' ) { return $value; }
	}

	/**
	 * Display the order add/edit page
	 * @since 3.0.0
	 */
	public function add_edit_order() {
		global $ewd_otp_controller;

		if ( ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) {

			$sales_rep = new ewdotpSalesRep();

			$sales_rep->load_sales_rep_from_wp_id( get_current_user_id() );

			if ( empty( $sales_rep->id ) ) { return; }
		}


		$order_id = ! empty( $_POST['ewd_otp_order_id'] ) ? intval( $_POST['ewd_otp_order_id'] ) :
					( ! empty( $_GET['order_id'] ) ? intval( $_GET['order_id'] ) : 0 );

		$order = new ewdotpOrder();
		
		if ( $order_id ) { 

			$order->load_order_from_id( $order_id );

			$order->load_order_status_history();
		}

		// Delete an entry for status history
		if ( array_key_exists( 'action', $_GET ) and 'delete_status' == $_GET['action'] ) {

			$ewd_otp_controller->order_manager->delete_order_status( $_GET['status_id'] );

			$order->load_order_status_history();
		}

		if ( isset( $_POST['ewd_otp_admin_order_submit'] ) ) {
	
			$order->process_admin_order_submission();
		}

		ewd_otp_load_view_files();

		$args = array(
			'order'	=> $order
		);

		if ( ! empty( $sales_rep ) ) { 

			$args['sales_rep'] = $sales_rep;
		}
		
		$admin_order_view = new ewdotpAdminOrderFormView( $args );

		echo $admin_order_view->render();
	}
}
} // endif;
