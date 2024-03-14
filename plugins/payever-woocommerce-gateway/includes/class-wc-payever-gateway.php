<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Gateway' ) ) {
	return;
}

use Payever\Sdk\Core\Http\RequestEntity;
use Payever\Sdk\Core\Enum\ChannelSet;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentRequest;
use Payever\Sdk\Payments\Http\RequestEntity\CreatePaymentV2Request;
use Payever\Sdk\Payments\Http\RequestEntity\PaymentItemEntity;
use Payever\Sdk\Payments\Http\RequestEntity\SubmitPaymentRequest;
use Payever\Sdk\Payments\Http\MessageEntity\CustomerAddressEntity;
use Payever\Sdk\Payments\Http\MessageEntity\ChannelEntity;
use Payever\Sdk\Payments\Http\MessageEntity\PaymentDataEntity;
use Payever\Sdk\Payments\Action\ActionDecider;
use Payever\Sdk\Payments\Enum\Status;

/**
 * @SuppressWarnings(PHPMD.ElseExpression)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class WC_Payever_Gateway extends WC_Payment_Gateway {
	use WC_Payever_WP_Wrapper_Trait;

	const CURRENT_PAYEVER_PAYMENT_ID = 'current_payever_payment_id';
	const PAYEVER_PAYMENT_ID = 'payment_id';
	const LOCK_WAIT_SECONDS = 30;

	/**
	 * Total amount of order.
	 *
	 * @var double $amount
	 */
	public $amount;

	/**
	 * @var float
	 */
	public $min_amount = 0;

	/**
	 * @var float
	 */
	public $max_amount = 0;

	/**
	 * Plugin settings.
	 *
	 * @var array $plugin_settings
	 */
	public $plugin_settings = array();

	/**
	 * Supported features
	 *
	 * @var array
	 */
	public $supports = array( 'products', 'refunds' );

	/**
	 * @var bool
	 */
	private $is_redirect_method = false;

	/**
	 * @var array
	 */
	private static $payever_status_info = array(
		'STATUS_PAID'       => true,
		'STATUS_ACCEPTED'   => true,
		'STATUS_IN_PROCESS' => true,
		'STATUS_CANCELLED'  => false,
		'STATUS_FAILED'     => false,
		'STATUS_DECLINED'   => false,
		'STATUS_REFUNDED'   => false,
		'STATUS_NEW'        => false,
	);

	/** @var WC_Payever_Order_Total */
	private $order_total_model;

	/**
	 * Construct
	 */
	public function __construct( $current_payment_id ) {
		$this->id              = $current_payment_id;
		$this->plugin_settings = WC_Payever_Helper::instance()->get_payever_plugin_settings();
		$this->init_settings();
		$this->payever_admin_payment_settings();
		$this->assign_payment_configuration_data();

		$this->countries = $this->settings['countries'];

		if ( ! array_key_exists( 'method_code', $this->settings ) ) {
			$this->settings['method_code'] = WC_Payever_Helper::instance()->remove_payever_prefix( $current_payment_id );
		}

		add_filter(
			'woocommerce_thankyou_order_received_text',
			array(
				&$this,
				'payever_thankyou_order_received_text',
			),
			10,
			2
		);

		add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( &$this, 'check_payever_callback' ) );

		WC_Payever_Helper::assert_wc_version_exists();

		if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
			add_action(
				'woocommerce_update_options_payment_gateways_' . $this->id,
				array( $this, 'process_admin_options' )
			);
		} else {
			add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );
		}

		add_action( 'woocommerce_api_payever_execute_commands', array( &$this, 'execute_commands' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( &$this, 'thankyou_page' ) );
		add_action( 'woocommerce_receipt_' . $this->id, array( &$this, 'receipt_page' ) );

		if ( version_compare( WOOCOMMERCE_VERSION, '2.3.0', '<' ) ) {
			add_action( 'woocommerce_order_details_after_order_table', array( &$this, 'display_transaction_info' ) );
		} elseif ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '<' ) ) {
			add_action( 'woocommerce_order_items_table', array( &$this, 'align_transaction_info' ) );
		} else {
			add_action(
				'woocommerce_order_details_after_order_table_items',
				array(
					&$this,
					'align_transaction_info',
				)
			);
		}

		WC_Payever_Helper::instance()->clear_session_fragments();
	}

	/**
	 * /wc-api/execute_commands/?token=:token
	 */
	public function execute_commands() {
		try {
			$token          = isset( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : ''; // WPCS: input var ok, CSRF ok.
			$business_uuid  = $this->plugin_settings[ WC_Payever_Helper::PAYEVER_BUSINESS_ID ];
			$is_valid_token = WC_Payever_Api::get_instance()
								->get_third_party_plugins_api_client()
								->validateToken( $business_uuid, $token );

			if ( $is_valid_token ) {
				WC_Payever_Plugin_Command_Cron::execute_plugin_commands();
				wp_send_json(
					array(
						'message' => 'The commands have been executed successfully',
						'result'  => 'success',
					)
				);
			}

			throw new \Exception( 'Invalid token' );
		} catch ( Exception $e ) {
			$this->log_exception( $e );

			wp_send_json(
				array(
					'message' => $e->getMessage(),
					'result'  => 'error',
				),
				400
			);
		}
	}

	/**
	 * @param Exception $exception
	 * @return void
	 */
	public function log_exception( \Exception $exception ) {
		$message = sprintf(
			'Exception with message "%s" occurred in %s on line %s',
			$exception->getMessage(),
			$exception->getFile(),
			$exception->getLine()
		);

		WC_Payever_Api::get_instance()->get_logger()->error( $message );
	}

	/**
	 * @param $text
	 * @param WC_Order|null $order
	 *
	 * @return string
	 */
	public function payever_thankyou_order_received_text( $text, $order ) {
		if ( WC_Payever_Helper::instance()->validate_order_payment_method( $order ) && false !== strpos( $order->get_status(), 'on-hold' ) ) {
			return __( 'Thank you, your order has been received. You will receive an update once your request has been processed.', 'payever-woocommerce-gateway' );
		}

		return $text;
	}

	/**
	 * Callback function
	 */
	public function check_payever_callback() {
		$callback_url = $this->get_callback_url();
		$type         = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		$payment_id   = isset( $_GET['paymentId'] ) ? sanitize_text_field( wp_unslash( $_GET['paymentId'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		$logger       = WC_Payever_Api::get_instance()->get_logger();
		$logger->info( sprintf( 'Handling callback type: %s', $type ) );
		switch ( $type ) {
			case 'finish':
				$this->finish_callback( $callback_url );
				break;
			case 'cancel':
				$this->cancel_callback( $callback_url );
				break;
			case 'error':
				$this->error_callback( $callback_url, $payment_id );
				break;
			case 'success':
			case 'processing':
				$callback_url = $this->payever_retrieve_payment( $payment_id );
				$this->web_redirect( $callback_url );
				break;
			case 'notice':
				$this->notice_callback( $payment_id );
				break;
		}

		die();
	}

	/**
	 * @return string
	 */
	private function get_callback_url() {
		return version_compare( WOOCOMMERCE_VERSION, '2.5.0', '>=' ) ? wc_get_cart_url() : WC()->cart->get_cart_url();
	}

	/**
	 * @param $payment_id
	 * @return void
	 */
	private function notice_callback( $payment_id ) {
		$locker       = WC_Payever_Api::get_instance()->get_locker();
		$logger       = WC_Payever_Api::get_instance()->get_logger();
		try {
			$notification_processor = new WC_Payever_Notification_Processor(
				new WC_Payever_Notification_Handler(),
				$locker,
				$logger
			);
			$headers = WC_Payever_Helper::instance()->get_request_headers();

			if ( isset( $headers['X-Payever-Signature'] ) ) {
				$notification_result = $notification_processor
					->validate_signature( $payment_id, $headers['X-Payever-Signature'] )
					->processNotification();
			} else {
				$retrieve_payment   = WC_Payever_Api::get_instance()->get_payments_api_client()->retrievePaymentRequest( $payment_id );
				$raw_data           = wp_kses_post( sanitize_text_field( file_get_contents( 'php://input' ) ) );  // WPCS: input var ok, CSRF ok.
				$raw_data           = json_decode( $raw_data, true );
				$payload            = array(
					'data'       => array(
						'payment' => $retrieve_payment->getResponseEntity()->getResult()->toArray(),
					),
					'created_at' => $raw_data['created_at'],
				);
				$notification_result = $notification_processor
					->skip_signature_validation()
					->processNotification( wp_json_encode( $payload ) );
			}

			echo esc_html( $notification_result->__toString() );
		} catch ( Exception $e ) {
			wp_send_json(
				array(
					'result'  => 'error',
					'message' => $e->getMessage(),
				),
				400
			);
			$locker->releaseLock( $payment_id );
		}
	}

	/**
	 * @param $callback_url
	 * @param $payment_id
	 * @return void
	 * @throws Exception
	 */
	private function error_callback( $callback_url, $payment_id ) {
		$logger   = WC_Payever_Api::get_instance()->get_logger();
		$order_id = isset( WC()->session->order_awaiting_payment )
			? absint( WC()->session->get( 'order_awaiting_payment' ) )
			: absint( WC()->session->get( 'store_api_draft_order', 0 ) );

		if ( $order_id > 0 ) {
			$order = wc_get_order( $order_id );
			$message = __( 'Payment has been declined.', 'payever-woocommerce-gateway' );

			if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
				$order->update_status( 'cancelled', $message );
			} else {
				$order->cancel_order( $message );
			}

			$payment_data = $this->prepare_payever_payment_data( $payment_id );
			WC_Payever_Helper::instance()->add_payever_hidden_method( $payment_data['payever_method'] );

			$logger->info( sprintf( 'Order %s Payment has been declined.', $order_id ) );
		}

		$this->get_wp_wrapper()->wc_add_notice(
			__( 'Payment has been declined', 'payever-woocommerce-gateway' ),
			'error'
		);
		$this->web_redirect( $callback_url );
	}

	/**
	 * @param $callback_url
	 * @return void
	 */
	private function cancel_callback( $callback_url ) {
		$order_id = isset( WC()->session->order_awaiting_payment )
			? absint( WC()->session->get( 'order_awaiting_payment' ) )
			: absint( WC()->session->get( 'store_api_draft_order', 0 ) );

		$logger = WC_Payever_Api::get_instance()->get_logger();
		if ( $order_id > 0 ) {
			$order   = wc_get_order( $order_id );
			$message = __( 'Order has been cancelled by customer.', 'payever-woocommerce-gateway' );

			if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
				$order->update_status( 'cancelled', $message );
			} else {
				$order->cancel_order( $message );
			}

			$logger->info( sprintf( 'Order %s was cancelled by customer', $order_id ) );
		}

		$this->get_wp_wrapper()->wc_add_notice(
			__( 'Payment was canceled', 'payever-woocommerce-gateway' ),
			'error'
		);

		$this->web_redirect( $callback_url );
	}

	/**
	 * @param $callback_url
	 * @return void
	 * @throws Exception
	 */
	private function finish_callback( $callback_url ) {
		$order_id = isset( $_GET['reference'] ) ? sanitize_text_field( wp_unslash( $_GET['reference'] ) ) : 0; // WPCS: input var ok, CSRF ok.
		$token    = isset( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		$message  = __( 'Token is invalid', 'payever-woocommerce-gateway' );
		$logger   = WC_Payever_Api::get_instance()->get_logger();

		if ( WC_Payever_Helper::instance()->get_hash( $order_id ) === $token ) {
			$message    = __( 'Tokens are matched', 'payever-woocommerce-gateway' );
			$payment_id = get_post_meta( $order_id, self::CURRENT_PAYEVER_PAYMENT_ID, true );
		}
		$logger->debug( $message );

		if ( empty( $payment_id ) ) {
			$logger->critical( 'payment_id is missing', array( $order_id, $token ) );

			$this->web_redirect( $callback_url );

			return;
		}

		self::$payever_status_info['STATUS_NEW'] = true;
		$callback_url                            = $this->payever_retrieve_payment( $payment_id, $order_id );
		$this->web_redirect( $callback_url );
	}

	/**
	 * Preparing payever payment data
	 *
	 * @param $payment_id
	 *
	 * @return array
	 * @throws Exception
	 */
	private function prepare_payever_payment_data( $payment_id ) {
		$payment_data     = array();
		$retrieve_payment = WC_Payever_Api::get_instance()->get_payments_api_client()->retrievePaymentRequest( $payment_id );

		if ( $retrieve_payment->isSuccessful() ) {
			$payment_result                     = $retrieve_payment->getResponseEntity()->getResult();
			$payment_data['status']             = true;
			$payment_data['payment_id']         = $payment_id;
			$payment_data['order_id']           = $payment_result->getReference();
			$payment_data['payever_status']     = $payment_result->getStatus();
			$payment_data['payever_method']     = $payment_result->getPaymentType();
			$payment_data['application_number'] = $payment_result->getPaymentDetails()->getApplicationNumber();
			$payment_data['pan_id']             = $payment_result->getPaymentDetails()->getUsageText();

			$status_mapping = WC_Payever_Helper::instance()->get_payever_status_mapping();
			if ( version_compare( WOOCOMMERCE_VERSION, '2.2.0', '>=' ) ) {
				$payment_data['payever_wc_status'] = 'wc-' . $status_mapping[ $payment_data['payever_status'] ];
			} else {
				$payment_data['payever_wc_status'] = $status_mapping[ $payment_data['payever_status'] ];
			}
		} else {
			$payment_data['status'] = false;
		} // End if().

		return $payment_data;
	}

	/**
	 * Updating order information
	 *
	 * @param $order_id
	 * @param $payment_data
	 */
	private function update_order( $payment_data ) {
		$order = new WC_Order( $payment_data['order_id'] );
		$order->update_status( $payment_data['payever_wc_status'] );

		/**
		 * Woocommerce restocks products only on 'cancelled' status
		 */
		if ( 'wc-failed' === $payment_data['payever_wc_status'] && version_compare( WOOCOMMERCE_VERSION, '3.5.0', '>=' ) ) {
			wc_maybe_increase_stock_levels( $order->get_id() );
		}

		if ( WC_Payever_Helper::instance()->is_santander( $payment_data['payever_method'] ) ) {
			update_post_meta( $payment_data['order_id'], 'Santander application number', $payment_data['application_number'] );
		}
		update_post_meta( $payment_data['order_id'], self::PAYEVER_PAYMENT_ID, $payment_data['payment_id'] );

		if ( ! empty( $payment_data['pan_id'] ) ) {
			update_post_meta( $payment_data['order_id'], 'pan_id', $payment_data['pan_id'] );
		}
	}

	/**
	 * Retrieve payment
	 *
	 * @param integer $payment_id Transaction id.
	 * @param integer $order_id Order id.
	 *
	 * @return mixed
	 */
	private function payever_retrieve_payment( $payment_id, $order_id = 0 ) {
		$callback_url = $this->get_callback_url();
		$locker       = WC_Payever_Api::get_instance()->get_locker();
		$locker->acquireLock( $payment_id, self::LOCK_WAIT_SECONDS );
		try {
			$payment_data = $this->prepare_payever_payment_data( $payment_id );
		} catch ( Exception $e ) {
			$this->get_wp_wrapper()->wc_add_notice( $e->getMessage(), 'error' );
			$locker->releaseLock( $payment_id );

			return $callback_url;
		}

		if ( ! $order_id ) {
			$order_id = $payment_data['order_id'];
		}

		if ( $payment_data['status'] ) {
			$order = new WC_Order( $order_id );
			$this->update_order( $payment_data );

			if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
				$order->set_transaction_id( $payment_data['payment_id'] );
				$order->save();
			}

			$is_reduced = get_post_meta( $order_id, 'payever_order_stock_reduced', true );

			if ( ! self::$payever_status_info[ $payment_data['payever_status'] ] ) {
				WC_Payever_Helper::instance()->add_payever_hidden_method( $payment_data['payever_method'] );

				$error = WC_Payever_Helper::instance()->is_santander( $payment_data['payever_method'] )
					? __( 'Unfortunately, the application was not successful. Please choose another payment option to pay for your order.', 'payever-woocommerce-gateway' )
					: __( 'The payment was not successful. Please try again or choose another payment option.', 'payever-woocommerce-gateway' );

				$this->increase_order_stock( $is_reduced, $order_id );

				$this->get_wp_wrapper()->wc_clear_notices();
				$this->get_wp_wrapper()->wc_add_notice( $error, 'error' );
			} else {
				$callback_url = html_entity_decode( $this->get_return_url( $order ) );
				$this->reduce_order_stock( $is_reduced, $order );
			} // End if().
		}
		$locker->releaseLock( $payment_id );

		return $callback_url;
	}

	/**
	 * @param $is_reduced
	 * @param $order_id
	 */
	private function increase_order_stock( $is_reduced, $order_id ) {
		if ( $is_reduced && version_compare( WOOCOMMERCE_VERSION, '3.5.0', '>=' ) ) {
			wc_maybe_increase_stock_levels( $order_id );
		}
	}

	/**
	 * @param bool $is_reduced_before
	 * @param WC_Order $order
	 */
	private function reduce_order_stock( $is_reduced_before, $order ) {
		if ( $is_reduced_before ) {
			return;
		}

		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$order_id = $order->get_id();
			wc_reduce_stock_levels( $order_id );
		} else {
			$order_id = $order->id;
			$order->reduce_order_stock();
		}

		add_post_meta( $order_id, 'payever_order_stock_reduced', '1', true );
	}

	/**
	 * Html redirect
	 *
	 * @param string $url Url for redirect.
	 */
	private function web_redirect( $url ) {
		$logger = WC_Payever_Api::get_instance()->get_logger();
		$logger->debug( sprintf( 'Web redirect to: %s', $url ) );
		?>
		<!DOCTYPE html>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
		<head>
			<script>
				parent.document.location.href='<?php echo esc_url_raw( $url ); ?>';
			</script>
			<noscript>
				<meta charset="UTF-8">
				<meta http-equiv="refresh" content="0; URL=<?php echo esc_url_raw( $url ); ?>">
			</noscript>
			<title>Page Redirection</title>
		</head>
		<body>
			<p>This page has been moved. If you are not redirected within 3 seconds, click <a href="<?php echo esc_url_raw( $url ); ?>">here</a>.</p>
		</body>
		</html>
		<?php
	}

	/**
	 * Gateway configurations in shop backend
	 *
	 * @return void
	 */
	private function payever_admin_payment_settings() {
		$currency_min_max = WC_Payever_Helper::instance()->is_santander( $this->id )
			? $this->settings['currencies'][0]
			: get_woocommerce_currency();

		$this->form_fields = array(
			'enabled'         => array(
				'title'   => __( 'Enable / Disable', 'payever-woocommerce-gateway' ),
				'type'    => 'checkbox',
				'label'   => sprintf( __( 'Enable %s', 'payever-woocommerce-gateway' ), $this->settings['title'] ),
				'default' => '',
			),
			'title'           => array(
				'title'       => __( 'Payment Title <sup>*</sup>', 'payever-woocommerce-gateway' ),
				'type'        => 'text',
				'description' => '',
				'default'     => '',
			),
			'description'     => array(
				'title'       => __( 'Description <sup>*</sup>', 'payever-woocommerce-gateway' ),
				'type'        => 'textarea',
				'description' => '',
				'default'     => '',
			),
			'accept_fee'      => array(
				'title'             => __( 'Enable / Disable Fee', 'payever-woocommerce-gateway' ),
				'type'              => 'checkbox',
				'label'             => __( 'Merchant covers fees', 'payever-woocommerce-gateway' ),
				'custom_attributes' => array(
					'onclick' => 'return false;',
				),
				'default'           => '',
			),
			'fee'             => array(
				'title'             => __( 'Fixed Fee', 'payever-woocommerce-gateway' ),
				'type'              => 'text',
				'desc_tip'          => true,
				'custom_attributes' => array(
					'readonly' => 'readonly',
				),
				'default'           => '',
			),
			'variable_fee'    => array(
				'title'             => __( 'Variable Fee', 'payever-woocommerce-gateway' ),
				'type'              => 'text',
				'desc_tip'          => true,
				'custom_attributes' => array(
					'readonly' => 'readonly',
				),
				'default'           => '',
			),
			'min_order_total' => array(
				'title'       => __( 'Minimum Order Total', 'payever-woocommerce-gateway' ) . ' (' . $currency_min_max . ') <sup>*</sup>',
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $currency_min_max,
				'default'     => '',
			),
			'max_order_total' => array(
				'title'       => __( 'Maximum Order Total', 'payever-woocommerce-gateway' ) . ' (' . $currency_min_max . ') <sup>*</sup>',
				'type'        => 'text',
				'desc_tip'    => true,
				'description' => $currency_min_max,
				'default'     => '',
			),
		);

		if ( array_key_exists( 'is_redirect_method', $this->settings ) ) {
			$this->form_fields['is_redirect_method'] = array(
				'title'   => __( 'Is redirect method', 'payever-woocommerce-gateway' ),
				'type'    => 'checkbox',
				'label'   => sprintf( __( 'Apply submit payment for %s', 'payever-woocommerce-gateway' ), $this->settings['title'] ),
				'default' => '',
			);
		}
	}

	/**
	 * Assign the Configuration data's to its member functions
	 *
	 * @return void
	 */
	public function assign_payment_configuration_data() {
		$this->method_title       = $this->settings['title'];
		$this->method_description = $this->settings['description'];

		if ( 'yes' === $this->settings['enabled'] ) {
			$this->title       = sanitize_text_field( $this->settings['title'] );
			$this->description = sanitize_text_field( $this->settings['description'] );

			$this->accept_fee   = $this->settings['accept_fee'];
			$this->fee          = floatval( $this->settings['fee'] );
			$this->variable_fee = floatval( $this->settings['variable_fee'] );

			$this->is_redirect_method = array_key_exists( 'is_redirect_method', $this->settings )
				? 'yes' === $this->settings['is_redirect_method']
				: false;

			$this->min_amount = intval( $this->settings['min_order_total'] );
			$this->max_amount = intval( $this->settings['max_order_total'] );
		}
	}

	/**
	 * Check if the gateway is available for use
	 *
	 * @return bool
	 */
	public function is_available() {
		/** @var WooCommerce $woocommerce */
		global $woocommerce;
		$is_available = $this->is_payment_enabled();

		//Check if a page is a checkout page or an ajax request to change the address.
		if ( ! ( is_checkout() || isset( $_GET['rest_route'] ) && $woocommerce->customer ) ) {
			return $is_available;
		}

		if ( $this->is_payment_rules_not_valid( $woocommerce ) ) {
			return false;
		}

		if ( ! WC_Payever_Helper::instance()->validate_hidden_methods( $this->settings['method_code'], $this->settings['variant_id'] ) ) {
			return false;
		}

		return $is_available;
	}

	/**
	 * Check if the order payment rules are correct
	 *
	 * @return bool
	 */
	private function is_payment_rules_not_valid( $woocommerce ) {
		$billing_country  = $this->get_customer_billing_country( $woocommerce );
		$shipping_country = $woocommerce->customer->get_shipping_country();

		return ! isset( $this->settings['countries'] )
			|| ! isset( $this->settings['currencies'] )
			|| ! $this->is_payment_available_by_cart_amount()
			|| ! $this->is_payment_available_by_options( get_woocommerce_currency(), $this->settings['currencies'] )
			|| ! $this->is_payment_available_by_options( $billing_country, $this->settings['countries'] )
			|| ! $this->is_payment_available_by_options( $shipping_country, $this->settings['countries'] );
	}

	private function is_payment_enabled() {
		return 'yes' === $this->enabled && $this->plugin_settings[ WC_Payever_Helper::PAYEVER_ENABLED ];
	}

	private function get_customer_billing_country( $woocommerce ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return $woocommerce->customer->get_billing_country();
		}

		return $woocommerce->customer->get_country();
	}

	private function is_payment_available_by_cart_amount() {
		if (
			WC()->cart
			&& 0 < $this->get_order_total()
			&& 0 < $this->max_amount
			&& $this->max_amount < $this->get_order_total()
			|| $this->min_amount >= 0
			&& $this->min_amount > $this->get_order_total()
		) {
			return false;
		}

		return true;
	}

	private function is_payment_available_by_options( $value, $options ) {
		if ( ! in_array( $value, $options ) && ! in_array( 'any', $options ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get the order total in checkout and pay_for_order.
	 *
	 * @return float
	 */
	protected function get_order_total() {
		$total    = 0;
		$order_id = absint( get_query_var( 'order-pay' ) );

		// Gets order total from "pay for order" page.
		if ( 0 < $order_id ) {
			$order = wc_get_order( $order_id );
			$total = (float) $order->get_total();

			// Gets order total from cart/checkout.
		} elseif ( 0 < WC()->cart->get_total( false ) ) {
			$total = (float) WC()->cart->get_total( false );
		}

		if ( 0 === $total && version_compare( WOOCOMMERCE_VERSION, '3.5', '<' ) ) {
			$total = WC()->cart->total;
		}

		return sprintf( '%.2f', $total );
	}

	/**
	 * Returns the gateway icon.
	 *
	 * @return string
	 */
	public function get_icon( $url_only = null ) {
		if ( 'yes' === $this->plugin_settings[ WC_Payever_Helper::PAYEVER_DISPLAY_ICON ] ) {
			if ( $this->settings['icon'] ) {
				$icon_html = $url_only
					? $this->settings['icon']
					: '<img src="' . esc_attr( $this->settings['icon'] ) . '" alt="' . esc_attr( $this->title ) . '" class="payever_icon" title="' . esc_attr( $this->title ) . '" />';

				return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
			}
		}
	}

	/**
	 * Returns the gateway's title
	 *
	 * @return string
	 */
	public function get_title() {
		$title = $this->title;

		if ( 'yes' === $this->plugin_settings[ WC_Payever_Helper::PAYEVER_DISPLAY_TITLE ] ) {
			$fee_label = $this->get_fee_label();
			$title     .= $fee_label;
		}

		if ( 0 !== (int) $this->plugin_settings[ WC_Payever_Helper::PAYEVER_ENVIRONMENT ] ) {
			$modes = WC_Payever_Helper::instance()->get_payever_modes();
			$title .= ' ' . strtoupper( $modes[ $this->plugin_settings[ WC_Payever_Helper::PAYEVER_ENVIRONMENT ] ] ) . ' ' . __( 'Mode', 'payever-woocommerce-gateway' );
		}

		return apply_filters( 'woocommerce_gateway_title', $title, $this->id );
	}

	/**
	 * Returns fee label
	 *
	 * @return string
	 */
	private function get_fee_label() {
		$fee_label = '';

		if ( 'no' === $this->accept_fee ) {
			$fixed_fee    = $this->fee;
			$variable_fee = $this->variable_fee;

			if ( 0 < $fixed_fee && 0 < $variable_fee ) {
				$fixed_fee = wp_strip_all_tags( wc_price( $fixed_fee ) );
				$fee_label = "({$variable_fee}% + {$fixed_fee})";
			} elseif ( $fixed_fee <= 0.001 && 0 < $variable_fee ) {
				$fee_label = "(+ {$variable_fee}%)";
			} elseif ( 0 < $fixed_fee && $variable_fee <= 0.001 ) {
				$fixed_fee = wp_strip_all_tags( wc_price( $fixed_fee ) );
				$fee_label = "(+ {$fixed_fee})";
			}
		}

		return $fee_label;
	}

	/**
	 * Return the gateway's description
	 *
	 * @return string
	 */
	public function get_description() {
		$description = $this->description;
		if ( ( 'yes' === $this->plugin_settings[ WC_Payever_Helper::PAYEVER_DISPLAY_DESCRIPTION ] ) && ! empty( $description ) ) {
			return apply_filters( 'woocommerce_gateway_description', $description, $this->id );
		}
	}

	/**
	 * Set this as the current gateway.
	 *
	 * @return void
	 */
	public function set_current() {
		$this->chosen = true;
	}

	/**
	 * Displays the payment form, payment description on checkout
	 *
	 * @return void
	 */
	public function payment_fields() {
		$description = $this->description;
		if ( ( 'yes' === $this->plugin_settings[ WC_Payever_Helper::PAYEVER_DISPLAY_DESCRIPTION ] ) && ! empty( $description ) ) {
			esc_html_e( $description );
		}
	}

	/**
	 * Process the payment and return the result
	 *
	 * @param integer $order_id Order id.
	 *
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$customer_order = new WC_Order( $order_id );
		WC_Payever_Api::get_instance()->get_logger()->debug( 'Order generated for the payment : ' . $this->id . '. Order no : ' . $order_id );
		$redirect_mode = ( $this->is_redirect_method ?: ( 'yes' === $this->plugin_settings[ WC_Payever_Helper::PAYEVER_REDIRECT_MODE ] ) ) ? true : false;
		$redirect_url  = $redirect_mode ? $this->get_payment_url( $order_id ) : $customer_order->get_checkout_payment_url( true );

		if ( $redirect_url ) {
			return array(
				'result'   => 'success',
				'redirect' => $redirect_url,
			);
		}

		return array(
			'result'   => 'failure',
			'redirect' => wc_get_endpoint_url( 'checkout' ),
		);
	}

	/**
	 * Process refund.
	 *
	 * If the gateway declares 'refunds' support, this will allow it to refund.
	 * a passed in amount.
	 *
	 * @param int $order_id Order ID.
	 * @param float|null $amount Refund amount.
	 * @param string $reason
	 *
	 * @return bool|WP_Error True or false based on success, or a WP_Error object.
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		try {
			$payment_id = get_post_meta( $order_id, self::CURRENT_PAYEVER_PAYMENT_ID, true ) ?: get_post_meta( $order_id, self::PAYEVER_PAYMENT_ID, true );
			$api        = WC_Payever_Api::get_instance()->get_payments_api_client();
			$refund     = $order->get_refunds();
			$refund     = reset( $refund );

			if ( version_compare( WOOCOMMERCE_VERSION, '3.3.0', '>=' ) &&
				( $refund->get_refunded_payment() || $refund->get_amount() !== $amount )
			) {
				throw new \RuntimeException(
					__(
						'Unable to determine refund entity or refund has already been processed.',
						'payever-woocommerce-gateway'
					)
				);
			}

			$openbank_refund_process_result = $this->openbank_refund_process(
				$order,
				$api,
				$payment_id,
				$amount,
				$refund,
				$order_id
			);

			if ( $openbank_refund_process_result ) {
				return $openbank_refund_process_result;
			}
			$refund_process_result = $this->refund_process(
				$api,
				$payment_id,
				$refund,
				$amount,
				$order,
				$order_id
			);

			if ( $refund_process_result ) {
				return $refund_process_result;
			}

			return new WP_Error(
				'refund_failed',
				__( 'Sorry, but refund is not available now', 'payever-woocommerce-gateway' )
			);
		} catch ( Exception $e ) {
			/**
			 * Reset tokens in case some of them caused this error
			 */
			WC_Payever_Api::get_instance()->get_payments_api_client()->getTokens()->clear()->save();
			$order->add_order_note(
				__(
					'<p style="color: red;">' . $e->getMessage() . '</p>',
					'payever-woocommerce-gateway'
				)
			);

			return new WP_Error( 'refund_failed', $e->getMessage() );
		}
	}

	/**
	 * @param $api
	 * @param $payment_id
	 * @param $refund
	 * @param $amount
	 * @param $order
	 * @param $order_id
	 * @return bool
	 * @throws Exception
	 */
	public function refund_process( $api, $payment_id, $refund, $amount, $order, $order_id ) {
		$actionDecider = new ActionDecider( $api );
		if ( ! $actionDecider->isRefundAllowed( $payment_id ) ) {
			return false;
		}
		WC_Payever_Api::get_instance()->get_logger()->info( 'Refund is allowed' );
		$items = $refund->get_items();

		if ( $items ) {
			$refund_items       = array();
			$total_items_amount = 0;
			$refunded_items     = '';
			$this->prepare_refunded_items(
				$items,
				$refund_items,
				$total_items_amount,
				$refunded_items
			);

			if ( round( $amount, 2 ) === round( $total_items_amount, 2 ) ) {
				$this->refund_by_items_request(
					$api,
					$payment_id,
					$refund_items,
					$order,
					$refunded_items,
					$total_items_amount,
					$refund,
					$order_id
				);
				return true;
			}
		}
		$refund_response = $api->refundPaymentRequest( $payment_id, $amount );
		$transaction_id  = $refund_response->getResponseEntity()->getCall()->getId();

		$order->add_order_note(
			__(
				'<p style="color: green;">Refunded ' . wc_price( $amount ) . '. Transaction ID: ' . $transaction_id . '</p>',
				'payever-woocommerce-gateway'
			)
		);

		if ( version_compare( WOOCOMMERCE_VERSION, '3.3.0', '>=' ) ) {
			$refund->set_refunded_payment( true );
		}

		return true;
	}

	/**
	 * @param WC_Order $order
	 * @param $api
	 * @param $payment_id
	 * @param $amount
	 * @param $refund
	 * @param $order_id
	 * @return bool
	 * @throws Exception
	 */
	private function openbank_refund_process( $order, $api, $payment_id, $amount, $refund, $order_id ) {
		$helper = WC_Payever_Helper::instance();
		if ( ! $helper->is_openbank( $helper->get_payment_method( $order ) ) ) {
			return false;
		}
		$transaction = $api->retrievePaymentRequest( $payment_id )->getResponseEntity()->getResult();
		$actionDecider = new ActionDecider( $api );

		if ( $transaction->getStatus() === Status::STATUS_ACCEPTED &&
			$actionDecider->isCancelAllowed( $payment_id, false )
		) {
			$this->cancel_request( $api, $payment_id, $amount, $order, $refund, $order_id );

			return true;
		}

		return false;
	}

	/**
	 * @param $item
	 * @return array
	 */
	private function get_item_data( $item ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			return array(
				'total'      => abs( $item->get_subtotal() + $item->get_subtotal_tax() ),
				'quantity'   => abs( $item->get_quantity() ),
				'name'       => $item->get_name(),
				'product_id' => $item->get_product_id(),
			);
		}

		return array(
			'total'      => abs( $item['item_meta']['_line_subtotal'][0] + $item['item_meta']['_line_subtotal_tax'][0] ),
			'quantity'   => abs( $item['item_meta']['_qty'][0] ),
			'name'       => $item['name'],
			'product_id' => $item['product_id'],
		);
	}

	/**
	 * @param $items
	 * @param $refund_items
	 * @param $total_items_amount
	 * @param $refunded_items
	 * @return $this
	 */
	private function prepare_refunded_items( $items, &$refund_items, &$total_items_amount, &$refunded_items ) {
		foreach ( $items as $item ) {
			$refund_item_entity = new PaymentItemEntity();
			$item_data          = $this->get_item_data( $item );

			$refund_item_entity
				->setName( $item_data['name'] )
				->setIdentifier( strval( $item_data['product_id'] ) )
				->setPrice( round( $item_data['total'] / $item_data['quantity'], 2 ) )
				->setQuantity( intval( $item_data['quantity'] ) );

			$refund_items[]     = $refund_item_entity;
			$total_items_amount += $item_data['total'];
			$refunded_items     .= $item_data['quantity'] . ' x ' . $item_data['name'] . '<br />';
		}

		return $this;
	}

	/**
	 * @param $api
	 * @param $payment_id
	 * @param $refund_items
	 * @param $order
	 * @param $refunded_items
	 * @param $total_items_amount
	 * @param $refund
	 * @param $order_id
	 * @return $this
	 */
	private function refund_by_items_request(
		$api,
		$payment_id,
		$refund_items,
		$order,
		$refunded_items,
		$total_items_amount,
		$refund,
		$order_id
	) {
		$refund_response = $api->refundItemsPaymentRequest( $payment_id, $refund_items );
		$transaction_id  = $refund_response->getResponseEntity()->getCall()->getId();

		$order->add_order_note(
			__(
				'<p style="color: green;">Refunded: <br />' . $refunded_items . 'Refunded amount: ' . wc_price( $total_items_amount ) . '. Transaction ID: ' . $transaction_id . '</p>',
				'payever-woocommerce-gateway'
			)
		);

		if ( version_compare( WOOCOMMERCE_VERSION, '3.3.0', '>=' ) ) {
			$refund->set_refunded_payment( true );
		}

		set_transient(
			'pe_lock_refund_' . $order_id,
			$total_items_amount,
			MINUTE_IN_SECONDS
		);

		return $this;
	}

	/**
	 * @param $api
	 * @param $payment_id
	 * @param $amount
	 * @param $order
	 * @param $refund
	 * @param $order_id
	 * @return $this
	 */
	private function cancel_request( $api, $payment_id, $amount, $order, $refund, $order_id ) {
		$cancel_response = $api->cancelPaymentRequest( $payment_id, $amount );
		$transaction_id  = $cancel_response->getResponseEntity()->getCall()->getId();
		$order->add_order_note(
			__(
				'<p style="color: green;">Cancelled ' . wc_price( $amount ) . '. Transaction ID: ' . $transaction_id . '</p>',
				'payever-woocommerce-gateway'
			)
		);

		if ( version_compare( WOOCOMMERCE_VERSION, '3.3.0', '>=' ) ) {
			$refund->set_refunded_payment( true );
		}

		set_transient(
			'pe_lock_cancel_' . $order_id,
			$amount,
			MINUTE_IN_SECONDS
		);

		return $this;
	}

	/**
	 * WooCommerce thankyou page
	 * calls from hook "woocommerce_thankyou_{gateway_id}"
	 *
	 * @param integer $order_id Order id.
	 */
	public function thankyou_page( $order_id ) {
		if ( ! isset( WC()->session->payever_thankyou_page ) ) {
			WC()->session->set( 'payever_thankyou_page', true );
		}

		try {
			$payment_id    = get_post_meta( $order_id, self::CURRENT_PAYEVER_PAYMENT_ID, true ) ?: get_post_meta( $order_id, self::PAYEVER_PAYMENT_ID, true ); //phpcs:ignore
			$api           = WC_Payever_Api::get_instance()->get_payments_api_client();
			$transaction = $api->retrievePaymentRequest( $payment_id )->getResponseEntity()->getResult();

			if ( Status::STATUS_PAID === $transaction->getStatus() ) {
				// Update captured items qty
				$order = wc_get_order( $order_id );
				$order_items = $this->get_order_total_model()->get_order_items( $order_id );
				$items = $order->get_items( array( 'line_item', 'shipping', 'fee' ) );
				foreach ( $items as $item_id => $item ) {
					if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
						/** @var WC_Order_Item $item */
						$item_id  = $item->get_id();
						$quantity = $item->get_quantity();
					} else {
						/** @var array $item */
						$quantity = $item['item_meta']['_qty'][0];
					}

					foreach ( $order_items as $key => $order_item ) {
						if ( (string) $item_id === (string) $order_item['item_id'] ) {
							$order_items[ $key ]['captured_qty'] += $quantity;
						}
					}

					update_post_meta( $order_id, '_payever_order_items', $order_items );
				}

				$this->get_order_total_model()->get_order_items( $order_id );
			}
		} catch ( Exception $exception ) {
			$this->log_exception( $exception );
		}
	}

	/**
	 * WooCommerce receipt page
	 * calls from hook "woocommerce_receipt_{gateway_id}"
	 *
	 * @param integer $order_id Order identificator.
	 */
	public function receipt_page( $order_id ) {
		if ( ! isset( WC()->session->payever_receipt_page ) ) {
			$payment_url = $this->get_payment_url( $order_id );
			if ( $payment_url ) {
				echo '<iframe allow="payment" sandbox="allow-same-origin allow-forms allow-top-navigation allow-scripts allow-modals allow-popups allow-popups-to-escape-sandbox" 
					  id="payever_iframe" width="100%" src="' . esc_url( $payment_url ) . '" style="border:none; 
					  min-height: 600px;"></iframe>';

				WC()->session->set( 'payever_receipt_page', true );
			}
		} // End if().
	}

	/**
	 * @param $order_id
	 *
	 * @return bool|string
	 */
	private function get_payment_url( $order_id ) {
		try {
			if ( WC_Payever_Helper::instance()->is_v2_api_version() ) {
				return $this->create_payment_v2( $order_id );
			}

			return $this->is_redirect_method ? $this->submit_payment( $order_id ) : $this->create_payment( $order_id );
		} catch ( Exception $e ) {
			$this->get_wp_wrapper()->wc_add_notice( $e->getMessage(), 'error' );
		}

		return false;
	}

	private function get_product_thumbnail_url( $image_id ) {
		$thumb = wp_get_attachment_image_src( $image_id, 'thumbnail' );
		if ( ! empty( $thumb ) ) {
			return array_shift( $thumb );
		}

		return wc_placeholder_img_src( 'thumbnail' );
	}

	/**
	 * @param $item
	 * @param $customer_order
	 * @return array
	 */
	private function get_order_item_data( $item, $customer_order ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
			$quantity = $item->get_quantity();
			return array(
				'quantity'      => $quantity,
				'price_ex'      => $customer_order->get_line_subtotal( $item, false, false ) / $quantity,
				'price_incl'    => $customer_order->get_line_subtotal( $item, true, false ) / $quantity,
				'post'          => get_post( $item->get_product_id() ),
				'url'           => $item->get_product()->get_permalink(),
				'product_id'    => $item->get_product_id(),
				'variation_id'  => $item->get_variation_id(),
				'thumbnail_url' => $this->get_product_thumbnail_url( $item->get_product()->get_image_id() ),
			);
		}
		$quantity = $item['item_meta']['_qty'][0];
		$price_ex = $item['item_meta']['_line_subtotal'][0];
		$tax      = $item['item_meta']['_line_subtotal_tax'][0];
		$post     = get_post( $item['item_meta']['_product_id'][0] );

		return array(
			'quantity'      => $quantity,
			'price_ex'      => $item['item_meta']['_line_subtotal'][0] / $quantity,
			'price_incl'    => ( $price_ex + $tax ) / $quantity,
			'post'          => get_post( $item['item_meta']['_product_id'][0] ),
			'url'           => get_permalink( $item['item_meta']['_product_id'][0] ),
			'product_id'    => $item['product_id'],
			'variation_id'  => $item['variation_id'],
			'thumbnail_url' => $this->get_product_thumbnail_url( get_post_thumbnail_id( $post->ID ) ),
		);
	}

	private function get_order_fees( $fee ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
			return array(
				'id'         => $fee['id'],
				'name'       => $fee['name'],
				'line_total' => floatval( $fee['line_total'] ),
				'line_tax'   => floatval( $fee['line_tax'] ),
			);
		}

		return array(
			'id'         => $fee->get_id(),
			'name'       => $fee->get_name(),
			'line_total' => $fee->get_total(),
			'line_tax'   => $fee->get_total_tax(),
		);
	}

	private function add_fees_line( $customer_order, &$products ) {
		$payever_fee = 0;
		foreach ( $customer_order->get_fees() as $fee ) {
			$fees = $this->get_order_fees( $fee );

			// Skip payever fees
			if ( __( 'payever Fee', 'payever-woocommerce-gateway' ) === $fees['name'] ) {
				$payever_fee += ( $fees['line_total'] + $fees['line_tax'] );
				continue;
			}

			$fee_rate = ( abs( $fees['line_tax'] ) > 0 )
				? round( 100 / ( $fees['line_total'] / $fees['line_tax'] ) ) : 0;

			$products[] = array(
				'name'        => $fees['name'],
				'price'       => apply_filters( 'pe_round', $fees['line_total'] + $fees['line_tax'] ),
				'identifier'  => 'fee-' . $fees['id'],
				'vatRate'     => $fee_rate,
				'quantity'    => 1,
				'description' => '',
				'thumbnail'   => '',
				'url'         => '',
			);
		}

		return $payever_fee;
	}

	/**
	 * Gets order products
	 *
	 * @param WC_Order $customer_order Customer order.
	 *
	 * @return array Products array
	 */
	private function get_order_products( $customer_order ) {
		$products = array();
		$items    = $customer_order->get_items( array( 'line_item' ) );

		foreach ( $items as $item ) {
			/** @var WC_Order_Item_Product $item */
			$item_data = $this->get_order_item_data( $item, $customer_order );
			$products[] = array(
				'name'        => $item['name'],
				'sku'         => sanitize_title( wc_get_product( $item_data['product_id'] )->get_sku() ),
				'price'       => apply_filters( 'pe_round', $item_data['price_incl'] ),
				'priceNetto'  => apply_filters( 'pe_round', floatval( $item_data['price_ex'] ) ),
				'identifier'  => strval( $item_data['variation_id'] ?: $item_data['product_id'] ),
				'vatRate'     => $item_data['price_ex'] > 0 ?
					apply_filters( 'pe_round', ( $item_data['price_incl'] / $item_data['price_ex'] - 1 ) ) * 100 : 0,
				'quantity'    => intval( $item_data['quantity'] ),
				'description' => $item_data['post']->post_excerpt,
				'thumbnail'   => $item_data['thumbnail_url'],
				'url'         => $item_data['url'],
			);
		}

		// Add discount line
		if ( $customer_order->get_total_discount( false ) > 0 ) {
			$discount_incl = $customer_order->get_total_discount( false );
			$products[] = array(
				'name'        => __( 'Discount', 'payever-woocommerce-gateway' ),
				'price'       => apply_filters( 'pe_round', - 1 * $discount_incl ),
				'identifier'  => 'discount',
				'quantity'    => 1,
				'description' => '',
				'thumbnail'   => '',
				'url'         => '',
			);
		}
		// Add fee lines
		$payever_fee = $this->add_fees_line( $customer_order, $products );
		$this->add_fee_line( $customer_order, $products, $payever_fee );

		return $products;
	}

	/**
	 * @param $customer_order
	 * @return mixed
	 */
	private function get_order_shipping_amount( $customer_order ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) ) {
			return $customer_order->get_total_shipping() + $customer_order->get_shipping_tax();
		}

		return $customer_order->get_shipping_total() + $customer_order->get_shipping_tax();
	}

	/**
	 * @param $customer_order
	 * @param $products
	 * @param $payever_fee
	 * @return $this
	 */
	private function add_fee_line( $customer_order, &$products, $payever_fee ) {
		// Verify totals
		$total = 0;
		foreach ( $products as $product ) {
			$total += ( $product['price'] * $product['quantity'] );
		}
		$diff = apply_filters(
			'pe_round',
			$customer_order->get_total() - ( $total + $payever_fee + $this->get_order_shipping_amount( $customer_order ) )
		);
		if ( abs( $diff ) >= 0.01 ) {
			$products[] = array(
				'name'        => __( 'Fee', 'payever-woocommerce-gateway' ),
				'price'       => apply_filters( 'pe_round', $diff ),
				'identifier'  => 'fee',
				'quantity'    => 1,
				'description' => '',
				'thumbnail'   => '',
				'url'         => '',
			);
		}

		return $this;
	}

	/**
	 * @Depricated
	 *
	 * Create payment request
	 *
	 * @param $order_id
	 *
	 * @return string
	 * @throws Exception
	 */
	private function create_payment( $order_id ) {
		$payment_parameters = $this->build_payment_parameters( $order_id, new CreatePaymentRequest() );

		$payment_response = WC_Payever_Api::get_instance()
								->get_payments_api_client()
								->createPaymentRequest( $payment_parameters );

		$language = empty( $this->plugin_settings[ WC_Payever_Helper::PAYEVER_LANGUAGES ] )
			? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ), 0, 2 ) // WPCS: input var ok, CSRF ok.
			: $this->plugin_settings[ WC_Payever_Helper::PAYEVER_LANGUAGES ];

		return $payment_response->getResponseEntity()->getRedirectUrl() . '?_locale=' . $language;
	}

	/**
	 * Submit payment request
	 *
	 * @param $order_id
	 *
	 * @return string
	 * @throws Exception
	 */
	private function submit_payment( $order_id ) {
		$payment_parameters = $this->build_payment_parameters( $order_id, new SubmitPaymentRequest() );

		$params  = array(
			'reference' => $order_id,
			'token'     => WC_Payever_Helper::instance()->get_hash( $order_id ),
		);
		$payment = array(
			'frontendFinishUrl' => $this->generate_callback_url( 'finish', null, $params ),
			'frontendCancelUrl' => $this->generate_callback_url( 'cancel' ),
		);
		$payment_parameters->setPaymentData( $payment );
		$payment_response = WC_Payever_Api::get_instance()->get_payments_api_client()->submitPaymentRequest( $payment_parameters );
		update_post_meta( $order_id, self::CURRENT_PAYEVER_PAYMENT_ID, $payment_response->getResponseEntity()->getResult()->getId() );

		return $payment_response->getResponseEntity()->getResult()->getPaymentDetails()->getRedirectUrl();
	}

	/**
	 * Create payment request
	 *
	 * @param $order_id
	 *
	 * @return string
	 * @throws Exception
	 */
	private function create_payment_v2( $order_id ) {
		$payment_parameters = $this->build_payment_parameters( $order_id, new CreatePaymentV2Request() );

		$payment_data = $payment_parameters->getPaymentData();
		if ( ! $payment_data ) {
			$payment_data = new PaymentDataEntity();
		}

		$payment_data->setForceRedirect( (bool) $this->is_redirect_method );
		$payment_parameters->setPaymentData( $payment_data );

		$language = $this->get_checkout_language();
		$payment_parameters->setLocale( $language );

		$payment_response = WC_Payever_Api::get_instance()
								->get_payments_api_client()
								->createPaymentV2Request( $payment_parameters );

		return $payment_response->getResponseEntity()->getRedirectUrl();
	}

	/**
	 * @return false|mixed|string
	 */
	private function get_checkout_language() {
		$language = empty( $this->plugin_settings[ WC_Payever_Helper::PAYEVER_LANGUAGES ] )
			? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ), 0, 2 ) // WPCS: input var ok, CSRF ok.
			: $this->plugin_settings[ WC_Payever_Helper::PAYEVER_LANGUAGES ];

		if ( WC_Payever_Helper::LOCALE_STORE_VALUE === $language ) {
			if ( function_exists( 'get_locale' ) ) {
				$locale = get_locale();
			}

			if ( function_exists( 'get_user_locale' ) ) {
				$locale = get_user_locale();
			}

			if ( isset( $locale ) ) {
				$language = explode( '_', $locale )[0];
			}
		}

		return $language;
	}

	/**
	 * Forms the payever payment parameters
	 *
	 * @param SubmitPaymentRequest|CreatePaymentRequest|CreatePaymentV2Request $payment_parameters
	 * @param $order_id
	 *
	 * @return SubmitPaymentRequest|CreatePaymentRequest|CreatePaymentV2Request
	 */
	public function build_payment_parameters( $order_id, RequestEntity $payment_parameters ) {
		$customer_order = new WC_Order( $order_id );
		$payever_order  = new WC_Payever_Order( $customer_order );
		$products       = $this->get_order_products( $customer_order );
		$order_total    = $customer_order->get_total();

		// Calculate fees
		$payever_fee = 0;
		$fee_name    = __( 'payever Fee', 'payever-woocommerce-gateway' );

		foreach ( $customer_order->get_fees() as $order_item_fee ) {
			$payever_item = new WC_Payever_Order_Item( $order_item_fee );
			if ( $fee_name === $payever_item->get_name() ) {
				$payever_fee += ( $payever_item->get_total() + $payever_item->get_total_tax() );
			}
		}

		$order_id = strval( $order_id );
		if ( strlen( $order_id ) < 4 ) {
			$order_id = str_repeat( '0', 4 - strlen( $order_id ) ) . $order_id;
		}

		// set order data
		$payment_parameters
			->setAmount( apply_filters( 'pe_format', $order_total - $payever_fee ) )
			->setFee( apply_filters( 'pe_format', $payever_order->get_shipping_tax() ) )
			->setOrderId( $order_id )
			->setCurrency( $payever_order->get_currency() )
			->setPaymentMethod( $this->settings['method_code'] )
			->setCart( wp_json_encode( $products ) );

		if ( array_key_exists( 'variant_id', $this->settings ) ) {
			$payment_parameters->setVariantId( $this->settings['variant_id'] );
		}

		if ( $payever_order->has_shipping_address() ) {
			$this->set_shipping_address( $payment_parameters, $payever_order );
		}
		$this->set_billing_address( $payment_parameters, $payever_order );
		$payment_parameters
			->setSuccessUrl( $this->generate_callback_url( 'success' ) )
			->setFailureUrl( $this->generate_callback_url( 'error' ) )
			->setCancelUrl( $this->generate_callback_url( 'cancel' ) )
			->setNoticeUrl( $this->generate_callback_url( 'notice' ) )
			->setPendingUrl( $this->generate_callback_url( 'processing' ) );

		return $payment_parameters;
	}

	/**
	 * @param RequestEntity $payment_parameters
	 * @param WC_Payever_Order $payever_order
	 *
	 * @return $this
	 */
	private function set_shipping_address( $payment_parameters, $payever_order ) {
		$shipping_address = new CustomerAddressEntity();
		$customer_address = $payever_order->get_shipping_address_array();
		$base_location    = wc_get_base_location();
		$country          = $base_location['country'];
		$billing_country  = $payever_order->get_billing_country();

		if ( ! empty( $billing_country ) ) {
			$country = $billing_country;
		}

		$shipping_address
			->setFirstName( $customer_address['first_name'] )
			->setLastName( $customer_address['last_name'] )
			->setCity( $customer_address['city'] )
			->setZip( $customer_address['postcode'] )
			->setStreet( trim( $customer_address['address_1'] . ' ' . $customer_address['address_2'] ) )
			->setCountry( $country );
		$state = $customer_address['state'];

		if ( ! empty( $state ) ) {
			$states = WC()->countries->get_states( $country );
			$shipping_address->setRegion( isset( $states[ $state ] ) ? $states[ $state ] : $state );
		}
		$payment_parameters->setShippingAddress( $shipping_address );

		return $this;
	}

	/**
	 * @param RequestEntity $payment_parameters
	 * @param WC_Payever_Order $payever_order
	 *
	 * @return $this
	 */
	private function set_billing_address( $payment_parameters, $payever_order ) {
		$billing_address = $payever_order->get_billing_address_array();
		$street          = trim( $billing_address['address_1'] . ' ' . $billing_address['address_2'] );
		$payment_parameters
			->setPluginVersion( WC_PAYEVER_PLUGIN_VERSION )
			->setEmail( $billing_address['email'] )
			->setPhone( $billing_address['phone'] );

		if ( $payment_parameters instanceof CreatePaymentV2Request ) {
			$billing_address_entity = new CustomerAddressEntity();
			$billing_address_entity
				->setFirstName( $billing_address['first_name'] )
				->setLastName( $billing_address['last_name'] )
				->setCity( $billing_address['city'] )
				->setZip( $billing_address['postcode'] )
				->setStreet( $street )
				->setCountry( $billing_address['country'] );

			$state = $billing_address['state'];
			if ( ! empty( $state ) ) {
				$states = WC()->countries->get_states( $billing_address['country'] );
				$billing_address_entity->setRegion( isset( $states[ $state ] ) ? $states[ $state ] : $state );
			}

			$channel_entity = new ChannelEntity();
			$channel_entity
				->setName( ChannelSet::CHANNEL_WOOCOMMERCE )
				->setSource( get_bloginfo( 'version' ) )
				->setType( 'ecommerce' );

			$payment_data = new PaymentDataEntity();
			if ( ! empty( $billing_address['company'] ) ) {
				$payment_data->setOrganizationName( $billing_address['company'] );
			}

			$payment_parameters->setBillingAddress( $billing_address_entity );
			$payment_parameters->setChannel( $channel_entity );
			$payment_parameters->setPaymentData( $payment_data );

			return $this;
		}

		$payment_parameters
			->setFirstName( $billing_address['first_name'] )
			->setLastName( $billing_address['last_name'] )
			->setCity( $billing_address['city'] )
			->setZip( $billing_address['postcode'] )
			->setStreet( $street )
			->setCountry( $billing_address['country'] );

		return $this;
	}

	/**
	 * @param string $type
	 * @param string|null $payment_id
	 * @param array $params
	 *
	 * @return string
	 */
	private function generate_callback_url( $type, $payment_id = null, $params = array() ) {
		$params['type'] = $type;
		if ( 'cancel' !== $type && 'finish' !== $type ) {
			$params['paymentId'] = $payment_id ?: '--PAYMENT-ID--';
		}

		return add_query_arg(
			$params,
			WC()->api_request_url( strtolower( get_class( $this ) ) )
		);
	}

	/**
	 * Calls from the hook "woocommerce_order_details_after_order_table"
	 * To display the customer notes for the order in the success page
	 *
	 * @param WC_Order $order
	 *
	 * @return void
	 */
	public static function display_transaction_info( $order ) {
		$payever_payments = array_keys( get_option( WC_Payever_Helper::PAYEVER_ACTIVE_PAYMENTS ) );
		$payment_method   = WC_Payever_Helper::instance()->get_payment_method( $order );
		$customer_note    = WC_Payever_Helper::instance()->get_customer_note( $order );
		if ( in_array( $payment_method, $payever_payments ) && $customer_note ) {
			esc_html_e( wpautop( '<h2>' . __( 'payever transaction details', 'payever-woocommerce-gateway' ) . '</h2>' ) );
			esc_html_e( wpautop( wptexturize( $customer_note ) ) );
		}
	}

	/**
	 * Calls from the hook "woocommerce_order_items_table"
	 * To align the customer notes in the order success page
	 *
	 * @param WC_Order $order
	 *
	 * @return void
	 */
	public static function align_transaction_info( $order ) {
		$payever_payments = array_keys( get_option( WC_Payever_Helper::PAYEVER_ACTIVE_PAYMENTS ) );
		$payment_method   = WC_Payever_Helper::instance()->get_payment_method( $order );
		$customer_note    = WC_Payever_Helper::instance()->get_customer_note( $order );
		if ( in_array( $payment_method, $payever_payments ) && $customer_note ) {
			$order->set_customer_note( wpautop( $customer_note ) );
		}
	}

	/**
	 * @return WC_Payever_Order_Total
	 * @codeCoverageIgnore
	 */
	private function get_order_total_model() {
		return null === $this->order_total_model
			? $this->order_total_model = new WC_Payever_Order_Total()
			: $this->order_total_model;
	}
}
