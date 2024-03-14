<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Load PDF API
use iio\libmergepdf\Merger;
use iio\libmergepdf\Driver\TcpdiDriver;

if ( ! class_exists( 'WC_Szamlazz_Bulk_Actions', false ) ) :

	class WC_Szamlazz_Bulk_Actions {

		public static function init() {
			add_filter( 'bulk_actions-edit-shop_order', array( __CLASS__, 'add_bulk_options'), 20, 1);
			add_filter( 'handle_bulk_actions-edit-shop_order', array( __CLASS__, 'handle_bulk_actions'), 10, 3 );
			add_filter( 'bulk_actions-woocommerce_page_wc-orders', array( __CLASS__, 'add_bulk_options'), 20, 1 );
			add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', array( __CLASS__, 'handle_bulk_actions'), 10, 3 );
			add_action( 'admin_notices', array( __CLASS__, 'bulk_actions_results') );
			add_action( 'admin_footer', array( __CLASS__, 'generator_modal' ) );
			add_action( 'wp_ajax_wc_szamlazz_bulk_generator', array( __CLASS__, 'bulk_generator_ajax' ) );

			add_filter( 'woocommerce_admin_order_preview_get_order_details', array( __CLASS__, 'add_invoices_in_preview_modal'), 20, 2 );
			add_action( 'woocommerce_admin_order_preview_start', array( __CLASS__, 'show_invoices_in_preview_modal') );
		}

		public static function get_bulk_actions() {
			$actions = array();
			$defaults = WC_Szamlazz_Helpers::get_default_bulk_actions();
			$enabled_actions = WC_Szamlazz()->get_option('bulk_actions', $defaults);
			$action_types = array(
				'generate_invoice' => _x( 'Create invoices', 'bulk action', 'wc-szamlazz' ),
				'print_invoice' => _x( 'Print invoices', 'bulk action', 'wc-szamlazz' ),
				'download_invoice' => _x( 'Download invoices', 'bulk action', 'wc-szamlazz' ),
				'generate_void' => _x( 'Create reverse invoices', 'bulk action', 'wc-szamlazz' ),
				'generator' => _x( 'Create documents', 'bulk action', 'wc-szamlazz' ),
				'print_delivery' => _x( 'Print delivery notes', 'bulk action', 'wc-szamlazz' ),
				'download_delivery' => _x( 'Download delivery notes', 'bulk action', 'wc-szamlazz' ),
			);

			foreach ($enabled_actions as $key) {
				if(isset($action_types[$key])) {
					$actions['wc_szamlazz_bulk_'.$key] = $action_types[$key];
				}
			}

			//Hide generator if pro not enabled
			if(!WC_Szamlazz_Pro::is_pro_enabled()) {
				unset($action_types['generator']);
			}

			return apply_filters('wc_szamlazz_bulk_actions', $actions);
		}

		public static function add_bulk_options( $actions ) {
			return $actions + self::get_bulk_actions();
		}

		public static function handle_bulk_actions( $redirect_to, $action, $post_ids ) {

			//Check if we are processing a szamlazz bulk request
			if( strpos($action, 'wc_szamlazz_bulk') !== false ) {
				$wc_szamlazz_query_args = array_keys(self::get_bulk_actions());
				$action_params = explode('_', $action);
				$document_type = $action_params[4];
				$action = $action_params[3];

				//If we are downloading or printing
				if ( in_array($action, array('print', 'download'))) {

					//Remove existing params from url
					$redirect_to = remove_query_arg(array('wc_szamlazz_bulk_action', 'wc_szamlazz_results_bulk_count', 'wc_szamlazz_results_bulk_pdf'), $redirect_to);

					//Create bulk pdf file
					$bulk_pdf_file = WC_Szamlazz()->get_pdf_file_path('bulk', 0);

					if(WC_Szamlazz()->get_option('bulk_download_zip', 'no') == 'yes' && $action == 'download') {

						//Create an object from the ZipArchive class.
						$zipArchive = new ZipArchive();

						//The full path to where we want to save the zip file.
						$bulk_pdf_file['path'] = str_replace('.pdf', '.zip', $bulk_pdf_file['path']);
						$bulk_pdf_file['name'] = str_replace('.pdf', '.zip', $bulk_pdf_file['name']);

						//Call the open function.
						$status = $zipArchive->open($bulk_pdf_file['path'], ZipArchive::CREATE | ZipArchive::OVERWRITE);

						//An array of files that we want to add to our zip archive.
						foreach ( $post_ids as $order_id ) {
							$order = wc_get_order($order_id);
							$pdf_file = WC_Szamlazz()->generate_download_link($order, $document_type, true);

							//Check for receipt if no invoice found
							if(!$pdf_file && $document_type == 'invoice') {
								$pdf_file = WC_Szamlazz()->generate_download_link($order, 'receipt', true);
							}

							if($pdf_file && file_exists($pdf_file)) {
								$new_filename = substr($pdf_file, strrpos($pdf_file,'/') + 1);
								$zipArchive->addFile($pdf_file, $new_filename);
								$processed[] = $order_id;
							}
						}

						//Bail if theres no $documents
						if(!$processed) {
							return $redirect_to;
						}

						//Finally, close the active archive.
						$zipArchive->close();

					} else {

						//Init PDF merger
						require_once plugin_dir_path(__FILE__) . '../vendor/autoload.php';
						$merger = new Merger(new TcpdiDriver);
						$processed = array();

						//Process selected posts
						foreach ( $post_ids as $order_id ) {
							$order = wc_get_order($order_id);
							$pdf_file = WC_Szamlazz()->generate_download_link($order, $document_type, true);

							//Check for receipt if no invoice found
							if(!$pdf_file && $document_type == 'invoice') {
								$pdf_file = WC_Szamlazz()->generate_download_link($order, 'receipt', true);
							}

							if($pdf_file && file_exists($pdf_file)) {
								$merger->addFile($pdf_file);
								$processed[] = $order_id;
							}
						}

						//Bail if theres no $documents
						if(!$processed) {
							return $redirect_to;
						}

						//Create bulk pdf file
						$merged_pdf_file = $merger->merge();

						//Store PDF
						global $wp_filesystem;
						if ( !$wp_filesystem ) WP_Filesystem();
						$wp_filesystem->put_contents( $bulk_pdf_file['path'], $merged_pdf_file );

					}

					//Set redirect url that will show the download message notice
					$redirect_to = add_query_arg( array('wc_szamlazz_bulk_action' => $action.'-'.$document_type, 'wc_szamlazz_results_bulk_count' => count( $processed ), 'wc_szamlazz_results_bulk_pdf' => urlencode($bulk_pdf_file['name'])), $redirect_to );
					return $redirect_to;
				} else if ($action == 'generate') {

					//Remove existing params from url
					$redirect_to = remove_query_arg($wc_szamlazz_query_args, $redirect_to);

					//Processed orders
					$processed = array();

					//Check if we need to defer
					$defer_limit = apply_filters('wc_szamlazz_bulk_generate_defer_limit', 2);

					if(count($post_ids) > $defer_limit) {
						foreach ( $post_ids as $order_id ) {
							WC()->queue()->add( 'wc_szamlazz_generate_document_async', array( 'invoice_type' => $document_type, 'order_id' => $order_id ), 'wc-szamlazz' );
							$processed[] = $order_id;
						}
					} else {
						foreach ( $post_ids as $order_id ) {
							if(!WC_Szamlazz()->is_invoice_generated($order_id, $document_type)) {
								if($document_type == 'void') {
									WC_Szamlazz()->generate_void_invoice($order_id);
								} else {
									WC_Szamlazz()->generate_invoice($order_id, $document_type);
								}
								$processed[] = $order_id;
							}
						}
					}

					//Set redirect url that will show the download message notice
					$redirect_to = add_query_arg( array('wc_szamlazz_bulk_action' => $action.'-'.$document_type, 'wc_szamlazz_results_bulk_count' => implode('|', $processed)), $redirect_to );
					return $redirect_to;

				} else if($action == 'generator') {
					return $redirect_to;
				} else {
					return $redirect_to;
				}

			} else {
				return $redirect_to;
			}

		}

		public static function bulk_actions_results() {
			if(isset($_REQUEST['wc_szamlazz_bulk_action'])) {

				//If its a print or download request
				$action = sanitize_text_field($_REQUEST['wc_szamlazz_bulk_action']);
				$document_type = explode('-', $action)[1];
				$action = explode('-', $action)[0];

				if ( in_array($action, array('print', 'download')) ) {
					$print_count = intval( $_REQUEST['wc_szamlazz_results_bulk_count'] );

					$paths = WC_Szamlazz()->get_pdf_file_path('bulk', 0);
					$pdf_file_name = esc_attr( $_REQUEST['wc_szamlazz_results_bulk_pdf'] );
					$pdf_file_url = $paths['baseurl'].$pdf_file_name;

					include( dirname( __FILE__ ) . '/views/html-notice-bulk.php' );
				}

				if ( $action == 'generate') {
					$documents = explode('|', $_REQUEST['wc_szamlazz_results_bulk_count']);
					include( dirname( __FILE__ ) . '/views/html-notice-bulk.php' );
				}

			}
		}

		public static function generator_modal() {
			global $typenow;
			if ( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ), true ) ) {
				include( dirname( __FILE__ ) . '/views/html-modal-generator.php' );
			}
		}

		public static function bulk_generator_ajax() {
			check_ajax_referer( 'wc_szamlazz_bulk_generator', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}

			//Create response
			$response = array();
			$response['error'] = false;
			$response['messages'] = array();

			//Get the selected order ids
			$orders = sanitize_text_field($_POST['orders']);
			$document_type = sanitize_text_field($_POST['options']['document_type']);
			$options = array();
			$option_names = array('account', 'lang', 'note', 'deadline', 'completed', 'doc_type');
			foreach ($option_names as $option_name) {
				$options[$option_name] = sanitize_text_field($_POST['options'][$option_name]);
			}

			//Convert submitted order ids to int array
			$order_ids = array_map('intval', explode(',', $orders));

			//Create an array of order numbers
			$order_numbers = array();
			foreach ($order_ids as $order_id) {
				$order = wc_get_order($order_id);
				$order_numbers[] = $order->get_order_number();
			}

			//Get document label
			$document_types = WC_Szamlazz_Helpers::get_document_types();
			$document_label = $document_types[$document_type];

			//Check if we need to defer
			$defer_limit = apply_filters('wc_szamlazz_bulk_generate_defer_limit', 2);
			if(count($order_ids) > $defer_limit) {
				foreach ( $order_ids as $order_id ) {
					WC()->queue()->add( 'wc_szamlazz_generate_document_async', array( 'invoice_type' => $document_type, 'order_id' => $order_id, 'options' => $options ), 'wc-szamlazz' );
					$processed[] = $order_id;
				}
				$response['messages'][] = sprintf( esc_html__( '%1$s order(s) has been selected to create the following documents: %2$s. Documents are being created. Reload this page and you will see a status indicator top right, next to your username.', 'wc-szamlazz' ), count($processed), $document_label);
			} else {
				$response['generated'] = array();
				foreach ( $order_ids as $order_id ) {
					$order = wc_get_order($order_id);
					if(!WC_Szamlazz()->is_invoice_generated($order_id, $document_type)) {
						$geneartor_response = WC_Szamlazz()->generate_invoice($order_id, $document_type, $options);
						$geneartor_response['order_number'] = $order->get_order_number();
						$response['generated'][] = $geneartor_response;
						$processed[] = $order_id;
					} else {
						$msg = sprintf(__('%1$s already exists for this order', 'wc-szamlazz'), $document_label);
						$response['generated'][] = array(
							'order_number' => $order->get_order_number(),
							'error' => true,
							'messages' => array($msg)
						);
					}
				}
				$response['messages'][] = sprintf( esc_html__( '%1$s order(s) has been selected to create the following documents: %2$s.', 'wc-szamlazz' ), count($processed), $document_label);
			}

			wp_send_json_success($response);
		}

		public static function add_invoices_in_preview_modal( $fields, $order ) {
			$invoice_types = WC_Szamlazz_Helpers::get_document_types();
			$invoices = array();

			foreach ($invoice_types as $invoice_type => $invoice_label) {
				if(WC_Szamlazz()->is_invoice_generated($order->get_id(), $invoice_type) && !$order->get_meta('_wc_szamlazz_own')) {
					$invoices[] = [
						'label' => $invoice_label,
						'name' => $order->get_meta('_wc_szamlazz_'.$invoice_type),
						'link' => WC_Szamlazz()->generate_download_link($order, $invoice_type)
					];
				}
			}

			if($invoices) {
				$fields['wc_szamlazz'] = $invoices;
			}

			return $fields;
		}

		public static function show_invoices_in_preview_modal() {
			?>
			<# if ( data.wc_szamlazz ) { #>
			<div class="wc-order-preview-addresses">
				<div class="wc-order-preview-address">
					<h2><?php esc_html_e( 'Számlázz.hu', 'wc-szamlazz' ); ?></h2>
					<# _.each( data.wc_szamlazz, function(res, index) { #>
						<strong>{{res.label}}</strong>
						<a href="{{ res.link }}" target="_blank">{{ res.name }}</a>
					<# }) #>
				</div>
			</div>
			<# } #>
			<?php
		}
	}

	WC_Szamlazz_Bulk_Actions::init();

endif;
