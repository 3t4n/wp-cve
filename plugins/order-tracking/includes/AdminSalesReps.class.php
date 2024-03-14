<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdotpAdminSalesReps' ) ) {
/**
 * Class to handle the admin sales reps page for Order Tracking
 *
 * @since 3.0.0
 */
class ewdotpAdminSalesReps {

	/**
	 * The sales reps table
	 *
	 * This is only instantiated on the sales reps admin page at the moment when
	 * it is generated.
	 *
	 * @see self::show_admin_sales_reps_page()
	 * @see WP_List_table.BookingsTable.class.php
	 * @since 3.0.0
	 */
	public $sales_reps_table;

	public function __construct() {

		// Add the admin menu
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

		// Hide the 'Add New' item from the side menu
		add_action( 'admin_head', array( $this, 'hide_add_new_menu_item' ) );
	}

	/**
	 * Add the top-level admin menu page
	 * @since 3.0.0
	 */
	public function add_menu_page() {
		global $ewd_otp_controller;

		add_submenu_page( 
			'ewd-otp-orders', 
			_x( 'Sales Reps', 'Title of admin page that lets you view all sales reps', 'order-tracking' ),
			_x( 'Sales Reps', 'Title of the sales reps admin menu item', 'order-tracking' ), 
			$ewd_otp_controller->settings->get_setting( 'access-role' ), 
			'ewd-otp-sales-reps', 
			array( $this, 'show_admin_sales_reps_page' )
		);

		add_submenu_page( 
			'ewd-otp-orders', 
			_x( 'Add/Edit Sales Rep', 'Title of admin page that lets you add or edit an sales rep', 'order-tracking' ),
			_x( 'Add New', 'Title of the add/edit sales rep admin menu item', 'order-tracking' ), 
			$ewd_otp_controller->settings->get_setting( 'access-role' ), 
			'ewd-otp-add-edit-sales-rep', 
			array( $this, 'add_edit_sales_rep' )
		);

		$sales_rep = new ewdotpSalesRep();

		$sales_rep->load_sales_rep_from_wp_id( get_current_user_id() );

		if ( empty( $sales_rep->id ) or current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) { return; }

		add_menu_page(
			'Order Tracking Plugin', 
			'Order Tracking', 
			'read', 
			'ewd-otp-sales-rep-orders', 
			array( $this, 'show_sales_rep_orders_page' ), 
			'dashicons-location', 
			'50.9'
		);
	}

	/**
	 * Hide the 'Add New' admin page from the WordPress sidebar menu
	 * @since 3.0.0
	 */
	public function hide_add_new_menu_item() {

		remove_submenu_page( 'ewd-otp-orders', 'ewd-otp-add-edit-sales-rep' );
	}

	/**
	 * Display the admin sales reps page
	 * @since 3.0.0
	 */
	public function show_admin_sales_reps_page() {
		global $ewd_otp_controller;

		require_once( EWD_OTP_PLUGIN_DIR . '/includes/WP_List_Table.SalesRepsTable.class.php' );
		$this->sales_reps_table = new ewdotpSalesRepsTable();
		$this->sales_reps_table->prepare_items();

		$permission = ( $ewd_otp_controller->permissions->check_permission( 'sales_reps' ) or get_option( 'ewd-otp-installation-time' ) < 1664742505 ) ? true : false;
		?>

		<div class="wrap">
			<h1>
				<?php _e( 'Sales Reps', 'order-tracking' ); ?>
				<?php if ( $permission ) { ?><a href="admin.php?page=ewd-otp-add-edit-sales-rep" class="add-new-h2 page-title-action add-sales_rep"><?php _e( 'Add New', 'order-tracking' ); ?></a><?php } ?>
			</h1>

			<?php if ( $permission ) { ?> 

				<?php do_action( 'ewd_otp_sales_reps_table_top' ); ?>
				<form id="ewd-otp-sales-reps-table" method="POST" action="">
					<input type="hidden" name="page" value="ewd-otp-sales-reps">
	
					<div class="ewd-otp-primary-controls clearfix">
						<div class="ewd-otp-views">
							<?php $this->sales_reps_table->views(); ?>
						</div>
						<?php $this->sales_reps_table->advanced_filters(); ?>
					</div>
	
					<?php $this->sales_reps_table->display(); ?>
				</form>
				<?php do_action( 'ewd_otp_sales_reps_table_bottom' ); ?>

			<?php } else { ?>

				<div class='ewd-otp-premium-locked'>
					<a href="https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1" target="_blank">Upgrade</a> to the premium version to use this feature
				</div>
			<?php } ?>
		</div>

		<?php
	}

	/**
	 * Display the admin sales reps page
	 * @since 3.0.0
	 */
	public function show_sales_rep_orders_page() {

		$sales_rep = new ewdotpSalesRep();

		$sales_rep->load_sales_rep_from_wp_id( get_current_user_id() );

		if ( empty( $sales_rep->id ) ) { return; }

		$args['sales_rep'] = $sales_rep->id;
		
		require_once( EWD_OTP_PLUGIN_DIR . '/includes/WP_List_Table.OrdersTable.class.php' );
		$this->orders_table = new ewdotpOrdersTable( $args );
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
	 * Display the admin sales reps page
	 * @since 3.0.0
	 */
	public function add_edit_sales_rep() {
		global $ewd_otp_controller;

		$sales_rep_id = ! empty( $_POST['ewd_otp_sales_rep_id'] ) ? intval( $_POST['ewd_otp_sales_rep_id'] ) :
            ( ! empty( $_GET['sales_rep_id'] ) ? intval( $_GET['sales_rep_id'] ) : 0 );

		$sales_rep = new ewdotpSalesRep();

		if ( $sales_rep_id ) { 

			$sales_rep->load_sales_rep_from_id( $sales_rep_id );
		}

		if ( isset( $_POST['ewd_otp_admin_sales_rep_submit'] ) ) {
	
			$sales_rep->process_admin_sales_rep_submission();
		}

		ewd_otp_load_view_files();

		$args = array(
			'sales_rep'	=> $sales_rep
		);
		
		$admin_sales_rep_view = new ewdotpAdminSalesRepFormView( $args );

		echo $admin_sales_rep_view->render();
	}
}
} // endif;
