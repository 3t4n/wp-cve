<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_Emails', false ) ) :

	class WC_Szamlazz_Emails {

		public static function init() {

			//Include invoices in emails
			if(WC_Szamlazz()->get_option('email_attachment', 'no') == 'yes') {
				if(WC_Szamlazz()->get_option('email_attachment_position', 'beginning') == 'beginning') {
					add_action('woocommerce_email_before_order_table', array( __CLASS__, 'email_attachment'), 10, 4);
					add_action('woocommerce_subscriptions_email_order_details', array( __CLASS__, 'email_attachment'), 10, 4);
				} else {
					add_action('woocommerce_email_customer_details', array( __CLASS__, 'email_attachment'), 30, 4);
				}
			}

			//Attach invoices to emails
			if(WC_Szamlazz()->get_option('email_attachment_file', 'no') == 'yes') {
				add_filter( 'woocommerce_email_attachments', array( __CLASS__, 'email_attachment_file'), 10, 3 );
				add_action( 'woocommerce_email', array( __CLASS__, 'email_attachment_fix_for_refunded_emails'));
			}

			//Forward emails if needed
			if(WC_Szamlazz()->get_option('invoice_forward')) {
				add_action( 'wc_szamlazz_document_created', array( __CLASS__, 'forward_invoices' ) );
			}

		}

		//Email attachment
		public static function email_attachment($order, $sent_to_admin, $plain_text, $email){
			$order_id = $order->get_id();
			$order = wc_get_order($order_id);
			$invoices = array();

			if(isset($email->id) && is_a( $order, 'WC_Order' )) {
				$invoice_types = array('invoice', 'proform', 'void', 'deposit');
				foreach ($invoice_types as $invoice_type) {
					$invoice_email_ids = WC_Szamlazz()->get_option('email_attachment_'.$invoice_type, array());
					if($invoice_email_ids && !empty($invoice_email_ids)) {
						if(in_array($email->id,$invoice_email_ids)) {

							//Check for receipts
							if($invoice_type == 'invoice' && WC_Szamlazz()->is_invoice_generated($order_id, 'receipt')) {
								$invoice_type = 'receipt';
							}

							if(WC_Szamlazz()->is_invoice_generated($order_id, $invoice_type)) {
								$invoices[$invoice_type] = array();
								$invoices[$invoice_type]['type'] = $invoice_type;
								$invoices[$invoice_type]['name'] = $order->get_meta('_wc_szamlazz_'.$invoice_type);
								$invoices[$invoice_type]['link'] = WC_Szamlazz()->generate_download_link($order, $invoice_type);
							}
						}
					}
				}
			}

			if(!empty($invoices)) {

				//This will load the correct site locale, not the admin language
				WC_Szamlazz()->load_plugin_textdomain();

				if($plain_text) {
					wc_get_template( 'emails/plain/email-szamlazz-section.php', array( 'order' => $order, 'wc_szamlazz_invoices' => $invoices ), '', plugin_dir_path( __FILE__ ) );
				} else {
					wc_get_template( 'emails/email-szamlazz-section.php', array( 'order' => $order, 'wc_szamlazz_invoices' => $invoices ), '', plugin_dir_path( __FILE__ ) );
				}
			}
		}

		//Email attachment file
		public static function email_attachment_file($attachments, $email_id, $order){
			if(!is_a( $order, 'WC_Order' )) return $attachments;
			$order_id = $order->get_id();
			$order = wc_get_order($order_id);
			$invoice_types = array('invoice', 'proform', 'void', 'deposit');
			foreach ($invoice_types as $invoice_type) {
				$invoice_email_ids = WC_Szamlazz()->get_option('email_attachment_'.$invoice_type, array());
				if($invoice_email_ids && !empty($invoice_email_ids)) {
					if(in_array($email_id,$invoice_email_ids)) {

						//Check for receipts
						if($invoice_type == 'invoice' && WC_Szamlazz()->is_invoice_generated($order_id, 'receipt')) {
							$invoice_type = 'receipt';
						}

						if(WC_Szamlazz()->is_invoice_generated($order_id, $invoice_type)) {
							$pdf_name = $order->get_meta('_wc_szamlazz_'.$invoice_type.'_pdf');
							if(strpos($pdf_name, '.pdf') !== false) {
								$attachments[] = WC_Szamlazz()->generate_download_link($order, $invoice_type, true);
							}
						}
					}
				}
			}
			return $attachments;
		}

		//If we plan to attach a void invoice to a refunded email, delay the email sending a bit
		public static function email_attachment_fix_for_refunded_emails( $email_class ) {
			$invoice_email_ids = WC_Szamlazz()->get_option('email_attachment_void', array());
			if($invoice_email_ids && !empty($invoice_email_ids && in_array('customer_refunded_order',$invoice_email_ids))) {
				remove_all_actions('woocommerce_order_fully_refunded_notification');
				remove_all_actions('woocommerce_order_partially_refunded_notification');
				add_action( 'woocommerce_order_status_refunded', array( $email_class->emails['WC_Email_Customer_Refunded_Order'], 'trigger' ) );
			}
		}

		//Send email on error
		public static function forward_invoices( $args ) {
			$order = wc_get_order($args['order_id']);
			$document_type = $args['document_type'];
			$document_types = WC_Szamlazz_Helpers::get_document_types();
			$document_label = $document_types[$document_type];

			$mailer = WC()->mailer();
			$content = wc_get_template_html( 'emails/invoice-copy.php', array(
				'order' => $order,
				'email_heading' => sprintf(__('The following document was successfully created: %s', 'wc-szamlazz'), $document_label),
				'plain_text' => false,
				'email' => $mailer,
				'sent_to_admin' => true,
				'document_type' => $document_label,
				'document_name' => $order->get_meta('_wc_szamlazz_'.$document_type),
				'document_link' => WC_Szamlazz()->generate_download_link($order, $document_type)
			), '', plugin_dir_path( __FILE__ ) );
			$recipient = WC_Szamlazz()->get_option('invoice_forward');
			$subject = sprintf(__("Számlázz.hu document created - %s", 'wc-szamlazz'), $document_label);
			$headers = "Content-Type: text/html\r\n";
			$attachments = array();
			$attachments[] = WC_Szamlazz()->generate_download_link($order, $document_type, true);
			$mailer->send( $recipient, $subject, $content, $headers, $attachments );
		}

	}

	WC_Szamlazz_Emails::init();

endif;
