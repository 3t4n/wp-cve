<?php

/**
 * Bizappay Payment Gateway Classs
 */
class bizappay extends WC_Payment_Gateway {
	function __construct() {
		$this->id = "Bizappay";

		$this->method_title = __( "Bizappay", 'Bizappay' );

		$this->method_description = __( "Bizappay Payment Gateway Plug-in for WooCommerce", 'Bizappay' );

		$this->title = __( "Bizappay", 'Bizappay' );

		$this->icon = 'https://bizappay.com/asset/img/bp_wp_logo.png';

		$this->has_fields = true;

		$this->init_form_fields();

		$this->init_settings();

		foreach ( $this->settings as $setting_key => $value ) {
			$this->$setting_key = $value;
		}

		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
				$this,
				'process_admin_options'
			) );
		}
	}

	# Build the administration fields for this specific Gateway
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'        => array(
				'title'   => __( 'Enable / Disable', 'Bizappay' ),
				'label'   => __( 'Enable this payment gateway', 'Bizappay' ),
				'type'    => 'checkbox',
				'default' => 'no',
			),
			'title'          => array(
				'title'    => __( 'Title', 'Bizappay' ),
				'type'     => 'text',
				'desc_tip' => __( 'Payment title the customer will see during the checkout process.', 'Bizappay' ),
				'default'  => __( 'Bizappay', 'Bizappay' ),
			),
			'description'    => array(
				'title'    => __( 'Description', 'Bizappay' ),
				'type'     => 'textarea',
				'desc_tip' => __( 'Payment description the customer will see during the checkout process.', 'Bizappay' ),
				'default'  => __( 'Pay securely using your online banking through Bizappay.', 'Bizappay' ),
				'css'      => 'max-width:350px;'
			),
			'universal_enabled_testing' => array(
				'title'   => __( 'Enable Sandbox Mode', 'Bizappay' ),
				'label'   => __( 'You must have a sandbox account at https://stg.bizappay.my. Make sure Category Code, Bizappay Email / Username and User Secret Key follows your Bizappay sandbox account. Remember! all sanbox transactions will not get paid from Bizappay.', 'Bizappay' ),
				'type'    => 'checkbox',
				'default' => 'no',
			),
			'universal_category' => array(
				'title'    => __( 'Category Code', 'Bizappay' ),
				'type'     => 'text',
				'desc_tip' => __( 'This category code you can obtain from your Bizappay category page', 'Bizappay' ),
			),
			'universal_form' => array(
				'title'    => __( 'Bizappay Email / Username', 'Bizappay' ),
				'type'     => 'text',
				'desc_tip' => __( 'This is your BIZAPPAY\'s username or email that registered at Bizappay', 'Bizappay' ),
			),
			'secretkey'      => array(
				'title'    => __( 'User Secret Key', 'Bizappay' ),
				'type'     => 'text',
				'desc_tip' => __( 'This is the secret key that you can obtain from profile page in Bizappay', 'Bizappay' ),
			)
		);
	}

	# Submit payment
	public function process_payment( $order_id ) {
		# Get this order's information so that we know who to charge and how much
		$customer_order = wc_get_order( $order_id );

		# Prepare the data to send to Bizappay
		$detail = "Order_WC" . $order_id;

		// set url variable
		$url = '';

		$old_wc = version_compare( WC_VERSION, '3.0', '<' );

		if ( $old_wc ) {
			$order_id = $customer_order->id;
			$amount   = $customer_order->order_total;
			$name     = $customer_order->billing_first_name . ' ' . $customer_order->billing_last_name;
			$email    = $customer_order->billing_email;
			$phone    = $customer_order->billing_phone;
		} else {
			$order_id = $customer_order->get_id();
			$amount   = $customer_order->get_total();
			$name     = $customer_order->get_billing_first_name() . ' ' . $customer_order->get_billing_last_name();
			$email    = $customer_order->get_billing_email();
			$phone    = $customer_order->get_billing_phone();
		}

		$hash_value = md5( $this->secretkey . $detail . $amount . $order_id );

		$post_args = array(
			'detail'   => $detail,
			'amount'   => $amount,
			'order_id' => $order_id,
			'hash'     => $hash_value,
			'name'     => $name,
			'email'    => $email,
			'merchant'    => $this->universal_form,
			'category'    => $this->universal_category,
			'phone'    => $phone,
			'checkout_page' => wc_get_checkout_url()
		);

		if($this->universal_enabled_testing == "yes") {
			$url = 'https://stg.bizappay.my/api/wordpress?';
		} else {
			$url = 'https://bizappay.my/api/wordpress?';
		}

		# Format it properly using get
		$bizappay_args = '';
		foreach ( $post_args as $key => $value ) {
			if ( $bizappay_args != '' ) {
				$bizappay_args .= '&';
			}
			$bizappay_args .= $key . "=" . $value;
		}

		return array(
			'result'   => 'success',
			'redirect' => $url . $bizappay_args
		);
	}

	public function check_bizappay_response() {
		
		sleep(3);

		$msg = filter_input(INPUT_GET, "msg", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$order_id = filter_input(INPUT_GET, "order_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$status_id =  filter_input(INPUT_GET, "status_id", FILTER_SANITIZE_NUMBER_INT);
		$transaction_id = filter_input(INPUT_GET, "transaction_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$hash = filter_input(INPUT_GET, "hash", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$porder_id = filter_input(INPUT_POST, "order_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);


		if ( isset($status_id) && isset($order_id) && $msg==true && isset($transaction_id) && isset($hash) ) {

			global $woocommerce;

			$is_callback = isset( $porder_id ) ? true : false;

			$order = wc_get_order( $order_id );

			$old_wc = version_compare( WC_VERSION, '3.0', '<' );

			$order_id = $old_wc ? $order->id : $order->get_id();

			if ( $order && $order_id != 0 ) {

				# Check if the data sent is valid based on the hash value
				$hash_value = md5( $this->secretkey . $status_id . $order_id . $transaction_id . $msg );
	
				if ( $hash_value == $hash ) {
					if ( $status_id == 1 || $status_id == '1' ) {
						if ( strtolower( $order->get_status() ) == 'pending' || strtolower( $order->get_status() ) == 'processing' ) {
							# only update if order is pending
							if ( strtolower( $order->get_status() ) == 'pending' ) {
								$order->payment_complete($transaction_id);

								$order->add_order_note( 'Payment successfully made through Bizappay. Transaction reference is ' . $transaction_id );
							}

							if ( $is_callback ) {
								echo 'OK';
							} else {
								# redirect to order receive page
								wp_redirect( $order->get_checkout_order_received_url() );

							}

							exit();
						}
					} else {
						if ( strtolower( $order->get_status() ) == 'pending' ) {
							if ( ! $is_callback ) {
								$order->add_order_note( 'Payment was pending' );
								add_filter( 'the_content', 'bizappay_payment_pending_msg' );
							}
						}
					}
				} else {

					add_filter( 'the_content', 'bizappay_hash_error_msg' );
				}
			}

			if ( $is_callback ) {
				echo 'OK';

				exit();
			}
		}
	}
	
	# this function used to call from backoffice system Bizappay
	public function callback_from_bizappay() {
	
		$msg = filter_input(INPUT_GET, "msg", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$order_id = filter_input(INPUT_GET, "order_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$status_id =  filter_input(INPUT_GET, "status_id", FILTER_SANITIZE_NUMBER_INT);
		$transaction_id = filter_input(INPUT_GET, "transaction_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$hash = filter_input(INPUT_GET, "hash", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		if ( isset($status_id) && isset($order_id) && $msg==true && isset($transaction_id) && isset($hash) ) {

			global $woocommerce;

			$order = wc_get_order( $order_id );

			$old_wc = version_compare( WC_VERSION, '3.0', '<' );

			$order_id = $old_wc ? $order->id : $order->get_id();

			if ( $order && $order_id != 0 ) {

				# Check if the data sent is valid based on the hash value
				$hash_value = md5( $this->secretkey . $status_id . $order_id . $transaction_id . $msg );
	
				if ( $hash_value == $hash ) {
					if ( $status_id == 1 || $status_id == '1' ) {
						if ( strtolower( $order->get_status() ) == 'pending' || strtolower( $order->get_status() ) == 'processing' ) {
							# only update if order is pending
							if ( strtolower( $order->get_status() ) == 'pending' ) {
								$order->payment_complete($transaction_id);

								$order->add_order_note( 'Payment successfully made through Bizappay. Transaction reference is ' . $transaction_id );
							}

							echo 'OK';
							exit();
						}
					} else {
						if ( strtolower( $order->get_status() ) == 'pending' && $status_id == 3 ) {
							
							$order->add_order_note( 'Payment was pending' );
							add_filter( 'the_content', 'bizappay_payment_pending_msg' );
							
						}
					}
				} else {

					add_filter( 'the_content', 'bizappay_hash_error_msg' );
				}
			}
		}
	}

	# Validate fields, do nothing for the moment
	public function validate_fields() {
		return true;
	}

	# Check if we are forcing SSL on checkout pages, Custom function not required by the Gateway for now
	public function do_ssl_check() {
		if ( $this->enabled == "yes" ) {
			if ( get_option( 'woocommerce_force_ssl_checkout' ) == "no" ) {
				echo "<div class=\"error\"><p>" . sprintf( __( "<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>" ), $this->method_title, admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . "</p></div>";
			}
		}
	}

	/**
	 * Check if this gateway is enabled and available in the user's country.
	 * Note: Not used for the time being
	 * @return bool
	 */
	public function is_valid_for_use() {
		return in_array( get_woocommerce_currency(), array( 'MYR' ) );
	}
}