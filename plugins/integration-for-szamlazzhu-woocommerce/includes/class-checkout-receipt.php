<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WC_Szamlazz_Checkout_Receipt', false ) ) :
	class WC_Szamlazz_Checkout_Receipt {

		//Init
		public static function init() {
			add_filter( 'woocommerce_checkout_fields' , array( __CLASS__, 'add_receipt_field_to_checkout' ) );
			add_action( 'wp_ajax_wc_szamlazz_receipt_check', array( __CLASS__, 'receipt_check_with_ajax' ) );
			add_action( 'wp_ajax_nopriv_wc_szamlazz_receipt_check', array( __CLASS__, 'receipt_check_with_ajax' ) );
			add_filter( 'woocommerce_checkout_get_value' , array( __CLASS__, 'receipt_get_checkout_value' ), 10, 2 );
			add_action( 'woocommerce_cart_updated', array( __CLASS__, 'store_receipt_session_data') );
			add_action( 'woocommerce_checkout_update_order_meta', array( __CLASS__, 'save_receipt_session_data') );
			add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'validate_checkout'), 10, 2);
		}

		//Validate based on payment methods
		public static function validate_checkout($fields, $errors){
			$chosen_gateway = WC()->session->get( 'chosen_payment_method' );
			$disabled_payment_methods = WC_Szamlazz()->get_option('receipts_invalid_payment_methods', array());
			if ( !in_array($chosen_gateway, $disabled_payment_methods) )
				return;
		
			$receipt = WC()->session->wc_szamlazz_receipt;
			if($receipt != 'invoice') {
				$available_gateways = WC()->payment_gateways->payment_gateways();
				$payment_method_label = '';
				foreach ($available_gateways as $available_gateway) {
					if($available_gateway->id == $chosen_gateway) {
						$payment_method_label = $available_gateway->title;
						break;
					}
				}
				$errors->add( 'validation', apply_filters('wc_szamlazz_receipt_payment_method_validation_message', sprintf(esc_html__('Please fill in your billing details if you prefer to use this payment method: %s', 'wc-szamlazz'), $payment_method_label), $fields) );
				WC()->session->set( 'wc_szamlazz_receipt', 'invoice' );
				WC()->session->set( 'reload_checkout', true );
			}
			
		}

		public static function store_receipt_session_data() {
			//Set session data, if not set previously
			if(!WC()->session->wc_szamlazz_receipt) {
				WC()->session->set( 'wc_szamlazz_receipt', 'receipt' );
			}
		}

		public static function receipt_get_checkout_value($value, $input) {
			if($input == 'wc_szamlazz_receipt') {
				$receipt = WC()->session->wc_szamlazz_receipt;
				if($receipt == 'invoice') {
					$value = true;
				} else {
					$value = false;
				}
			}
			return $value;
		}

		//Add E-Nyugta checkbox to checkout
		public static function add_receipt_field_to_checkout($fields) {

			//Allow plugins to show/hide the receipt filed conditionally
			if(!apply_filters('wc_szamlazz_receipt_enabled', true)) {
				WC()->session->__unset( 'wc_szamlazz_receipt' );
				return $fields;
			}

			$fields['billing']['wc_szamlazz_receipt'] = array(
				'type'	=> 'checkbox',
				'label'	=> esc_html__('I need an invoice instead of a receipt', 'wc-szamlazz'),
				'priority' => 0
			);

			//Hide billing fields if we don't need an invoice, just a receipt
			if(WC()->session->wc_szamlazz_receipt && WC()->session->wc_szamlazz_receipt == 'receipt') {
				if(WC_Szamlazz()->get_option('receipt_hidden_fields')) {
					foreach (WC_Szamlazz()->get_option('receipt_hidden_fields') as $field_to_hide) {
						foreach ($fields as $fields_group => $fields_in_group) {
							foreach ($fields_in_group as $field_id => $field_options) {
								if($field_to_hide == $field_id) {
									unset($fields[$fields_group][$field_id]);
								}
							}
						}
					}
				} else {
					unset($fields['billing']['billing_company']);
					unset($fields['billing']['billing_address_1']);
					unset($fields['billing']['billing_address_2']);
					unset($fields['billing']['billing_city']);
					unset($fields['billing']['billing_postcode']);
					unset($fields['billing']['billing_country']);
					unset($fields['billing']['billing_state']);
					unset($fields['billing']['billing_phone']);
					unset($fields['billing']['billing_address_2']);
					unset($fields['billing']['wc_szamlazz_adoszam']);
					unset($fields['order']['order_comments']);
				}
			}

			//Load temporary values for name and email, which was saved when receipt or invoice was switched on checkout
			$tempCustomer = WC()->session->wc_szamlazz_temp_customer;
			if($tempCustomer) {
				$storedFields = array('billing_first_name', 'billing_last_name', 'billing_email');
				foreach ($storedFields as $storedField) {
					if(isset(WC()->session->wc_szamlazz_temp_customer[$storedField]) && isset($fields['billing'][$storedField])) {
						$fields['billing'][$storedField]['default'] = WC()->session->wc_szamlazz_temp_customer[$storedField];
					}
				}
			}

			//Move email field below names
			$fields['billing']['billing_email']['priority'] = 21;

			return $fields;
		}

		public static function receipt_check_with_ajax() {
			check_ajax_referer( 'update-order-review', 'nonce' );

			if($_POST['checked'] == 'invoice') {
				WC()->session->set( 'wc_szamlazz_receipt', 'invoice' );
			} else {
				WC()->session->set( 'wc_szamlazz_receipt', 'receipt' );
			}

			//Update name and email, so if it was filled already, it will stay filled after a reload
			$customer = array();

			if ( ! empty( $_POST['billing_first_name'] ) ) {
				$customer['billing_first_name'] = $_POST['billing_first_name'];
			}

			if ( ! empty( $_POST['billing_last_name'] ) ) {
				$customer['billing_last_name'] = $_POST['billing_last_name'];
			}

			if ( ! empty( $_POST['billing_email'] ) ) {
				$customer['billing_email'] = $_POST['billing_email'];
			}

			WC()->session->set( 'wc_szamlazz_temp_customer', $customer);

			wp_send_json_success();
		}

		public static function save_receipt_session_data( $order_id ) {
			if ( ! empty( WC()->session->wc_szamlazz_receipt ) ) {

				//Save order type as receipt, if receipt is selected
				if(WC()->session->wc_szamlazz_receipt == 'receipt') {
					$order = wc_get_order($order_id);
					$order->update_meta_data( '_wc_szamlazz_type_receipt', true );
					$order->save();
				}

			}
		}

	}

	WC_Szamlazz_Checkout_Receipt::init();

endif;
