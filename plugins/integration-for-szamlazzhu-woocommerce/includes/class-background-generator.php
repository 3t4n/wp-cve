<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Szamlazz_Background_Generator', false ) ) :

	class WC_Szamlazz_Background_Generator {

		public static function init() {

			//Function to run for scheduled async jobs
			add_action('wc_szamlazz_generate_document_async', array(__CLASS__, 'generate_document_async'), 10, 3);
			add_action('wc_szamlazz_mark_as_paid_async', array(__CLASS__, 'mark_as_paid_async'), 10, 3);

			//Add loading indicator to admin bar for background generation
			add_action('admin_bar_menu', array( __CLASS__, 'background_generator_loading_indicator'), 55);
			add_action('wp_ajax_wc_szamlazz_bg_generate_status', array( __CLASS__, 'background_generator_status' ) );
			add_action('wp_ajax_wc_szamlazz_bg_generate_stop', array( __CLASS__, 'background_generator_stop' ) );

		}

		//Called by WC Queue to generate documents in the background
		public static function generate_document_async($invoice_type, $order_id, $options = array()) {
			if(!WC_Szamlazz()->is_invoice_generated($order_id, $invoice_type)) {
				WC_Szamlazz()->generate_invoice($order_id, $invoice_type, $options);
			}
		}

		//Called by WC Queue to generate documents in the background
		public static function mark_as_paid_async($order_id, $date, $options = array()) {
			if(!WC_Szamlazz()->is_invoice_paid($order_id)) {
				WC_Szamlazz()->generate_invoice_complete($order_id, $date);
			}
		}

		//Check background generation status with ajax
		public static function background_generator_status() {
			check_ajax_referer( 'wc-szamlazz-bg-generator', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			$response = array();
			if(self::is_async_generate_running()) {
				$response['finished'] = false;
			} else {
				$response['finished'] = true;
			}
			wp_send_json_success($response);
			wp_die();
		}

		//Stop background generation with ajax
		public static function background_generator_stop() {
			check_ajax_referer( 'wc-szamlazz-bg-generator', 'nonce' );
			if ( !current_user_can( 'edit_shop_orders' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this action.', 'wc-szamlazz' ) );
			}
			WC()->queue()->cancel_all('wc_szamlazz_generate_document_async');
			wp_send_json_success();
			wp_die();
		}

		//Get bg generator status
		public static function is_async_generate_running() {
			$documents_pending = WC()->queue()->search(
				array(
					'status'   => 'pending',
					'hook'    => 'wc_szamlazz_generate_document_async',
					'per_page' => 1,
				)
			);
			return (bool) count( $documents_pending );
		}

		//Add loading indicator to menu bar
		public static function background_generator_loading_indicator($wp_admin_bar) {
			if(self::is_async_generate_running()) {
				$wp_admin_bar->add_menu(
					array(
						'parent' => 'top-secondary',
						'id' => 'wc-szamlazz-bg-generate-loading',
						'title' => '<div class="loading"><em></em><strong>'.__('Generating invoices...', 'wc-szamlazz').'</strong></div><div class="finished"><em></em><strong>'.__('Invoice generation was successful', 'wc-szamlazz').'</strong></div>',
						'href' => '',
					)
				);

				$text = __('Számlázz.hu is generating invoices in the background', 'wc-szamlazz');
				$text2 = __('Invoices generated successfully. Reload the page to see the invoices.', 'wc-szamlazz');
				$text_stop = __('Stop', 'wc-szamlazz');
				$text_refresh = __('Refresh', 'wc-szamlazz');
				$wp_admin_bar->add_menu(
					array(
						'parent' => 'wc-szamlazz-bg-generate-loading',
						'id' => 'wc-szamlazz-bg-generate-loading-msg',
						'title' => '<div class="loading"><span>'.$text.'</span> <a href="#" id="wc-szamlazz-bg-generate-stop" data-nonce="'.wp_create_nonce( 'wc-szamlazz-bg-generator' ).'">'.$text_stop.'</a></div><div class="finished"><span>'.$text2.'</span> <a href="#" id="wc-szamlazz-bg-generate-refresh">'.$text_refresh.'</a></div>',
						'href' => '',
					)
				);
			}
		}

	}

	WC_Szamlazz_Background_Generator::init();

endif;
