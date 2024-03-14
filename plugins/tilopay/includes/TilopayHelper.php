<?php

/**
 * Helper code call to organize the class code
 *
 * @package  Tilopay
 */

namespace Tilopay;

use Automattic\WooCommerce\Utilities\OrderUtil;

class TilopayHelper {
	/**
	 * Add at submenu Tilopay settings to woocommerce
	 */
	public function tpay_add_menu() {
		$this->page_id = add_submenu_page(
			'woocommerce',
			__( 'Settings', 'tilopay' ) . ' Tilopay',
			__( 'Settings', 'tilopay' ) . ' Tilopay',
			'manage_woocommerce',
			'wc-settings&tab=checkout&section=tilopay',
			array( $this, '' )
		 );
	}

	/**
	 * Helper function to hook, check if Tilopay is enable or deseable
	 */
	public function tilopay_gateway_payment_status( $available_gateways ) {
		if (isset($available_gateways['tilopay']) && is_object($available_gateways['tilopay'])) {
			if ( 'no' == $available_gateways['tilopay']->enabled ) {
				unset( $available_gateways['tilopay'] );
			}
		}
		return $available_gateways;
	}

	//Helper function to hook
	public function tpay_plugin_cancel_tilopay() {
		$log = new \WC_Logger();
		$log->add( 'test', 'ENTRE' );
		if ( !wp_next_scheduled( 'tpay_my_cron_tilopay' ) ) {
			$log->add( 'test', 'ENTRE' );
			wp_schedule_event( current_time( 'timestamp' ), 'every_three_minutes', 'tpay_my_cron_tilopay' );
		}
	}

	//Helper function to hook
	public function tpay_my_process_tilopay() {
		$orders = wc_get_orders( array(
			'status' => 'pending',
			'return' => 'ids',
			'limit' => -1,
		 ) );
		foreach ( $orders as $data ) {
			$logger = new \WC_Logger();
			if ( $this->haveActiveHPOS ) {
				// HPOS
				$meta_field_data = $data->get_meta( 'tpay_cancel', true);
			} else {
				$meta_field_data = get_post_meta( $data, 'tpay_cancel' )[0] ? get_post_meta( $data, 'tpay_cancel' )[0] : '';
			}
			if ( 'modal' == $meta_field_data ) {
				$order = wc_get_order( $data );
				$order->update_status( 'cancelled' );
				$order->add_order_note( __( 'Order canceled when closing the payment method', 'tilopay' ) );
			}
		}
	}

	//Helper function to hook
	public function tpay_add_cron_recurrence_interval( $schedules ) {

		$schedules['every_three_minutes'] = array(
			'interval' => 180,
			'display' => __( 'Every 3 minutes', 'tilopay' )
		 );

		return $schedules;
	}

	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=tilopay">' . __( 'Settings', 'tilopay' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}
	/**
	 * ************************************************
	 * ************************************************
	 * Woocommerce class init function hook helper ****
	 * ************************************************
	 * ************************************************
	 */

	// Register the gateway in WC
	public function tpay_woocommerce_register_tilopay_gateway( $methods ) {
		if ( class_exists( 'Tilopay\\WCTilopay' ) ) {
			$methods[] = new WCTilopay();
		}

		return $methods;
	}

	// Define the woocommerce_credit_card_form_fields callback
	public function tpay_filter_woocommerce_credit_card_form_fields() {
		if ( sanitize_text_field( isset( $_REQUEST['response_data'] ) ) ) {
			if ( isset( $_REQUEST['response_data'] ) && 'found' == $_REQUEST['response_data'] && sanitize_text_field( isset( $_REQUEST['order_id'] ) ) ) {
				$order_id = sanitize_text_field( $_REQUEST['order_id'] );

				if ( $this->haveActiveHPOS ) {
					// HPOS
					$order = wc_get_order( $order_id );
					$tpay_url_payment_form = $order->get_meta( 'tilopay_html_form', true);
				} else {
					$tpay_url_payment_form = get_post_meta( $order_id, 'tilopay_html_form' )[0];
				}
				//check if have html
				if ( isset( $tpay_url_payment_form ) && '' != $tpay_url_payment_form ) {
					wp_redirect( esc_url( $tpay_url_payment_form) );
				}
			}
		}
	}

