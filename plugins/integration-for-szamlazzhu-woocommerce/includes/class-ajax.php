<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_Ajax', false ) ) :

	class WC_Szamlazz_Ajax {
		public static $pdf_file_path;

		public static function init() {

			//Ajax functions related to invoices
			add_action( 'wp_ajax_wc_szamlazz_generate_invoice', array( __CLASS__, 'generate_invoice_with_ajax' ) );
			add_action( 'wp_ajax_wc_szamlazz_void_invoice', array( __CLASS__, 'void_invoice_with_ajax' ) );
			add_action( 'wp_ajax_wc_szamlazz_mark_completed', array( __CLASS__, 'mark_completed_with_ajax' ) );
			add_action( 'wp_ajax_wc_szamlazz_toggle_invoice', array( __CLASS__, 'toggle_invoice' ) );
			add_action( 'wp_ajax_wc_szamlazz_upload_document', array( __CLASS__, 'upload_document' ) );

			//Ajax functions related to receipts
			add_action( 'wp_ajax_wc_szamlazz_generate_receipt', array( __CLASS__, 'generate_receipt_with_ajax' ) );
			add_action( 'wp_ajax_wc_szamlazz_void_receipt', array( __CLASS__, 'void_receipt_with_ajax' ) );
			add_action( 'wp_ajax_wc_szamlazz_reverse_receipt', array( __CLASS__, 'reverse_receipt_with_ajax' ) );

		}

		//Generate Invoice with Ajax
		public static function generate_invoice_with_ajax() {
			check_ajax_referer( 'wc_szamlazz_generate_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			$order_id = intval($_POST['order']);

			//Generate invoice(either final, proform or deposit, based on $_POST['type'])
			$type = sanitize_text_field($_POST['type']);
			$response = WC_Szamlazz()->generate_invoice($order_id, $type);

			//Check if we need to create delivery note too, only if we already generated an invoice
			$order = wc_get_order($order_id);
			$need_delivery_note = (WC_Szamlazz()->get_option('delivery_note', 'no') == 'yes');
			$need_delivery_note = apply_filters('wc_szamlazz_need_delivery_note', $need_delivery_note, $order);
			if(!$response['error'] && $need_delivery_note && $_POST['type'] == 'invoice' && !WC_Szamlazz()->is_invoice_generated($order_id, 'delivery')) {
				$response_delivery_note = WC_Szamlazz()->generate_invoice($order_id, 'delivery');
				$response['delivery'] = array(
					'name' => $response_delivery_note['name'],
					'link' => $response_delivery_note['link']
				);
			}

			wp_send_json_success($response);
		}

		//Generate Receipt with Ajax
		public static function generate_receipt_with_ajax() {
			check_ajax_referer( 'wc_szamlazz_generate_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			$order_id = intval($_POST['order']);
			$response = WC_Szamlazz()->generate_receipt($order_id);
			wp_send_json_success($response);
		}

		//Cancel Invoice with Ajax
		public static function void_invoice_with_ajax() {
			check_ajax_referer( 'wc_szamlazz_generate_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			$order_id = intval($_POST['order']);
			$response = WC_Szamlazz()->generate_void_invoice($order_id, 'void');
			wp_send_json_success($response);
		}

		//Cancel receipt with ajax
		public static function void_receipt_with_ajax() {
			check_ajax_referer( 'wc_szamlazz_generate_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			$order_id = intval($_POST['order']);
			$response = WC_Szamlazz()->generate_void_receipt($order_id);
			wp_send_json_success($response);
		}

		//Mark completed with Ajax
		public static function mark_completed_with_ajax() {
			check_ajax_referer( 'wc_szamlazz_generate_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			$order_id = intval($_POST['order']);
			$date = false;
			if(isset($_POST['date'])) $date = sanitize_text_field($_POST['date']);
			$response = WC_Szamlazz()->generate_invoice_complete($order_id, $date);
			wp_send_json_success($response);
		}

		//If the invoice is already generated without the plugin
		public static function toggle_invoice() {
			check_ajax_referer( 'wc_szamlazz_generate_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			$orderid = intval($_POST['order']);
			$order = wc_get_order($orderid);
			$note = sanitize_text_field($_POST['note']);
			$invoice_own = $order->get_meta('_wc_szamlazz_own');
			$response = array();

			if($invoice_own) {
				$response['state'] = 'on';
				$order->delete_meta_data('_wc_szamlazz_own');
				$response['messages'][] = esc_html__('Invoice generation turned on.', 'wc-szamlazz');
			} else {
				$response['state'] = 'off';
				$order->update_meta_data( '_wc_szamlazz_own', $note );
				$response['messages'][] = esc_html__('Invoice generation turned off.', 'wc-szamlazz');
			}

			//Save the order
			$order->save();

			wp_send_json_success($response);
		}

		//If the invoice is already generated without the plugin
		public static function reverse_receipt_with_ajax() {
			check_ajax_referer( 'wc_szamlazz_generate_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			$orderid = intval($_POST['order']);
			$order = wc_get_order($orderid);
			$order->delete_meta_data('_wc_szamlazz_type_receipt');
			$order->save();
			wp_send_json_success();
		}

		//Upload document manually
		public static function upload_document() {
			check_ajax_referer( 'wc_szamlazz_generate_invoice', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}

			//Gather data
			$order_id = intval($_POST['order']);
			$order = wc_get_order($order_id);
			$document_name = sanitize_text_field($_POST['document_upload_name']);
			$document_type = sanitize_text_field($_POST['document_upload_type']);
			$document_payment_date = sanitize_text_field($_POST['document_payment_date']);
			$pdf = $_FILES['document_upload_file'];

			//Create response object
			$response = array();
			$response['error'] = true;
			$response['messages'] = array();

			//Check if document already exists
			if(WC_Szamlazz()->is_invoice_generated($order_id, $document_type)) {
				$response['error'] = true;
				$response['messages'][] = __('Document already generated', 'wc-szamlazz');
				wp_send_json_error($response);
			}

			//Set file name & path
			self::$pdf_file_path = WC_Szamlazz()->get_pdf_file_path($document_type, $order_id);

			//Create folder if not exists
			$file = array(
				'base' 		=> self::$pdf_file_path['file_dir'],
				'file' 		=> 'index.html',
				'content' 	=> ''
			);

			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				if ( $file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'w' ) ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}

			//Try to upload file
			add_filter( 'upload_dir', array( __CLASS__, 'upload_dir_trick' ) );
			$upload = wp_handle_upload($pdf, array(
				'test_form' => false,
				'mimes'     => array('pdf' => 'application/pdf'),
				'unique_filename_callback' => array( __CLASS__, 'custom_file_name' )
			));
			remove_filter( 'upload_dir', array( __CLASS__, 'upload_dir_trick' ) );

			//Check for upload errors
			if ( isset( $upload['error'] ) ) {
				$response['error'] = true;
				$response['messages'][] = $upload['error'];
				wp_send_json_error($response);
			} else {

				//Get filename
				$invoice_pdf = $upload['file'];

				//Save order data
				$order->update_meta_data( '_wc_szamlazz_'.$document_type, $document_name );
				$order->update_meta_data( '_wc_szamlazz_'.$document_type.'_pdf', self::$pdf_file_path['name'] );
				$order->update_meta_data( '_wc_szamlazz_'.$document_type.'_manual', true );

				//Update order notes
				$order->add_order_note(sprintf(esc_html__('Számlázz.hu document called %s uploaded successfully.', 'wc-szamlazz'), $document_name));

				//Mark as paid if needed
				if($document_payment_date) {
					$order->update_meta_data( '_wc_szamlazz_completed', $document_payment_date );
					$response['completed'] = $document_payment_date;
				}

				//Save order
				$order->save();

				//Set download link
				$response['link'] = $upload['url'];

				//Set response message
				$response['error'] = false;
				$response['type'] = $document_type;
				$response['name'] = $document_name;
				$response['messages'][] = sprintf(esc_html__('Számlázz.hu document called %s uploaded successfully.', 'wc-szamlazz'), $document_name);

				//Return response
				wp_send_json_success($response);
			}

		}

		//Helper to change the upload path
		public static function upload_dir_trick($param) {
			global $woocommerce;
			$subdir =  '/wc_szamlazz';
			if ( empty( $param['subdir'] ) ) {
				$param['path']   = $param['path'] . $subdir;
				$param['url']    = $param['url'] . $subdir;
				$param['subdir'] = $subdir;
			} else {
				$param['path']   = str_replace( $param['subdir'], $subdir, $param['path'] );
				$param['url']    = str_replace( $param['subdir'], $subdir, $param['url'] );
				$param['subdir'] = str_replace( $param['subdir'], $subdir, $param['subdir'] );
			}
			return $param;
		}

		public static function custom_file_name($dir, $name, $ext){
			return self::$pdf_file_path['name'];
		}

	}

	WC_Szamlazz_Ajax::init();

endif;
