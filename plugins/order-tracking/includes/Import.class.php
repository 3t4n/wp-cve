<?php

/**
 * Class to handle importing orders into the plugin
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if (!class_exists('ComposerAutoloaderInit4618f5c41cf5e27cc7908556f031e4d4')) {require_once EWD_OTP_PLUGIN_DIR . '/lib/PHPSpreadsheet/vendor/autoload.php';}
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class ewdotpImport {

	public $status;
	public $message;

	public function __construct() {
		add_action( 'admin_menu', array($this, 'register_install_screen' ));

		if ( isset( $_POST['ewd_otp_import_orders'] ) ) { add_action( 'admin_init', array($this, 'import_orders' )); }
		if ( isset( $_POST['ewd_otp_import_customers'] ) ) { add_action( 'admin_init', array($this, 'import_customers' )); }
		if ( isset( $_POST['ewd_otp_import_sales_reps'] ) ) { add_action( 'admin_init', array($this, 'import_sales_reps' )); }
	}

	public function register_install_screen() {
		global $ewd_otp_controller;
		
		add_submenu_page( 
			'ewd-otp-orders', 
			'Import Menu', 
			'Import', 
			$ewd_otp_controller->settings->get_setting( 'access-role' ), 
			'ewd-otp-import', 
			array($this, 'display_import_screen') 
		);
	}

	public function display_import_screen() {
		global $ewd_otp_controller;

		$import_permission = $ewd_otp_controller->permissions->check_permission( 'import' );
		?>
		<div class='wrap'>
			<h2>Import</h2>
			<?php if ( $import_permission ) { ?> 

				<h4><?php _e( 'Orders', 'order-tracking' ); ?></h4>
				<form method='post' enctype="multipart/form-data">
					
					<?php wp_nonce_field( 'EWD_OTP_Import', 'EWD_OTP_Import_Nonce' );  ?>

					<p>
						<label for="ewd_otp_orders_spreadsheet"><?php _e( 'Spreadsheet Containing Orders', 'order-tracking' ) ?></label><br />
						<input name="ewd_otp_orders_spreadsheet" type="file" value=""/>
					</p>
					<input type='submit' name='ewd_otp_import_orders' value='Import Orders' class='button button-primary' />
				</form>

				<h4><?php _e( 'Customers', 'order-tracking' ); ?></h4>
				<form method='post' enctype="multipart/form-data">
					
					<?php wp_nonce_field( 'EWD_OTP_Import', 'EWD_OTP_Import_Nonce' );  ?>

					<p>
						<label for="ewd_otp_customers_spreadsheet"><?php _e( 'Spreadsheet Containing Customers', 'order-tracking' ) ?></label><br />
						<input name="ewd_otp_customers_spreadsheet" type="file" value=""/>
					</p>
					<input type='submit' name='ewd_otp_import_customers' value='Import Customers' class='button button-primary' />
				</form>

				<h4><?php _e( 'Sales Reps', 'order-tracking' ); ?></h4>
				<form method='post' enctype="multipart/form-data">
					
					<?php wp_nonce_field( 'EWD_OTP_Import', 'EWD_OTP_Import_Nonce' );  ?>

					<p>
						<label for="ewd_otp_sales_reps_spreadsheet"><?php _e( 'Spreadsheet Containing Sales Reps', 'order-tracking' ) ?></label><br />
						<input name="ewd_otp_sales_reps_spreadsheet" type="file" value=""/>
					</p>
					<input type='submit' name='ewd_otp_import_sales_reps' value='Import Sales Reps' class='button button-primary' />
				</form>

			<?php } else { ?>
				<div class='ewd-otp-premium-locked'>
					<a href="https://www.etoilewebdesign.com/license-payment/?Selected=OTP&Quantity=1&utm_source=otp_import" target="_blank">Upgrade</a> to the premium version to use this feature
				</div>
			<?php } ?>
		</div>
	<?php }

	public function import_orders() {
		global $ewd_otp_controller;

		if ( ! current_user_can( 'edit_posts' ) ) { return; }

		if ( ! isset( $_POST['EWD_OTP_Import_Nonce'] ) ) { return; }

    	if ( ! wp_verify_nonce( $_POST['EWD_OTP_Import_Nonce'], 'EWD_OTP_Import' ) ) { return; }

    	$field_name = 'ewd_otp_orders_spreadsheet';

		$update = $this->handle_spreadsheet_upload( $field_name );

    	$custom_fields = $ewd_otp_controller->settings->get_order_custom_fields();

		if ( $update['message_type'] != 'Success' ) {

			$this->status = false;
			$this->message =  $update['message'];

			add_action( 'admin_notices', array( $this, 'display_notice' ) );

			return;
		}

		$excel_url = EWD_OTP_PLUGIN_DIR . '/order-sheets/' . $update['filename'];

	    // Build the workbook object out of the uploaded spreadsheet
	    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $excel_url );
	
	    // Create a worksheet object out of the product sheet in the workbook
	    $sheet = $spreadsheet->getActiveSheet();
	
	    $allowable_custom_fields = array();
	    foreach ( $custom_fields as $custom_field ) { $allowable_custom_fields[] = $custom_field->name; }
	    //List of fields that can be accepted via upload
	    $allowed_fields = array( 'Name', 'Number', 'Order Status', 'Location', 'Display', 'Notes Public', 'Notes Private', 'Email', 'Show in Admin Table', 'Sales Rep ID', 'Customer ID' );
	
	    // Get column names
	    $highest_column = $sheet->getHighestColumn();
	    $highest_column_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString( $highest_column );
	    for ( $column = 1; $column <= $highest_column_index; $column++ ) {

	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Name' ) { $name_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Number' ) { $number_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Order Status' ) { $status_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Location' ) { $location_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Display' or trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Show in Admin Table' ) { $display_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Notes Public' ) { $public_notes_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Notes Private' ) { $private_notes_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Email' ) { $email_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Sales Rep ID' ) { $sales_rep_id_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Customer ID' ) { $customer_id_column = $column; }
	
	        foreach ( $custom_fields as $custom_field ) {

        	    if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == $custom_field->name ) { $custom_field->column = $column; }
        	}
	    }

	    $name_column = ! empty( $name_column ) ? $name_column : -1;
	    $number_column = ! empty( $number_column ) ? $number_column : -1;
	    $status_column = ! empty( $status_column ) ? $status_column : -1;
	    $location_column = ! empty( $location_column ) ? $location_column : -1;
	    $display_column = ! empty( $display_column ) ? $display_column : -1;
	    $public_notes_column = ! empty( $public_notes_column ) ? $public_notes_column : -1;
	    $private_notes_column = ! empty( $private_notes_column ) ? $private_notes_column : -1;
	    $email_column = ! empty( $email_column ) ? $email_column : -1;
	    $sales_rep_id_column = ! empty( $sales_rep_id_column ) ? $sales_rep_id_column : -1;
	    $customer_id_column = ! empty( $customer_id_column ) ? $customer_id_column : -1;
	
	    // Put the spreadsheet data into a multi-dimensional array to facilitate processing
	    $highest_row = $sheet->getHighestRow();
	    for ( $row = 2; $row <= $highest_row; $row++ ) {
	        for ( $column = 1; $column <= $highest_column_index; $column++ ) {
	            $data[$row][$column] = $sheet->getCellByColumnAndRow( $column, $row )->getValue();
	        }
	    }
	
	    // Create the query to insert the products one at a time into the database and then run it
	    foreach ( $data as $order_data ) {

	    	// Save the data into an array, so that an order can be updated based on the order
	    	// number if it exists already
	        $order_data_array = array(
	        	'custom_fields'	=> array()
	        );

	        foreach ( $order_data as $col_index => $value ) {

	            if ( $col_index == $name_column ) { $order_data_array['name'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $number_column ) { $order_data_array['number'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $status_column ) { $order_data_array['status'] = $order_data['external_status'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $location_column ) { $order_data_array['location'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $display_column ) { $order_data_array['display'] = ( strtolower( $value ) == 'no' ? false : true ); }
            	elseif ( $col_index == $public_notes_column ) { $order_data_array['notes_public'] = sanitize_textarea_field( $value ); }
            	elseif ( $col_index == $private_notes_column ) { $order_data_array['notes_private'] = sanitize_textarea_field( $value ); }
            	elseif ( $col_index == $email_column ) { $order_data_array['email'] = sanitize_email( $value ); }
            	elseif ( $col_index == $sales_rep_id_column ) { $order_data_array['sales_rep'] = intval( $value ); }
            	elseif ( $col_index == $customer_id_column ) { $order_data_array['customer'] = intval( $value ); }
            	else {

            		foreach ( $custom_fields as $custom_field ) {

            			if ( $col_index == $custom_field->column ) { $order_data_array['custom_fields'][ $custom_field->id ] = sanitize_text_field( $value ); }
            		}
            	}
	        }
	        
	        // Create a new order object, and assign the imported values to it
	     	$order = new ewdotpOrder();

	     	$order_status = null;

	     	if ( ! empty( $order_data_array['number'] ) ) {

	     		$db_order_data = $ewd_otp_controller->order_manager->get_order_from_tracking_number( $order_data_array['number'] );

	     		if ( $db_order_data ) {

	     			$order->load_order( $db_order_data );

	     			$order_status = $ewd_otp_controller->order_manager->get_order_field( 'Order_Status', $order->id );
	     		}
	     	}

	     	if ( empty( $order->id ) ) { $order->display = true; }

	     	if ( ! empty( $order_data_array['name'] ) ) { $order->name = $order_data_array['name']; }
            if ( ! empty( $order_data_array['number'] ) ) { $order->number = $order_data_array['number']; }
            if ( ! empty( $order_data_array['status'] ) ) { $order->status = $order->external_status = $order_data_array['status']; }
            if ( ! empty( $order_data_array['location'] ) ) { $order->location = $order_data_array['location']; }
            if ( isset( $order_data_array['display'] ) ) { $order->display_column = $order_data_array['display']; }
            if ( ! empty( $order_data_array['notes_public'] ) ) { $order->notes_public = $order_data_array['notes_public']; }
            if ( ! empty( $order_data_array['notes_private'] ) ) { $order->notes_private = $order_data_array['notes_private']; }
            if ( ! empty( $order_data_array['email'] ) ) { $order->email = $order_data_array['email']; }
            if ( ! empty( $order_data_array['sales_rep'] ) ) { $order->sales_rep = $order_data_array['sales_rep']; }
            if ( ! empty( $order_data_array['customer'] ) ) { $order->customer = $order_data_array['customer']; }
            if ( ! empty( $order_data_array['custom_fields'] ) ) {

            	foreach ( $order_data_array['custom_fields'] as $field_id => $field_value ) {

            		$order->custom_fields[ $field_id ] = $field_value;
            	}
            }

            if ( empty( $order->customer ) and ! empty( $order->email ) and $ewd_otp_controller->settings->get_setting( 'allow-assign-orders-to-customers' ) ) {

				$order->customer = $ewd_otp_controller->customer_manager->get_customer_id_from_email( $order->email );
			}
       
	        if ( empty( $order->id ) ) { 

	        	$order->insert_order(); 

	        	$order->insert_order_status();

	        	do_action( 'ewd_otp_admin_order_inserted', $order );
	        }
	        else {

	        	$order->update_order();

	        	if ( ! empty( $order_data_array['status'] ) and $order_data_array['status'] != $order_status ) {

					$old_status = $order_status;

	        		$order->insert_order_status(); 

	        		do_action( 'ewd_otp_status_updated', $order, $old_status );
	        	}
	        }
	    }

	    $this->status = true;
		$this->message = __( 'Order(s) uploaded successfully.', 'order-tracking' );

		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}

	/**
	 * Takes customer information from a spreadsheet and adds it to the database
	 * @since 3.3.5
	 */
	public function import_customers() {
		global $ewd_otp_controller;

		if ( ! current_user_can( 'edit_posts' ) ) { return; }

		if ( ! isset( $_POST['EWD_OTP_Import_Nonce'] ) ) { return; }

    	if ( ! wp_verify_nonce( $_POST['EWD_OTP_Import_Nonce'], 'EWD_OTP_Import' ) ) { return; }

    	$field_name = 'ewd_otp_customers_spreadsheet';

		$update = $this->handle_spreadsheet_upload( $field_name );

    	$custom_fields = $ewd_otp_controller->settings->get_customer_custom_fields();

		if ( $update['message_type'] != 'Success' ) {

			$this->status = false;
			$this->message =  $update['message'];

			add_action( 'admin_notices', array( $this, 'display_notice' ) );

			return;
		}

		$excel_url = EWD_OTP_PLUGIN_DIR . '/order-sheets/' . $update['filename'];

	    // Build the workbook object out of the uploaded spreadsheet
	    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $excel_url );
	
	    // Create a worksheet object out of the product sheet in the workbook
	    $sheet = $spreadsheet->getActiveSheet();
	
	    $allowable_custom_fields = array();
	    foreach ( $custom_fields as $custom_field ) { $allowable_custom_fields[] = $custom_field->name; }
	    //List of fields that can be accepted via upload
	    $allowed_fields = array( 'Customer ID', 'Number', 'Name', 'Email', 'Sales Rep ID', 'WP ID', 'FEUP ID' );
	
	    // Get column names
	    $highest_column = $sheet->getHighestColumn();
	    $highest_column_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString( $highest_column );
	    for ( $column = 1; $column <= $highest_column_index; $column++ ) {

	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Customer ID' ) { $customer_id_column = $column; }
	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Number' ) { $number_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Name' ) { $name_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Email' ) { $email_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Sales Rep ID' ) { $sales_rep_id_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'WP ID' ) { $wp_id_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'FEUP ID' ) { $feup_id_column = $column; }
	
	        foreach ( $custom_fields as $custom_field ) {

        	    if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == $custom_field->name ) { $custom_field->column = $column; }
        	}
	    }

	    
	    $customer_id_column = ! empty( $customer_id_column ) ? $customer_id_column : -1;
	    $number_column = ! empty( $number_column ) ? $number_column : -1;
	    $name_column = ! empty( $name_column ) ? $name_column : -1;
	    $email_column = ! empty( $email_column ) ? $email_column : -1;
	    $sales_rep_id_column = ! empty( $sales_rep_id_column ) ? $sales_rep_id_column : -1;
	    $wp_id_column = ! empty( $wp_id_column ) ? $wp_id_column : -1;
	    $feup_id_column = ! empty( $feup_id_column ) ? $feup_id_column : -1;
	
	    // Put the spreadsheet data into a multi-dimensional array to facilitate processing
	    $highest_row = $sheet->getHighestRow();
	    for ( $row = 2; $row <= $highest_row; $row++ ) {
	        for ( $column = 1; $column <= $highest_column_index; $column++ ) {
	            $data[$row][$column] = $sheet->getCellByColumnAndRow( $column, $row )->getValue();
	        }
	    }
	
	    // Create the query to insert the customers one at a time into the database and then run it
	    foreach ( $data as $customer_data ) {

	    	// Save the data into an array, so that a customer can be updated based on the customer
	    	// ID if it exists already
	        $customer_data_array = array(
	        	'custom_fields'	=> array()
	        );

	        foreach ( $customer_data as $col_index => $value ) {

	            if ( $col_index == $customer_id_column ) { $customer_data_array['customer_id'] = intval( $value ); }
	            elseif ( $col_index == $number_column ) { $customer_data_array['number'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $name_column ) { $customer_data_array['name'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $email_column ) { $customer_data_array['email'] = sanitize_email( $value ); }
            	elseif ( $col_index == $sales_rep_id_column ) { $customer_data_array['sales_rep'] = intval( $value ); }
            	elseif ( $col_index == $wp_id_column ) { $customer_data_array['wp_id'] = intval( $value ); }
            	elseif ( $col_index == $feup_id_column ) { $customer_data_array['feup_id'] = intval( $value ); }
            	else {

            		foreach ( $custom_fields as $custom_field ) {

            			if ( $col_index == $custom_field->column ) { $customer_data_array['custom_fields'][ $custom_field->id ] = sanitize_text_field( $value ); }
            		}
            	}
	        }
	        
	        // Create a new customer object, and assign the imported values to it
	     	$customer = new ewdotpCustomer();

	     	if ( ! empty( $customer_data_array['customer_id'] ) ) {

	     		$db_customer_data = $ewd_otp_controller->customer_manager->get_customer_from_id( $customer_data_array['customer_id'] );

	     		if ( $db_customer_data ) {

	     			$customer->load_customer( $db_customer_data );
	     		}
	     	}

	     	if ( ! empty( $customer_data_array['number'] ) ) { $customer->number = $customer_data_array['number']; }
            if ( ! empty( $customer_data_array['name'] ) ) { $customer->name = $customer_data_array['name']; }
            if ( ! empty( $customer_data_array['email'] ) ) { $customer->email = $customer_data_array['email']; }
            if ( ! empty( $customer_data_array['sales_rep'] ) ) { $customer->sales_rep = $customer_data_array['sales_rep']; }
            if ( ! empty( $customer_data_array['wp_id'] ) ) { $customer->wp_id = $customer_data_array['wp_id']; }
            if ( ! empty( $customer_data_array['feup_id'] ) ) { $customer->feup_id = $customer_data_array['feup_id']; }
            if ( ! empty( $customer_data_array['custom_fields'] ) ) {

            	foreach ( $customer_data_array['custom_fields'] as $field_id => $field_value ) {

            		$customer->custom_fields[ $field_id ] = $field_value;
            	}
            }
       
	        if ( empty( $customer->id ) ) { 

	        	$customer->insert_customer(); 

	        	do_action( 'ewd_otp_admin_customer_inserted', $customer );
	        }
	        else {

	        	$customer->update_customer();
	        }
	    }

	    $this->status = true;
		$this->message = __( 'Customer(s) uploaded successfully.', 'order-tracking' );

		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}

	/**
	 * Takes sales rep information from a spreadsheet and adds it to the database
	 * @since 3.3.5
	 */
	public function import_sales_reps() {
		global $ewd_otp_controller;

		if ( ! current_user_can( 'edit_posts' ) ) { return; }

		if ( ! isset( $_POST['EWD_OTP_Import_Nonce'] ) ) { return; }

    	if ( ! wp_verify_nonce( $_POST['EWD_OTP_Import_Nonce'], 'EWD_OTP_Import' ) ) { return; }

    	$field_name = 'ewd_otp_sales_reps_spreadsheet';

		$update = $this->handle_spreadsheet_upload( $field_name );

    	$custom_fields = $ewd_otp_controller->settings->get_sales_rep_custom_fields();

		if ( $update['message_type'] != 'Success' ) {

			$this->status = false;
			$this->message =  $update['message'];

			add_action( 'admin_notices', array( $this, 'display_notice' ) );

			return;
		}

		$excel_url = EWD_OTP_PLUGIN_DIR . '/order-sheets/' . $update['filename'];

	    // Build the workbook object out of the uploaded spreadsheet
	    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $excel_url );
	
	    // Create a worksheet object out of the product sheet in the workbook
	    $sheet = $spreadsheet->getActiveSheet();
	
	    $allowable_custom_fields = array();
	    foreach ( $custom_fields as $custom_field ) { $allowable_custom_fields[] = $custom_field->name; }
	    //List of fields that can be accepted via upload
	    $allowed_fields = array( 'Sales Rep ID', 'Number', 'First Name', 'Last Name', 'Email', 'Phone Number', 'WP ID' );
	
	    // Get column names
	    $highest_column = $sheet->getHighestColumn();
	    $highest_column_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString( $highest_column );
	    for ( $column = 1; $column <= $highest_column_index; $column++ ) {

	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Sales Rep ID' ) { $sales_rep_id_column = $column; }
	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Number' ) { $number_column = $column; }
	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'First Name' ) { $first_name_column = $column; }
	    	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Last Name' ) { $last_name_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Email' ) { $email_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'Phone Number' ) { $phone_number_column = $column; }
        	if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == 'WP ID' ) { $wp_id_column = $column; }
	
	        foreach ( $custom_fields as $custom_field ) {

        	    if ( trim( $sheet->getCellByColumnAndRow( $column, 1 )->getValue() ) == $custom_field->name ) { $custom_field->column = $column; }
        	}
	    }

	    
	    $sales_rep_id_column = ! empty( $sales_rep_id_column ) ? $sales_rep_id_column : -1;
	    $number_column = ! empty( $number_column ) ? $number_column : -1;
	    $first_name_column = ! empty( $first_name_column ) ? $first_name_column : -1;
	    $last_name_column = ! empty( $last_name_column ) ? $last_name_column : -1;
	    $email_column = ! empty( $email_column ) ? $email_column : -1;
	    $phone_number_column = ! empty( $phone_number_column ) ? $phone_number_column : -1;
	    $wp_id_column = ! empty( $wp_id_column ) ? $wp_id_column : -1;
	
	    // Put the spreadsheet data into a multi-dimensional array to facilitate processing
	    $highest_row = $sheet->getHighestRow();
	    for ( $row = 2; $row <= $highest_row; $row++ ) {
	        for ( $column = 1; $column <= $highest_column_index; $column++ ) {
	            $data[$row][$column] = $sheet->getCellByColumnAndRow( $column, $row )->getValue();
	        }
	    }
	
	    // Create the query to insert the sales reps one at a time into the database and then run it
	    foreach ( $data as $sales_rep_data ) {

	    	// Save the data into an array, so that a sales rep can be updated based on the sales rep
	    	// ID if it exists already
	        $sales_rep_data_array = array(
	        	'custom_fields'	=> array()
	        );

	        foreach ( $sales_rep_data as $col_index => $value ) {

	            if ( $col_index == $sales_rep_id_column ) { $sales_rep_data_array['sales_rep_id'] = intval( $value ); }
	            elseif ( $col_index == $number_column ) { $sales_rep_data_array['number'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $first_name_column ) { $sales_rep_data_array['first_name'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $last_name_column ) { $sales_rep_data_array['last_name'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $email_column ) { $sales_rep_data_array['email'] = sanitize_email( $value ); }
            	elseif ( $col_index == $phone_number_column ) { $sales_rep_data_array['phone_number'] = sanitize_text_field( $value ); }
            	elseif ( $col_index == $wp_id_column ) { $sales_rep_data_array['wp_id'] = intval( $value ); }
            	else {

            		foreach ( $custom_fields as $custom_field ) {

            			if ( $col_index == $custom_field->column ) { $sales_rep_data_array['custom_fields'][ $custom_field->id ] = sanitize_text_field( $value ); }
            		}
            	}
	        }
	        
	        // Create a new sales_rep object, and assign the imported values to it
	     	$sales_rep = new ewdotpSalesRep();

	     	if ( ! empty( $sales_rep_data_array['sales_rep_id'] ) ) {

	     		$db_sales_rep_data = $ewd_otp_controller->sales_rep_manager->get_sales_rep_from_id( $sales_rep_data_array['sales_rep_id'] );

	     		if ( $db_sales_rep_data ) {

	     			$sales_rep->load_sales_rep( $db_sales_rep_data );
	     		}
	     	}

	     	if ( ! empty( $sales_rep_data_array['number'] ) ) { $sales_rep->number = $sales_rep_data_array['number']; }
            if ( ! empty( $sales_rep_data_array['first_name'] ) ) { $sales_rep->first_name = $sales_rep_data_array['first_name']; }
            if ( ! empty( $sales_rep_data_array['last_name'] ) ) { $sales_rep->last_name = $sales_rep_data_array['last_name']; }
            if ( ! empty( $sales_rep_data_array['email'] ) ) { $sales_rep->email = $sales_rep_data_array['email']; }
            if ( ! empty( $sales_rep_data_array['phone_number'] ) ) { $sales_rep->phone_number = $sales_rep_data_array['phone_number']; }
            if ( ! empty( $sales_rep_data_array['wp_id'] ) ) { $sales_rep->wp_id = $sales_rep_data_array['wp_id']; }
            if ( ! empty( $sales_rep_data_array['custom_fields'] ) ) {

            	foreach ( $sales_rep_data_array['custom_fields'] as $field_id => $field_value ) {

            		$sales_rep->custom_fields[ $field_id ] = $field_value;
            	}
            }
       
	        if ( empty( $sales_rep->id ) ) { 

	        	$sales_rep->insert_sales_rep(); 

	        	do_action( 'ewd_otp_admin_sales_rep_inserted', $sales_rep );
	        }
	        else {

	        	$sales_rep->update_sales_rep();
	        }
	    }

	    $this->status = true;
		$this->message = __( 'Sales Rep(s) uploaded successfully.', 'order-tracking' );

		add_action( 'admin_notices', array( $this, 'display_notice' ) );
	}

	function handle_spreadsheet_upload( $field_name ) {

		  /* Test if there is an error with the uploaded spreadsheet and return that error if there is */
        if ( ! empty( $_FILES[ $field_name ]['error'] ) ) {
                
            switch( $_FILES[ $field_name ]['error'] ) {

                case '1':
                    $error = __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 'order-tracking' );
                    break;
                case '2':
                    $error = __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'order-tracking' );
                    break;
                case '3':
                    $error = __( 'The uploaded file was only partially uploaded', 'order-tracking' );
                    break;
                case '4':
                    $error = __( 'No file was uploaded.', 'order-tracking' );
                    break;

                case '6':
                    $error = __( 'Missing a temporary folder', 'order-tracking' );
                    break;
                case '7':
                    $error = __( 'Failed to write file to disk', 'order-tracking' );
                    break;
                case '8':
                    $error = __( 'File upload stopped by extension', 'order-tracking' );
                    break;
                case '999':
                    default:
                    $error = __( 'No error code avaiable', 'order-tracking' );
            }
        }
        /* Make sure that the file exists */
        elseif ( empty($_FILES[ $field_name ]['tmp_name']) || $_FILES[ $field_name ]['tmp_name'] == 'none' ) {
                $error = __( 'No file was uploaded here..', 'order-tracking' );
        }
        /* Move the file and store the URL to pass it onwards*/
        /* Check that it is a .xls or .xlsx file */ 
        if ( ! isset($_FILES[ $field_name ]['name'] ) or ( ! preg_match("/\.(xls.?)$/", $_FILES[ $field_name ]['name'] ) and ! preg_match( "/\.(csv.?)$/", $_FILES[ $field_name ]['name'] ) ) ) {
            $error = __( 'File must be .csv, .xls or .xlsx', 'order-tracking' );
        }
        else {
            $filename = basename( $_FILES[ $field_name ]['name'] );
            $filename = mb_ereg_replace( "([^\w\s\d\-_~,;\[\]\(\).])", '', $filename );
            $filename = mb_ereg_replace ("([\.]{2,})", '', $filename );

            //for security reason, we force to remove all uploaded file
            $target_path = EWD_OTP_PLUGIN_DIR . "/order-sheets/";

            $target_path = $target_path . $filename;

            if ( ! move_uploaded_file($_FILES[ $field_name ]['tmp_name'], $target_path ) ) {
                $error .= "There was an error uploading the file, please try again!";
            }
            else {
                $excel_file_name = $filename;
            }
        }

        /* Pass the data to the appropriate function in Update_Admin_Databases.php to create the products */
        if ( ! isset( $error ) ) {
                $update = array( "message_type" => "Success", "filename" => $excel_file_name );
        }
        else {
                $update = array( "message_type" => "Error", "message" => $error );
        }

        return $update;
	}

	public function display_notice() {

		if ( $this->status ) {

			echo "<div class='updated'><p>" . esc_html( $this->message ) . "</p></div>";
		}
		else {

			echo "<div class='error'><p>" . esc_html( $this->message ) . "</p></div>";
		}
	}

}