	//Change order status
	public function tpay_order_status_changed( $order_id ) {
		/**
		 * Function tpay_process_payment_modification( $order_id, $type, $total )
		 *$type:
		 * 1 = Capture ( captura )
		 * 2 = Refund ( reembolso )
		 * 3 = Reversal ( reverso )
		 */
		if ( !$order_id ) {
			return;
		}

		$order = wc_get_order( $order_id );
		if ( !$order ) {
			return;
		}

		$getResponse = false;
		$type = '';

		if ( $this->haveActiveHPOS ) {
			// HPOS
			$capture = $order->get_meta( 'tpay_capture', true);
		} else {
			$capture = key_exists( 0, get_post_meta( $order_id, 'tpay_capture' ) ) ? get_post_meta( $order_id, 'tpay_capture' )[0] : null;
		}

		if ( 'processing' == $order->get_status() && 'no' == $capture ) {
			//Process capture.
			$wc_tilopay = new WCTilopay();
			//1 = Capture ( captura )
			$type = '1';
			$getResponse = $wc_tilopay->tpay_process_payment_modification( $order_id, '1', $order->get_total() );
		}
		/*
			if ( $order->get_status() == 'refunded' && $capture == "yes" ) {
				$wc_tilopay = new WCTilopay();
				$wc_tilopay->tpay_process_payment_modification( $order_id, "2" );
			}
			if ( $order->get_status() == 'refunded' && $capture == "no" ) {
				$wc_tilopay = new WCTilopay();
				$wc_tilopay->tpay_process_payment_modification( $order_id, "3" );
			}
			*/
		if ( !empty( $type ) ) {
			//Set not from API Response
			if ( false !== $getResponse && isset( $getResponse->ReasonCode ) && in_array( $getResponse->ReasonCode, ['1', '1101'] ) ) {
				$request_tpay_order_id = ( isset( $getResponse->transactionId ) ) ? $getResponse->transactionId : '';
				$textOrderTilopay = ( 1 == $type ) ? __( 'Capture', 'tilopay' ) : '';
				//Set last actions done = capture
				if (OrderUtil::custom_orders_table_usage_is_enabled()){
					// HPOS
					$order->update_meta_data( 'tilopay_is_captured', 1 );
					$order->save();
				} else {
					update_post_meta( $order_id, 'tilopay_is_captured', 1 );
				}
				// translators: %s action type.
				$order->add_order_note( sprintf( __( '%s Tilopay id:', 'tilopay' ), $textOrderTilopay ) . $request_tpay_order_id );
				$order->add_order_note( __( 'Result:', 'tilopay' ) . $getResponse->ReasonCodeDescription );
				$order->update_status( 'processing' );
				return true;
			} else {
				//Rejected
				$errorResponse = ( false !== $getResponse && isset( $getResponse->ReasonCodeDescription ) )
					? $getResponse->ReasonCodeDescription
					: __( 'Connection error with TILOPAY, contact sac@tilopay.com.', 'tilopay' );

				// translators: %s the order number.
				$errorNote = sprintf( __( 'Error, the refund of the order no.%s could not be made.', 'tilopay' ), $order_id );
				$errorNote = ( !empty( $errorResponse ) ) ? $errorNote . ' Error Tilopay:' . $errorResponse : $errorNote;
				$order->add_order_note( $errorNote );
			}
		}
	}

	/*
			// define the woocommerce_order_partially_refunded callback
	function tpay_woocommerce_order_partially_refunded($order_get_id, $refund_get_id) {
		$order_id=$order_get_id;
		if ( $order_id == "" ) {
			return;
		}
		if ( $order->get_status() == 'processing' ) {
			$wc_tilopay = new WCTilopay();
			$wc_tilopay->tpay_process_payment_modification( $order_id, "2" );
		}
		if ( $order->get_status() == 'pending' ) {
			$wc_tilopay = new WCTilopay();
			$wc_tilopay->tpay_process_payment_modification( $order_id, "3" );
		}
	};
			// define the woocommerce_order_fully_refunded callback
	function tpay_woocommerce_order_fully_refunded(  $order_get_id, $refund_get_id  ) {
		// make action magic happen here...
	};
		 */

