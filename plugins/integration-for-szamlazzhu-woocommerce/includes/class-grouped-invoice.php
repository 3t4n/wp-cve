<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_Grouped_Invoice', false ) ) :

	class WC_Szamlazz_Grouped_Invoice {

		public static function init() {
			add_filter( 'bulk_actions-edit-shop_order', array( __CLASS__, 'add_bulk_options'), 20, 1);
			add_action( 'admin_footer', array( __CLASS__, 'grouped_generate_modal' ) );
			add_action( 'wp_ajax_wc_szamlazz_generate_grouped_invoice', array( __CLASS__, 'generate_grouped_invoice' ) );
		}

		public static function add_bulk_options( $actions ) {
			$enabled_actions = WC_Szamlazz()->get_option('bulk_actions', array());
			if(in_array('grouped_generate', $enabled_actions)) {
				$actions['wc_szamlazz_bulk_grouped_generate'] = _x( 'Create combined invoice', 'bulk action', 'wc-szamlazz' );
			}
			return $actions;
		}

		public static function grouped_generate_modal() {
			global $typenow;
			if ( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ), true ) ) {
				include( dirname( __FILE__ ) . '/views/html-modal-grouped.php' );
			}
		}

		public static function generate_grouped_invoice() {
			check_ajax_referer( 'wc_szamlazz_generate_grouped_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}

			//Create response
			$response = array();
			$response['error'] = false;

			//Order status to change todo
			$order_status = str_replace( 'wc-', '', WC_Szamlazz()->get_option('grouped_invoice_status', 'no') );

			//Get the selected order ids
			$orders = sanitize_text_field($_POST['orders']);

			//Get the main order id(used as the base for the invoice)
			$main_order_id = intval($_POST['main_order']);
			$main_order = wc_get_order($main_order_id);

			//Convert submitted order ids to int array
			$order_ids = array_map('intval', explode(',', $orders));
			if (($key = array_search($main_order_id, $order_ids)) !== false) {
				//Remove the main order id from the array
				unset($order_ids[$key]);
			}

			//Add the main order id to the beginning of the array
			array_unshift($order_ids, $main_order_id);

			//Create an array of order numbers
			$order_numbers = array();
			foreach ($order_ids as $order_id) {
				$order = wc_get_order($order_id);
				$order_numbers[] = $order->get_order_number();
			}

			//Return error if invoice already exists for main order
			if(WC_Szamlazz()->is_invoice_generated($main_order_id, 'invoice')) {
				$response['error'] = true;
				$response['messages'][] = sprintf( esc_html__( 'An invoice has been already created for the selected orders(#%1$s).', 'wc-szamlazz' ), $main_order->get_order_number() );
				wp_send_json_success($response);
				return false;
			}

			//Create an invoice(multiple orders passed in the first parameter)
			$xml_response = WC_Szamlazz()->generate_invoice($order_ids, 'invoice');

			//If no error, save some other stuff too
			if(!$xml_response['error']) {

				//Loop through the orders
				foreach ($order_ids as $order_id) {
					$order = wc_get_order($order_id);

					//If its the main order, save a note that this contains a grouped invoice
					if($order_id == $main_order_id) {
						$order->add_order_note(sprintf( esc_html__( 'A combined invoice has been created for this order. This invoice also has line items from the following orders: #%1$s', 'wc-szamlazz' ), implode(', ', $order_numbers) ));
					}

					//If its not the main order, disable the auto invoice generation and store where to find the actual invoice
					if($order_id != $main_order_id) {
						$order->update_meta_data('_wc_szamlazz_own', sprintf( esc_html__( 'A combined invoice has been created for this order. You can find the invoice on the main order: #%1$s', 'wc-szamlazz' ), $order->get_order_number() ));
					}

					//Change order status if needed
					if($order_status != 'no') {
						$order->update_status($order_status);
					}

					//Save order
					$order->save();
				}
			}

			//Return response, both success and error
			$response = $xml_response;
			$response['order_link'] = $main_order->get_edit_order_url();
			wp_send_json_success($response);
		}
	}

	WC_Szamlazz_Grouped_Invoice::init();

endif;
