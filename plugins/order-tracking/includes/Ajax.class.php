<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdotpAJAX' ) ) {
	/**
	 * Class to handle AJAX interactions for Order Tracking
	 *
	 * @since 3.0.0
	 */
	class ewdotpAJAX {

		public function __construct() { 

			add_action( 'wp_ajax_ewd_otp_get_order', array( $this, 'get_order' ) );
			add_action( 'wp_ajax_nopriv_ewd_otp_get_order', array( $this, 'get_order' ) );

			add_action( 'wp_ajax_ewd_otp_get_customer_orders', array( $this, 'get_customer_orders' ) );
			add_action( 'wp_ajax_nopriv_ewd_otp_get_customer_orders', array( $this, 'get_customer_orders' ) );

			add_action( 'wp_ajax_ewd_otp_get_sales_rep_orders', array( $this, 'get_sales_rep_orders' ) );
			add_action( 'wp_ajax_nopriv_ewd_otp_get_sales_rep_orders', array( $this, 'get_sales_rep_orders' ) );

			add_action( 'wp_ajax_ewd_otp_update_customer_note', array( $this, 'update_customer_note' ) );
			add_action( 'wp_ajax_nopriv_ewd_otp_update_customer_note', array( $this, 'update_customer_note' ) );

			add_action( 'wp_ajax_ewd_otp_delete_order', array( $this, 'admin_delete_order' ) );
			add_action( 'wp_ajax_ewd_otp_hide_order', array( $this, 'admin_hide_order' ) );
			add_action( 'wp_ajax_ewd_otp_delete_customer', array( $this, 'admin_delete_customer' ) );
			add_action( 'wp_ajax_ewd_otp_delete_sales_rep', array( $this, 'admin_delete_sales_rep' ) );

			add_action( 'wp_ajax_ewd_otp_send_test_email', array( $this, 'send_test_email' ) );
		}

		/**
		 * Returns the output for a single order, given its tracking number and (optionally) email
		 * @since 3.0.0
		 */
		public function get_order() {
			global $ewd_otp_controller;

			// Authenticate request
			if ( ! check_ajax_referer( 'ewd-otp-js', 'nonce' ) ) {
				ewdotpHelper::admin_nopriv_ajax();
			}

			$order = new ewdotpOrder();

			$order->load_order_from_tracking_number( sanitize_text_field( $_POST['order_number'] ) );

			if ( empty( $order->id ) ) {

				wp_send_json_error(
					array(
						'output' => __( 'There are no order statuses for tracking number: ', 'order-tracking' ) . sanitize_text_field( $_POST['order_number'] )
					)
				);
			}

			if ( $ewd_otp_controller->settings->get_setting( 'email-verification' ) and ! $order->verify_order_email( sanitize_email( $_POST['order_email'] ) ) ) { 

				wp_send_json_error(
					array(
						'output' => __( 'The email submitted does not match the email associated with this order.', 'order-tracking' )
					)
				);
			}

			$order->load_order_status_history();

			$customer = new ewdotpCustomer();

			$customer->load_customer_from_id( $order->customer );

			$sales_rep = new ewdotpSalesRep();

			$sales_rep->load_sales_rep_from_id( $order->sales_rep );

			$args = array(
				'order'			=> $order,
				'customer'		=> $customer,
				'sales_rep'		=> $sales_rep,
				'notes_submit'	=> sanitize_text_field( $_POST['customer_notes_label'] )
			);

			$order_view = new ewdotpViewOrderForm( $args );

			$order_view->set_order_form_options();

			ob_start();

			$order_view->maybe_print_order_results();

			$output = ob_get_clean();

			wp_send_json_success(
				array(
					'output'	=> $output
				)
			);

			die();
		}

		/**
		 * Returns the customer order table for a given customer id
		 * @since 3.0.0
		 */
		public function get_customer_orders() {
			global $ewd_otp_controller;

			// Authenticate request
			if ( ! check_ajax_referer( 'ewd-otp-js', 'nonce' ) ) {
				ewdotpHelper::admin_nopriv_ajax();
			}

			$customer = new ewdotpCustomer();

			$customer->load_customer_from_number( sanitize_text_field( trim( $_POST['customer_number'] ) ) );

			if ( $ewd_otp_controller->settings->get_setting( 'email-verification' ) and ! $customer->verify_customer_email( sanitize_email( $_POST['customer_email'] ) ) ) { 

				wp_send_json_error(
					array(
						'output' => __( 'The email submitted does not match the email associated with this customer.', 'order-tracking' )
					)
				);
			}

			$args = array(
				'customer'	=> $customer
			);

			$customer_view = new ewdotpViewCustomerForm( $args );

			$customer_view->set_customer_orders();

			ob_start();

			$customer_view->maybe_print_customer_results();

			$output = ob_get_clean();

			if( !$output ) {

				$customer_view->error_message = __( 'No orders were found associated with the submitted customer number', 'order-tracking' );

				ob_start();

				$customer_view->print_error_message();

				$output = ob_get_clean();
			}

			wp_send_json_success(
				array(
					'output'	=> $output
				)
			);

			die();
		}

		/**
		 * Returns the sales rep order table for a given sales rep id
		 * @since 3.0.0
		 */
		public function get_sales_rep_orders() {
			global $ewd_otp_controller;

			// Authenticate request
			if ( ! check_ajax_referer( 'ewd-otp-js', 'nonce' ) ) {
				ewdotpHelper::admin_nopriv_ajax();
			}

			$sales_rep = new ewdotpSalesRep();

			$sales_rep->load_sales_rep_from_number( sanitize_text_field( trim( $_POST['sales_rep_number'] ) ) );

			if ( $ewd_otp_controller->settings->get_setting( 'email-verification' ) and ! $sales_rep->verify_sales_rep_email( sanitize_email( $_POST['sales_rep_email'] ) ) ) { 

				wp_send_json_error(
					array(
						'output' => __( 'The email submitted does not match the email associated with this sales rep.', 'order-tracking' )
					)
				);
			}

			$args = array(
				'sales_rep'	=> $sales_rep
			);

			$sales_rep_view = new ewdotpViewSalesRepForm( $args );

			$sales_rep_view->set_sales_rep_orders();

			ob_start();

			$sales_rep_view->maybe_print_sales_rep_results();

			$output = ob_get_clean();

			if( !$output ) {

				$sales_rep_view->error_message = __( 'No orders were found associated with the submitted sales rep number.', 'order-tracking' );

				ob_start();

				$sales_rep_view->print_error_message();

				$output = ob_get_clean();
			}

			wp_send_json_success(
				array(
					'output'	=> $output
				)
			);

			die();
		}

		/**
		 * Updates the customer note for an order
		 * @since 3.0.0
		 */
		public function update_customer_note() {
			global $ewd_otp_controller;

			// Authenticate request
			if ( ! check_ajax_referer( 'ewd-otp-js', 'nonce' ) ) {
				ewdotpHelper::admin_nopriv_ajax();
			}

			$order = new ewdotpOrder();

			$order->load_order_from_tracking_number( sanitize_text_field( $_POST['order_number'] ) );

			if ( $order->id != intval( $_POST['order_id'] ) ) { return; }

			$order->customer_notes = sanitize_textarea_field( $_POST['customer_notes'] );

			$order->update_order();

			do_action( 'ewd_otp_customer_note_updated', $order );

			wp_send_json_success(
				array(
					'output'	=> __( 'Customer note has been successfully updated.', 'order-tracking' )
				)
			);

			die();
		}

		/**
		 * Deletes a single order via the admin page
		 * @since 3.0.0
		 */
		public function admin_delete_order() {
			global $ewd_otp_controller;

			// Authenticate request
			if ( 
				! check_ajax_referer( 'ewd-otp-admin-js', 'nonce' )
				or ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) )
			) {
				ewdotpHelper::admin_nopriv_ajax();
			}
			
			if ( ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) { return; }

			$ewd_otp_controller->order_manager->delete_order( intval( $_POST['order_id'] ) );
		}

		/**
		 * Hides an order from the admin page
		 * @since 3.0.0
		 */
		public function admin_hide_order() {
			global $ewd_otp_controller;

			// Authenticate request
			if ( 
				! check_ajax_referer( 'ewd-otp-admin-js', 'nonce' )
				or ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) )
			) {
				ewdotpHelper::admin_nopriv_ajax();
			}
			
			if ( ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) { return; }

			$order = new ewdotpOrder();

			$order->load_order_from_id( intval( $_POST['order_id'] ) );

			$order->display = false;

			$order->update_order();
		}

		/**
		 * Deletes a single customer via the admin page
		 * @since 3.0.0
		 */
		public function admin_delete_customer() {
			global $ewd_otp_controller;

			// Authenticate request
			if ( 
				! check_ajax_referer( 'ewd-otp-admin-js', 'nonce' )
				or ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) )
			) {
				ewdotpHelper::admin_nopriv_ajax();
			}

			if ( ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) { return; }

			$ewd_otp_controller->customer_manager->delete_customer( intval( $_POST['customer_id'] ) );
		}

		/**
		 * Deletes a single sales rep via the admin page
		 * @since 3.0.0
		 */
		public function admin_delete_sales_rep() {
			global $ewd_otp_controller;

			// Authenticate request
			if ( 
				! check_ajax_referer( 'ewd-otp-admin-js', 'nonce' )
				or ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) )
			) {
				ewdotpHelper::admin_nopriv_ajax();
			}

			if ( ! current_user_can( $ewd_otp_controller->settings->get_setting( 'access-role' ) ) ) { return; }

			$ewd_otp_controller->sales_rep_manager->delete_sales_rep( intval( $_POST['sales_rep_id'] ) );
		}

		/**
		 * Send a test email for the order emails
		 * @since 3.0.0
		 */
		public function send_test_email() {
			global $ewd_otp_controller;

			$email_address = sanitize_email( $_POST['email_address'] );
			$email_to_send = sanitize_text_field( $_POST['email_to_send'] );
			$order = new ewdotpOrder();

			$mail_success = $ewd_otp_controller->notifications->send_email( $email_to_send, $email_address, $order );

			if ( ! empty( $mail_success ) ) { 

				echo '<div class="ewd-otp-test-email-response">Success: Email has been sent successfully.</div>';
			}
			else {

				echo '<div class="ewd-otp-test-email-response">Error: Please check your email settings, or try using an SMTP email plugin to change email settings.</div>';
			}

			die();
		}
	}
}