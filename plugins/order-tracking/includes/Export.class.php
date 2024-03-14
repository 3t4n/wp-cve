<?php

/**
 * Class to export orders created by the plugin
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if (!class_exists('ComposerAutoloaderInit4618f5c41cf5e27cc7908556f031e4d4')) { require_once EWD_OTP_PLUGIN_DIR . '/lib/PHPSpreadsheet/vendor/autoload.php'; }
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
class ewdotpExport {

	// Set whether a valid nonce is needed before exporting orders
	public $nonce_check = true;

	/**
	 * all the messages to display
	 * array(
	 *   'error' => 'Some error!',
	 *   'info'  => 'Some info msg',
	 *   'success' => 'Some success',
	 *   'warning' => 'Some warning!'
	 * )
	 * @var array
	 */
	public $messages = array();

	public function __construct() {

		if ( isset( $_POST['ewd_otp_export'] ) ) { add_action( 'admin_menu', array( $this, 'run_export' ) ); }

		add_action( 'admin_menu', array($this, 'register_install_screen' ));
	}

	public function register_install_screen() {
		global $ewd_otp_controller;
		
		add_submenu_page(
			'ewd-otp-orders',
			'Export Menu',
			'Export',
			$ewd_otp_controller->settings->get_setting( 'access-role' ),
			'ewd-otp-export',
			array($this, 'display_export_screen')
		);

		// This is required to enqueue style however, we are not registering/rendering this
		require_once( EWD_OTP_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version' => '2.6.18',
				'lib_url' => EWD_OTP_PLUGIN_URL . '/lib/simple-admin-pages/',
				'theme'   => 'purple',
			)
		);
		$sap->add_page(
			'submenu',
			array(
				'id'            => 'ewd-otp-export',
				'title'         => __( 'Export', 'order-tracking' ),
				'menu_title'    => __( 'Export', 'order-tracking' ),
				'parent_menu'	=> 'ewd-otp-orders',
				'description'   => ''
			)
		);
	}

	public function display_export_screen() {
		global $ewd_otp_controller;

		$export_permission = $ewd_otp_controller->permissions->check_permission( 'export' );

		?>
		<div class="wrap sap-settings-page">
			<h1>Export</h1>

			<?php $this->display_messages(); ?>

			<?php if ( $export_permission ) { ?> 
				<form method='post'>
					<?php
						wp_nonce_field( 'EWD_OTP_Export', 'EWD_OTP_Export_Nonce' );

						// Fetch order status
						$order_status_list = ewd_otp_decode_infinite_table_setting( $ewd_otp_controller->settings->get_setting( 'statuses' ) );
						$customer_list = $this->get_customer_list();
						$sales_rep_list = $this->get_sales_rep_list();
						
						// set type being exported to orders if not set
						$_POST['type-of-record'] = isset( $_POST['type-of-record'] ) ? $_POST['type-of-record'] : 'order';
					?>

					<h2>Filters</h2>
					
					<table class="form-table ewd-otp-export-filters" role="presentation">

						<tr class="row type-of-record">
							<th><?php _e( 'Type of Record', 'order-tracking' ); ?></th>
							<td>
								<fieldset>
									<label for="type-of-record-order" class="sap-admin-input-container">
										<input type="radio" name="type-of-record" value="order" id="type-of-record-order" <?php echo $_POST['type-of-record'] == 'order' ? 'checked' : ''; ?>>
										<span class="sap-admin-radio-button"></span>
										<span><?php _e( 'Order', 'order-tracking' ); ?></span>
									</label>
									<label for="type-of-record-customer" class="sap-admin-input-container">
										<input type="radio" name="type-of-record" value="customer" id="type-of-record-customer" <?php echo $_POST['type-of-record'] == 'customer' ? 'checked' : ''; ?>>
										<span class="sap-admin-radio-button"></span>
										<span>Customer</span>
									</label>
									<label for="type-of-record-sales-rep" class="sap-admin-input-container">
										<input type="radio" name="type-of-record" value="sales-rep" id="type-of-record-sales-rep" <?php echo $_POST['type-of-record'] == 'sales-rep' ? 'checked' : ''; ?>>
										<span class="sap-admin-radio-button"></span>
										<span>Sales Rep</span>
									</label>
								</fieldset>
							</td>
						</tr>

						<tr class="row by-status <?php echo $_POST['type-of-record'] != 'order' ? 'ewd-otp-hidden' : ''; ?>" >
							<th><?php _e( 'Orders by Status', 'order-tracking' ); ?></th>
							<td>
								<fieldset>
									<?php foreach ($order_status_list as $record): ?>
										<label for="type-of-record-<?php echo esc_attr( $record->status );?>" class="sap-admin-input-container">
											<input type="checkbox" name="order-by-status[]" value="<?php echo $record->status;?>" id="type-of-record-<?php echo $record->status;?>" <?php echo isset($_POST['order-by-status']) && in_array($record->status, $_POST['order-by-status']) ? 'checked' : ''; ?>>
											<span class="sap-admin-checkbox"></span>
											<span><?php echo esc_html( $record->status );?></span>
										</label>
									<?php endforeach ?>
								</fieldset>
							</td>
						</tr>

						<tr class="row date-range <?php echo $_POST['type-of-record'] != 'order' ? 'ewd-otp-hidden' : ''; ?>">
							<th><?php _e( 'Orders from a Specific Date Range', 'order-tracking' ); ?></th>
							<td>
								<fieldset>
									<label for="date-range-today" class="sap-admin-input-container">
										<input type="radio" name="date_range" value="today" id="date-range-today" <?php echo isset( $_POST['date_range'] ) && 'today' == $_POST['date_range'] ? 'checked' : ''; ?>>
										<span class="sap-admin-radio-button"></span>
										<span><?php _e( sprintf( 'Today (%s)', date( get_option( 'date_format' ), strtotime( 'today' ) ) ), 'order-tracking' ); ?></span>
									</label>
									<label for="date-range-week" class="sap-admin-input-container">
										<input type="radio" name="date_range" value="week" id="date-range-week" <?php echo isset( $_POST['date_range'] ) && 'week' == $_POST['date_range'] ? 'checked' : ''; ?>>
										<span class="sap-admin-radio-button"></span>
										<span><?php _e( sprintf( 'This Week (%s - %s)', date( get_option( 'date_format' ), strtotime( 'monday this week' ) ), date( get_option( 'date_format' ), strtotime( 'sunday this week' ) ) ), 'order-tracking' ); ?></span>
									</label>
									<label for="date-range-past" class="sap-admin-input-container">
										<input type="radio" name="date_range" value="past" id="date-range-past" <?php echo isset( $_POST['date_range'] ) && 'past' == $_POST['date_range'] ? 'checked' : ''; ?>>
										<span class="sap-admin-radio-button"></span>
										<span><?php _e( 'Past', 'order-tracking' ); ?></span>
									</label>
									<label for="date-range-from"><?php _e( 'From', 'order-tracking' ); ?></label>
									<input type="date" name="start_date" id="date-range-from" value="<?php echo isset( $_POST['start_date'] ) ? esc_attr__( $_POST['start_date'] ) : ''; ?>">
									<label for="date-range-to"><?php _e( 'To', 'order-tracking' ); ?></label>
									<input type="date" name="end_date" id="date-range-to" value="<?php echo isset( $_POST['end_date'] ) ? esc_attr__( $_POST['end_date'] ) : ''; ?>">
								</fieldset>
							</td>
						</tr>

						<tr class="row customer-list <?php echo $_POST['type-of-record'] != 'order' ? 'ewd-otp-hidden' : ''; ?>">
							<th><?php _e( 'Orders from specific Customer(s)', 'order-tracking' ); ?></th>
							<td>
								<fieldset>
									<select name="order-of-customer[]" multiple>
										<?php
											foreach ($customer_list as $record) {
												$selected = isset($_POST['order-of-customer']) && in_array($record->id, $_POST['order-of-customer']) ? 'selected' : '';
												echo "<option value='{$record->id}' {$selected}>{$record->id} - {$record->name} ( {$record->email} )</option>";
											}
										?>
									</select>
								</fieldset>
							</td>
						</tr>

						<tr class="row sales-rep-list <?php echo $_POST['type-of-record'] != 'order' ? 'ewd-otp-hidden' : ''; ?>">
							<th><?php _e( 'Orders for specific Sales Rep(s)', 'order-tracking' ); ?></th>
							<td>
								<fieldset>
									<select name="order-of-sales-rep[]" multiple>
										<?php
											foreach ($sales_rep_list as $record) {
												$selected = isset($_POST['order-of-sales-rep']) && in_array($record->id, $_POST['order-of-sales-rep']) ? 'selected' : '';
												$l_name = !empty( $record->last_name ) ? " {$record->last_name}" : '';
												echo "<option value='{$record->id}' {$selected}>{$record->id} - {$record->first_name}{$l_name} ( {$record->email} )</option>";
											}
										?>
									</select>
								</fieldset>
							</td>
						</tr>

					</table>

					<input type='submit' name='ewd_otp_export' value='Export to Spreadsheet' class='button button-primary'>
					&nbsp;
					<a href="" class='button'><?php _e( 'Clear Form', 'order-tracking' ); ?></a>

				</form>
			<?php } else { ?>
				<div class='ewd-otp-premium-locked'>
					<a href="https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1&utm_source=otp_export" target="_blank">Upgrade</a> to the premium version to use this feature
				</div>
			<?php } ?>
		</div>
	<?php }


	public function run_export() {
		global $ewd_otp_controller;

		if ( $this->nonce_check and ! isset( $_POST['EWD_OTP_Export_Nonce'] ) ) {
			return;
		}

		if ( $this->nonce_check and ! wp_verify_nonce( $_POST['EWD_OTP_Export_Nonce'], 'EWD_OTP_Export' ) ) {
			return;
		}

		// Instantiate a new PHPExcel object
		$spreadsheet = new Spreadsheet();
		// Set the active Excel worksheet to sheet 0
		$spreadsheet->setActiveSheetIndex(0);

		$records = array('header' => array(), 'data' => array());

		if ( empty( $_POST['type-of-record'] ) or 'order' == $_POST['type-of-record'] ) {
			$records = $this->get_order_data();
		}
		elseif ( 'customer' == $_POST['type-of-record'] ) {
			$records = $this->get_customer_data();
		}
		elseif( 'sales-rep' == $_POST['type-of-record'] ) {
			$records = $this->get_sales_rep_data();
		}

		if( 1 > count( $records['data'] ) ) {
			$this->warning('No records found to export!');
			return;
		}

		// Adding header
		$row = 1; $col = 'A';
		foreach ($records['header'] as $value) {
			$spreadsheet->getActiveSheet()->setCellValue( $col.$row, $value );
			$col++;
		}

		//start while loop to get data
		$row = 2;
		foreach ( $records['data'] as $record ) {
			$col = 'A';
			foreach ( $record as $value ) {
				$spreadsheet->getActiveSheet()->setCellValue( $col.$row, $value );
				$col++;
			}
			$row++;
		}

		// Redirect output to a client’s web browser (Excel5)
		if ( ! isset( $format_type ) == 'csv' ) {

			ob_clean();

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $records['filename'] . '.csv"');
			header('Cache-Control: max-age=0');
			$objWriter = new Csv($spreadsheet);
			$objWriter->save('php://output');
		}
		else {

			ob_clean();

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $records['filename'] . '.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = new Xls($spreadsheet);
			$objWriter->save('php://output');
		}

		die();
	}

	public function get_order_data()
	{
		global $ewd_otp_controller;

		// Print out the regular order field labels
		$order_header = array(
			'Name',
			'Number',
			'Order Status',
			'Order Status Updated (Read-Only)',
			'Location',
			'Display',
			'Notes Public',
			'Notes Private',
			'Email',
			'Customer',
			'Sales Rep'
		);

		// Add custom fields to column headers
		$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$order_header[] = $custom_field->name;
		}

		$args = array(
			'display' 				=> true,
			'orders_per_page' => -1,
		);

		// Order status
		if( isset( $_POST['order-by-status'] ) && 0 < count( $_POST['order-by-status'] ) ) {

			$args['status'] = array_map( 'sanitize_text_field', $_POST['order-by-status'] );
		}

		// Order for Date-range
		if ( ! empty( $this->after ) ) {

			// Used to let sales-rep and customers download their data from front-end
			$args['after'] = $this->after;
		}
		elseif( isset( $_POST['date_range'] ) ) {

			$args['date_range'] = sanitize_text_field( $_POST['date_range'] );
		}
		elseif( isset( $_POST['start_date'] ) || isset( $_POST['end_date'] ) ) {

			// just to pass if in order_manager->prepare_args()
			$args['date_range'] = 'custom';
			$args['start_date'] = sanitize_text_field( $_POST['start_date'] );
			$args['end_date'] = sanitize_text_field( $_POST['end_date'] );
		}

		// Order of Customer('s)
		if ( ! empty( $this->customer_id ) ) {

			// Used to let sales-rep and customers download their data from front-end
			$args['customer'] = $this->customer_id;
		}
		elseif( isset( $_POST['order-of-customer'] ) && 0 < count( $_POST['order-of-customer'] ) ) {

			$args['customer'] = array_map( 'sanitize_text_field', $_POST['order-of-customer'] );
		}

		// Order of Sales Rep('s)
		if ( ! empty( $this->sales_rep_id ) ) {

			// Used to let sales-rep and customers download their data from front-end
			$args['sales_rep'] = $this->sales_rep_id;
		}
		elseif( isset( $_POST['order-of-sales-rep'] ) ) {

			$args['sales_rep'] = array_map( 'sanitize_text_field', $_POST['order-of-sales-rep'] );
		}

		// fetching orders
		$orders = $ewd_otp_controller->order_manager->get_matching_orders( $args );

		$data = array();

		foreach ( $orders as $order ) {

			$record = array(
				$order->name,
				$order->number,
				$order->status,
				$order->status_updated,
				$order->location,
				( $order->display ? 'Yes' : 'No' ),
				$order->notes_public,
				$order->notes_private,
				$order->email,
				max( $order->customer, 0 ),
				max( $order->sales_rep, 0 ),
			);

			// Adding custom field data
			foreach ( $custom_fields as $custom_field ) {

				$record[] = $ewd_otp_controller->order_manager->get_field_value( $custom_field->id, $order->id );
			}

			$data[] = $record;
		}

		return array(
			'header' 	=> $order_header,
			'data'   	=> $data,
			'filename'	=> 'order_export',
		);
	}

	public function get_customer_data() {
		global $ewd_otp_controller;

		// Print out the regular customer field labels
		$customer_header = array(
			'Customer ID',
			'Number',
			'Name',
			'Email',
			'Sales Rep ID',
			'WP ID',
			'FEUP ID',
		);

		// Add custom fields to column headers
		$custom_fields = $ewd_otp_controller->settings->get_customer_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$customer_header[] = $custom_field->name;
		}

		$args = array(
			'customers_per_page'	=> -1,
		);

		// fetching customers
		$customers = $ewd_otp_controller->customer_manager->get_matching_customers( $args );

		$data = array();

		foreach ( $customers as $customer ) {

			$record = array(
				$customer->id,
				$customer->number,
				$customer->name,
				$customer->email,
				max( $customer->sales_rep, 0 ),
				max( $customer->wp_id, 0 ),
				max( $customer->feup_id, 0 ),
			);

			// Adding custom field data
			foreach ( $custom_fields as $custom_field ) {

				$record[] = $ewd_otp_controller->customer_manager->get_field_value( $custom_field->id, $customer->id );
			}

			$data[] = $record;
		}

		return array(
			'header' 	=> $customer_header,
			'data'   	=> $data,
			'filename'	=> 'customer_export',
		);
	}

	public function get_sales_rep_data() {
		global $ewd_otp_controller;

		// Print out the regular sales rep field labels
		$sales_rep_header = array(
			'Sales Rep ID',
			'Number',
			'First Name',
			'Last Name',
			'Email',
			'Phone Number',
			'WP ID',
		);

		// Add custom fields to column headers
		$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

		foreach ( $custom_fields as $custom_field ) {

			$sales_rep_header[] = $custom_field->name;
		}

		$args = array(
			'sales_reps_per_page'	=> -1,
		);

		// fetching sales reps
		$sales_reps = $ewd_otp_controller->sales_rep_manager->get_matching_sales_reps( $args );

		$data = array();

		foreach ( $sales_reps as $sales_rep ) {

			$record = array(
				$sales_rep->id,
				$sales_rep->number,
				$sales_rep->first_name,
				$sales_rep->last_name,
				$sales_rep->email,
				$sales_rep->phone_number,
				max( $sales_rep->wp_id, 0 ),
			);

			// Adding custom field data
			foreach ( $custom_fields as $custom_field ) {

				$record[] = $ewd_otp_controller->sales_rep_manager->get_field_value( $custom_field->id, $customer->id );
			}

			$data[] = $record;
		}

		return array(
			'header' 	=> $sales_rep_header,
			'data'   	=> $data,
			'filename'	=> 'sales_rep_export',
		);
	}

	public function get_customer_list() {
		global $ewd_otp_controller;

		$args = array(
			'orderby' => 'Customer_Name',
			'order'   => 'asc',
			'customers_per_page' => -1
		);
		
		return $ewd_otp_controller->customer_manager->get_matching_customers( $args );
	}

	public function get_sales_rep_list() {
		global $ewd_otp_controller;

		$args = array(
			'orderby' => 'Sales_Rep_First_Name',
			'order'   => 'asc',
			'sales_reps_per_page' => -1
		);
		
		return $ewd_otp_controller->sales_rep_manager->get_matching_sales_reps( $args );
	}

	public function display_messages() {

		foreach ( $this->messages as $type => $msgs ) {

			echo "<div class='notice notice-{$type}''>";
				foreach ($msgs as $msg) {
					echo "<p>{$msg}</p>";
				}
			echo '</div>';
		}
	}

	public function warning( $msg ) {

		if ( !isset( $this->messages['warning'] ) ) {

			$this->messages['warning'] = array();
		}

		$this->messages['warning'][] = $msg;
	}

	public function error( $msg ) {

		if ( !isset( $this->messages['error'] ) ) {

			$this->messages['error'] = array();
		}

		$this->messages['error'][] = $msg;
	}

	public function success( $msg ) {

		if ( !isset( $this->messages['success'] ) ) {

			$this->messages['success'] = array();
		}

		$this->messages['success'][] = $msg;
	}

}