	public function tpay_woocommerce_order_refunded( $order_get_id, $refund_id ) {

		/**
		 * Function tpay_process_payment_modification( $order_id, $type, $total )
		 * $type:
		 * 1 = Capture ( captura )
		 * 2 = Refund ( reembolso )
		 * 3 = Reversal ( reverso )
		 */
		$order_id = $order_get_id;
		if ( '' == $order_id ) {
			return;
		}

		// Get original order
		$order = wc_get_order( $order_id );
		if ( !$order ) {
			return;
		}

		//Get refund order
		$refund = wc_get_order( $refund_id );
		if ( !$refund ) {
			return;
		}

		//Get last action done
		if ( $this->haveActiveHPOS ) {
			// HPOS
			$is_captured = $order->get_meta( 'tilopay_is_captured', true);
			$capture = $order->get_meta( 'tpay_capture', true);
		} else {
			$is_captured = get_post_meta( $order_id, 'tilopay_is_captured', true );
			//Get if capture original order
			$capture = key_exists( 0, get_post_meta( $order_id, 'tpay_capture' ) ) ? get_post_meta( $order_id, 'tpay_capture' )[0] : '';
		}

		$getResponse = false;
		$type = '';

		//new instance
		$wc_tilopay = new WCTilopay();

		//Check order status
		if ( 'processing' == $order->get_status() ) {
			//2 = Refund ( reembolso )
			$getResponse = $wc_tilopay->tpay_process_payment_modification( $order_id, '2', $refund->get_amount() );
			$type = 2;
		}
		if ( 'pending' == $order->get_status() ) {
			//3 = Reversal ( reverso )
			$getResponse = $wc_tilopay->tpay_process_payment_modification( $order_id, '3', $refund->get_amount() );
			$type = 3;
		}
		if ( 'refunded' == $order->get_status() && 'yes' == $capture ) {
			//2 = Refund ( reembolso )
			$getResponse = $wc_tilopay->tpay_process_payment_modification( $order_id, '2', $refund->get_amount() );
			$type = 2;
		}
		if ( 'refunded' == $order->get_status() && 'no' == $capture ) {
			//3 = Reversal ( reverso )
			$getResponse = $wc_tilopay->tpay_process_payment_modification( $order_id, '3', $refund->get_amount() );
			$type = 3;
		}

		if ( !empty( $type ) ) {
			$tpay_order_id = ( isset( $getResponse->transactionId ) ) ? $getResponse->transactionId : '';
			//check if capture to set refund if not ser reverse
			$textOrderTilopay = ( 1 == $is_captured ) ? __( 'Refund', 'tilopay' ) : __( 'Reverse', 'tilopay' );
			//Set not from API Response
			if ( false !== $getResponse && isset( $getResponse->ReasonCode ) && in_array( $getResponse->ReasonCode, ['1', '1101'] ) ) {
				// translators: %s action type.
				$order->add_order_note( sprintf( __( '%s Tilopay id:', 'tilopay' ), $textOrderTilopay ) . $tpay_order_id );
				$order->add_order_note( __( 'Code:', 'tilopay' ) . $getResponse->ReasonCode );
				$order->add_order_note( __( 'Result:', 'tilopay' ) . $getResponse->ReasonCodeDescription );
				$order->update_status( 'refunded' );
				return true;
			} else {
				//Rejected
				$haveMessage = isset( $getResponse->message ) ? $getResponse->message : '';
				$errorResponse = ( false !== $getResponse && isset( $getResponse->ReasonCodeDescription ) )
					? $getResponse->ReasonCodeDescription
					: $haveMessage;

				$errorResponse = ( empty( $errorResponse ) )
					? __( 'Connection error with TILOPAY, contact sac@tilopay.com.', 'tilopay' )
					: $errorResponse;

				// translators: %s the order number.
				$errorNote = sprintf( __( 'Error, the refund of the order no.%s could not be made.', 'tilopay' ), $order_id );
				$errorNote = ( !empty( $errorResponse ) ) ? $errorNote . ' Error Tilopay:' . $errorResponse : $errorNote;

				$order->add_order_note( $errorNote );
			}
		}
	}

