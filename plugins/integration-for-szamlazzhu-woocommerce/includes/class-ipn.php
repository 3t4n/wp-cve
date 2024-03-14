<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_IPN', false ) ) :

	class WC_Szamlazz_IPN {

		public static function init() {
			//Process IPN request
			add_action( 'init', array( __CLASS__, 'ipn_process' ), 11 );
		}

		public static function ipn_process() {
			if (isset($_GET['wc_szamlazz_ipn_url'])) {
				if(empty($_GET['wc_szamlazz_ipn_url']) || $_GET['wc_szamlazz_ipn_url'] != get_option( '_wc_szamlazz_ipn_url')) {
					return false;
				}

				//Setup parameters
				$ipn_parameters = array();
				if(isset($_POST['szlahu_fizetesmod'])) $ipn_parameters['payment_method'] = esc_html($_POST['szlahu_fizetesmod']);
				if(isset($_POST['szlahu_szamlaszam'])) $ipn_parameters['invoice_id'] = esc_html($_POST['szlahu_szamlaszam']);
				if(isset($_POST['szlahu_rendelesszam'])) $ipn_parameters['order_number'] = esc_html($_POST['szlahu_rendelesszam']);
				$ipn_parameters = apply_filters('wc_szamlazz_ipn_request_parameters', $ipn_parameters);

				WC_Szamlazz()->log_debug_messages($ipn_parameters, 'ipn_process-parameters', true);

				//Get order
				$order = wc_get_order(esc_html($ipn_parameters['order_number']));
				if(!$order) exit;

				do_action('wc_szamlazz_before_ipn_process', $order, $ipn_parameters);

				$orderId = $order->get_id();

				//If not already, mark it paid
				$marked_paid = false;
				if(!$order->get_meta('_wc_szamlazz_completed') && !WC_Szamlazz()->is_invoice_generated($orderId, 'void')) {
					$order->add_order_note( __( 'Szamlazz.hu credit entry successfully recorded(through IPN)', 'wc-szamlazz' ) );
					$order->update_meta_data( '_wc_szamlazz_completed', date_i18n('Y-m-d') );
					$order->set_date_paid( time() );
					$marked_paid = true;
				}

				//Check for duplicate depostit invoice
				$is_deposit = false;
				if(WC_Szamlazz()->is_invoice_generated($orderId, 'deposit') && isset($ipn_parameters['invoice_id'])) {
					$deposit_id = preg_replace("/\s+/", "", $order->get_meta('_wc_szamlazz_deposit'));
					if($ipn_parameters['invoice_id'] == $deposit_id) $is_deposit = true;
				}

				//Check if we don't have an invoice, but IPN sent one. If so, download the PDF invoice and store it in WooCommerce
				if(!WC_Szamlazz()->is_invoice_generated($orderId) && !empty($ipn_parameters['invoice_id']) && $ipn_parameters['invoice_id'][0] != 'D' && isset($ipn_parameters['payment_method']) && !WC_Szamlazz()->is_invoice_generated($orderId, 'void') && !$is_deposit) {
					$szamlaszam = $ipn_parameters['invoice_id'];

					//Build Xml
					$szamla = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><xmlszamlapdf xmlns="http://www.szamlazz.hu/xmlszamlapdf" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.szamlazz.hu/xmlszamlapdf xmlszamlapdf.xsd"></xmlszamlapdf>');

					//Account & Invoice settings
					$szamla->addChild('szamlaagentkulcs', WC_Szamlazz()->get_option('agent_key', ''));
					$szamla->addChild('szamlaszam', $szamlaszam);
					$szamla->addChild('valaszVerzio', '1');

					//Generate XML
					$xml = $szamla->asXML();
					$xml_response = WC_Szamlazz()->xml_generator->generate($xml, $orderId, 'action-szamla_agent_pdf');
					if(!$xml_response['error']) {

						//Set document type
						$ipn_document_type = apply_filters('wc_szamlazz_ipn_document_type', 'invoice', $ipn_parameters, $order);

						//Download & Store PDF - generate a random file name so it will be downloadable later only by you
						$pdf_file_name = WC_Szamlazz()->xml_generator->save_pdf_file($ipn_document_type, $orderId, false, $szamlaszam);

						//Store as a custom field
						$order->update_meta_data( '_wc_szamlazz_'.$ipn_document_type, $szamlaszam );
						$order->update_meta_data( '_wc_szamlazz_'.$ipn_document_type.'_pdf', $pdf_file_name );

						//Update order notes
						if($ipn_document_type == 'deposit') {
							$order->add_order_note( sprintf(__( 'Szamlazz.hu deposit invoice successfully generated through IPN. Invoice number: %s', 'wc-szamlazz' ), $szamlaszam) );
						} else {
							$order->add_order_note( sprintf(__( 'Szamlazz.hu invoice successfully generated through IPN. Invoice number: %s', 'wc-szamlazz' ), $szamlaszam) );
						}

						$need_delivery_note = (WC_Szamlazz()->get_option('delivery_note', 'no') == 'yes');
						$need_delivery_note = apply_filters('wc_szamlazz_need_delivery_note', $need_delivery_note, $order);
						if($need_delivery_note && !WC_Szamlazz()->is_invoice_generated($orderId, 'delivery')) {
							$return_info = WC_Szamlazz()->generate_invoice($orderId, 'delivery');
						}

					}
				}

				//Update order status to complete if needed
				$target_status = WC_Szamlazz()->get_option('ipn_close_order');

				//Old value was just a simple yes or no for the completed status
				if($target_status == 'yes') $target_status = 'completed';

				//Remove wc prefix
				//$target_status = str_replace( 'wc-', '', $target_status);

				//Change status if needed
				if($target_status && $target_status != 'no' && isset($ipn_parameters['payment_method']) && !WC_Szamlazz()->is_invoice_generated($orderId, 'void') && (!WC_Szamlazz()->is_invoice_generated($orderId, 'invoice') || $marked_paid)) {
					if(apply_filters('wc_szamlazz_ipn_should_change_order_status', ($order->get_status() != 'completed'), $order, $ipn_parameters, $target_status)) {
						$order->update_status($target_status, __( 'Order status changed with Számlázz.hu IPN.', 'wc-szamlazz' ));
					}
				}

				//Save order
				$order->save();

				do_action('wc_szamlazz_after_ipn_process', $order, $ipn_parameters);

				exit();
			}
		}
	}

	WC_Szamlazz_IPN::init();

endif;
