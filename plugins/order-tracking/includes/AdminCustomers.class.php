<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdotpAdminCustomers' ) ) {
/**
 * Class to handle the admin customers page for Order Tracking
 *
 * @since 3.0.0
 */
class ewdotpAdminCustomers {

	/**
	 * The customers table
	 *
	 * This is only instantiated on the customers admin page at the moment when
	 * it is generated.
	 *
	 * @see self::show_admin_customers_page()
	 * @see WP_List_table.BookingsTable.class.php
	 * @since 3.0.0
	 */
	public $customers_table;

	public function __construct() {

		// Add the admin menu
		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 12 );

		// Hide the 'Add New' item from the side menu
		add_action( 'admin_head', array( $this, 'hide_add_new_menu_item' ), 12 );
	}

	/**
	 * Add the top-level admin menu page
	 * @since 3.0.0
	 */
	public function add_menu_page() {
		global $ewd_otp_controller;

		add_submenu_page( 
			'ewd-otp-orders', 
			_x( 'Customers', 'Title of admin page that lets you view all customers', 'order-tracking' ),
			_x( 'Customers', 'Title of the customers admin menu item', 'order-tracking' ), 
			$ewd_otp_controller->settings->get_setting( 'access-role' ), 
			'ewd-otp-customers', 
			array( $this, 'show_admin_customers_page' )
		);

		add_submenu_page( 
			'ewd-otp-orders', 
			_x( 'Add/Edit Customer', 'Title of admin page that lets you add or edit an customer', 'order-tracking' ),
			_x( 'Add New', 'Title of the add/edit customer admin menu item', 'order-tracking' ), 
			$ewd_otp_controller->settings->get_setting( 'access-role' ), 
			'ewd-otp-add-edit-customer', 
			array( $this, 'add_edit_customer' )
		);
	}

	/**
	 * Hide the 'Add New' admin page from the WordPress sidebar menu
	 * @since 3.0.0
	 */
	public function hide_add_new_menu_item() {

		remove_submenu_page( 'ewd-otp-orders', 'ewd-otp-add-edit-customer' );
	}

	/**
	 * Display the admin customers page
	 * @since 3.0.0
	 */
	public function show_admin_customers_page() {
		global $ewd_otp_controller;

		require_once( EWD_OTP_PLUGIN_DIR . '/includes/WP_List_Table.CustomersTable.class.php' );
		$this->customers_table = new ewdotpCustomersTable();
		$this->customers_table->prepare_items();

		$permission = ( $ewd_otp_controller->permissions->check_permission( 'customers' ) or get_option( 'ewd-otp-installation-time' ) < 1664742505 ) ? true : false;
		?>

		<div class="wrap">
			<h1>
				<?php _e( 'Customers', 'order-tracking' ); ?>
				<?php if ( $permission ) { ?><a href="admin.php?page=ewd-otp-add-edit-customer" class="add-new-h2 page-title-action add-customer"><?php _e( 'Add New', 'order-tracking' ); ?></a><?php } ?>
			</h1>

			<?php if ( $permission ) { ?> 
	
				<?php do_action( 'ewd_otp_customers_table_top' ); ?>
				<form id="ewd-otp-customers-table" method="POST" action="">
					<input type="hidden" name="page" value="ewd-otp-customers">
	
					<div class="ewd-otp-primary-controls clearfix">
						<div class="ewd-otp-views">
							<?php $this->customers_table->views(); ?>
						</div>
						<?php $this->customers_table->advanced_filters(); ?>
					</div>
	
					<?php $this->customers_table->display(); ?>
				</form>
				<?php do_action( 'ewd_otp_customers_table_bottom' ); ?>

			<?php } else { ?>

				<div class='ewd-otp-premium-locked'>
					<a href="https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1" target="_blank">Upgrade</a> to the premium version to use this feature
				</div>
			<?php } ?>
		</div>

		<?php
	}

	/**
	 * Display the admin customers page
	 * @since 3.0.0
	 */
	public function add_edit_customer() {
		global $ewd_otp_controller;

		$customer_id = ! empty( $_POST['ewd_otp_customer_id'] ) ? intval( $_POST['ewd_otp_customer_id'] ) :
            ( ! empty( $_GET['customer_id'] ) ? intval( $_GET['customer_id'] ) : 0 );

		$customer = new ewdotpCustomer();

		if ( $customer_id ) { 

			$customer->load_customer_from_id( $customer_id );
		}

		if ( isset( $_POST['ewd_otp_admin_customer_submit'] ) ) {
	
			$customer->process_admin_customer_submission();
		}

		ewd_otp_load_view_files();

		$args = array(
			'customer'	=> $customer
		);
		
		$admin_customer_view = new ewdotpAdminCustomerFormView( $args );

		echo $admin_customer_view->render();
	}
}
} // endif;