	/**
	 * Admin script to upload logo, only load at WC wc-settings page, js and css
	 */
	public function enqueuing_admin_config_payment_scripts() {
		//Add here the script to load in whole administration or the specific page conditions
		//Only for Woocommerce Settings
		if ( isset( $_GET['page'] ) && 'wc-settings' == $_GET['page'] ) {
			wp_enqueue_media();
			$config_payment_ver = gmdate( 'ymd-Gis' );
			wp_register_script( 'tilopay-config-payment', WP_PLUGIN_URL . '/tilopay/assets/js/tilopay-config-payment.js', array( 'jquery' ), $config_payment_ver, true );
			wp_enqueue_script( 'tilopay-config-payment' );
			//multiselect
			$multiselect_dropdown_ver = gmdate( 'ymd-Gis' );
			wp_register_script( 'tilopay-config-payment-multiselect', WP_PLUGIN_URL . '/tilopay/assets/js/multiselect-dropdown.js', array( 'jquery' ), $multiselect_dropdown_ver, true );
			wp_enqueue_script( 'tilopay-config-payment-multiselect' );
			wp_localize_script( 'tilopay-config-payment-multiselect', 'traslateVar', array(
				'selectAll' => __( 'Select all', 'tilopay' ),
				'search' => __( 'Search', 'tilopay' ),
				'select' => __( 'Select', 'tilopay' ),
				'selected' => __( 'Selected', 'tilopay' ),
				'remove' => __( 'Remove', 'tilopay' )
			 ) );
			//Pass parameter to the script js, here we are passing plugins_url
			wp_localize_script( 'tilopay-config-payment', 'variableSet', array(
				'pluginsUrl' => plugins_url(),
				'removeIconButtonText' => __( 'Remove icon', 'tilopay' ),
				'useTiloPayIcon' => __( 'Use TILOPAY icon', 'tilopay' ),
				'swalTitel' => __( 'Are you sure to switch to authorization and partial capture mode?', 'tilopay' ),
				'swalBody' => __( 'Once you receiving an order in your store with the authorization and partial capture mode, Tilopay only authorizes the transactions and the order remains in the Pending Payment status. To capture the amount and complete the transaction, an administrator can review and change the order if necessary, then change the order status to Processing. In case of not making the change of status to Processing, the transaction will be automatically canceled after 7 days. Are you sure you want to continue?', 'tilopay' ),
				'swalBtnCancel' => __( 'No, cancel', 'tilopay' ),
				'swalBtnOk' => __( 'Yes, i understand', 'tilopay' ),
				'swalNoChange' => __( 'Excellent, the capture mode was not changed.', 'tilopay' ),
				'swalChange' => __( 'Excellent, the capture mode was changed to authorization and partial capture.', 'tilopay' ),
			 ) );

			//CSS
			$my_admincss_ver = gmdate( 'ymd-Gis', filemtime( TPAY_PLUGIN_DIR . '/assets/css/tilopay-config-payment-admin.css' ) );
			wp_register_style( 'tilopay-config-payment-admin', WP_PLUGIN_URL . '/tilopay/assets/css/tilopay-config-payment-admin.css', false, $my_admincss_ver );
			wp_enqueue_style( 'tilopay-config-payment-admin' );

			$sweetalert_ver = gmdate( 'ymd-Gis' );
			wp_register_script( 'sweetalert-TYP', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js', null, $sweetalert_ver, true );
			wp_enqueue_script( 'sweetalert-TYP' );
		} //.End only for Woocommerce Settings
	}

	/**
	 * Load tilopay front scripts, js and css
	 */
	public function load_tilopay_front_scripts( $hook ) {
		// we need JavaScript to process a token only on cart/checkout pages, right?
		if ( is_checkout() || isset( $_GET['pay_for_order'] ) ) {
			// create my own version codes
			//$my_modaljs_ver = gmdate( 'ymd-Gis', filemtime( TPAY_PLUGIN_DIR . '/assets/js/jquery.modal.min.js' ) );
			//$my_css_ver = gmdate( 'ymd-Gis', filemtime( TPAY_PLUGIN_DIR . '/assets/css/jquery.modal.min.css' ) );
			$my_admincss_ver = gmdate( 'ymd-Gis', filemtime( TPAY_PLUGIN_DIR . '/assets/css/admin.css' ) );

			//wp_enqueue_script( 'tilopay-modaljs', WP_PLUGIN_URL . '/tilopay/assets/js/jquery.modal.min.js', array(), $my_modaljs_ver );
			//wp_register_style( 'tilopay-modal-frontcss', WP_PLUGIN_URL . '/tilopay/assets/css/jquery.modal.min.css', false, $my_css_ver );
			wp_enqueue_style( 'tilopay-modal-frontcss' );
			wp_register_style( 'tilopay-frontcss', WP_PLUGIN_URL . '/tilopay/assets/css/admin.css', false, $my_admincss_ver );
			wp_enqueue_style( 'tilopay-frontcss' );
		} else {
			return;
		}
	}

	//load_plugin_textdomain
	public function tilopay_on_init() {

		$path = dirname( TPAY_PLUGIN_BASENAME ) . '/languages/';
		load_plugin_textdomain( 'tilopay', false, $path );
	}

	//load lang
	public function load_tilopay_textdomain( $mofile, $domain ) {
		if ( 'tilopay' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
			$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
			$mofile = WP_PLUGIN_DIR . '/' . TPAY_PLUGIN_NAME . '/languages/' . $domain . '-' . $locale . '.mo';
		}
		return $mofile;
	}

	//Route to check form is valide
	public function register_tilopay_validation_form_route() {
		$isNative = ( new WCTilopay() )->isNativePayment();
		if ( $isNative ) {
			register_rest_route( 'tilopay/v1', '/tpay_validate_checkout_form_errors',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'tpay_validate_form_request' ),
				'permission_callback' => '__return_true'
			 ) );
		}
	}

	//Route callback handler
	public function tpay_validate_form_request() {
		// Call validate_fields to validate errors
		$getResponse = ( new WCTilopay() )->validate_fields();

		// Response JSON
		return rest_ensure_response( $getResponse );
	}
}
