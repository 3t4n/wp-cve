<?php

namespace FKWCS\Gateway\Stripe;

use WC_Payment_Gateway;
use WC_AJAX;

#[\AllowDynamicProperties]
abstract class Abstract_Payment_Gateway extends WC_Payment_Gateway {
	private $client = null;
	protected $retry_interval = 1;

	protected $test_mode = '';
	protected $keys = [];
	protected $test_pub_key = '';
	protected $test_secret_key = '';
	protected $live_pub_key = '';
	protected $live_secret_key = '';
	protected $client_secret = '';
	protected $client_pub_key = '';
	protected $debug = false;
	protected $inline_cc = true;
	protected $allowed_cards = [];
	public $enable_saved_cards = false;
	public $refund_supported = false;
	public $payment_method_types = 'card';

	/**
	 * Construct
	 */
	public function __construct() {
		$this->set_api_keys();
		$this->core_hooks();
		$this->init();
		$this->filter_hooks();

	}

	/**
	 * Set API Keys
	 *
	 * @return void
	 */
	protected function set_api_keys() {
		$this->test_mode       = get_option( 'fkwcs_mode', 'test' );
		$this->test_pub_key    = get_option( 'fkwcs_test_pub_key', '' );
		$this->test_secret_key = get_option( 'fkwcs_test_secret_key', '' );
		$this->live_pub_key    = get_option( 'fkwcs_pub_key', '' );
		$this->live_secret_key = get_option( 'fkwcs_secret_key', '' );
		$this->debug           = 'yes' === get_option( 'fkwcs_debug_log', 'no' );
		Helper::$log_enabled   = $this->debug;

		if ( 'test' === $this->test_mode ) {
			$this->client_secret  = $this->test_secret_key;
			$this->client_pub_key = $this->test_pub_key;
		} else {
			$this->client_secret  = $this->live_secret_key;
			$this->client_pub_key = $this->live_pub_key;
		}

		$this->set_client();
	}

	/**
	 * Get saved publishable key
	 *
	 * @return mixed|string
	 */
	public function get_client_key() {
		return apply_filters( 'fkwcs_api_client_public_key', $this->client_pub_key );
	}

	/**
	 * Check if secret or publishable, any key saved
	 *
	 * @return bool
	 */
	public function is_configured() {
		if ( empty( $this->client_secret ) || empty( $this->client_pub_key ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Add hooks
	 *
	 * @return void
	 */
	protected function core_hooks() {
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_stripe_js' ] );
	}

	/**
	 * Set & init stripe core
	 * @return void
	 */
	public function set_client() {
		if ( empty( $this->client_secret ) || empty( $this->client_pub_key ) ) {
			return;
		}


		$this->client = Helper::get_new_client( $this->client_secret );
	}

	abstract protected function init();

	/**
	 * @return Client|null;
	 */
	public function get_client() {
		return $this->client;
	}

	/**
	 * Register Stripe JS
	 *
	 * @return void
	 */
	public function register_stripe_js() {
		wp_register_script( 'fkwcs-stripe-external', 'https://js.stripe.com/v3/', [], FKWCS_VERSION, true );
		wp_register_script( 'fkwcs-stripe-js', FKWCS_URL . 'assets/js/stripe-elements' . Helper::is_min_suffix() . '.js', [
			'jquery',
			'jquery-payment',
			'fkwcs-stripe-external'
		], FKWCS_VERSION, true );
	}

	/**
	 * Checks if current page supports express checkout
	 *
	 * @return boolean
	 */
	public function is_page_supported() {
		return $this->is_product() || is_cart() || is_checkout() || isset( $_GET['pay_for_order'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Helper method to create stripe friendly locale from the wordpress locale
	 *
	 * @param string $wc_locale
	 *
	 * @return string
	 */
	public static function convert_wc_locale_to_stripe_locale( $wc_locale ) {
		// List copied from: https://stripe.com/docs/js/appendix/supported_locales.
		$supported = [
			'ar',     // Arabic.
			'bg',     // Bulgarian (Bulgaria).
			'cs',     // Czech (Czech Republic).
			'da',     // Danish.
			'de',     // German (Germany).
			'el',     // Greek (Greece).
			'en',     // English.
			'en-GB',  // English (United Kingdom).
			'es',     // Spanish (Spain).
			'es-419', // Spanish (Latin America).
			'et',     // Estonian (Estonia).
			'fi',     // Finnish (Finland).
			'fr',     // French (France).
			'fr-CA',  // French (Canada).
			'he',     // Hebrew (Israel).
			'hu',     // Hungarian (Hungary).
			'id',     // Indonesian (Indonesia).
			'it',     // Italian (Italy).
			'ja',     // Japanese.
			'lt',     // Lithuanian (Lithuania).
			'lv',     // Latvian (Latvia).
			'ms',     // Malay (Malaysia).
			'mt',     // Maltese (Malta).
			'nb',     // Norwegian Bokmål.
			'nl',     // Dutch (Netherlands).
			'pl',     // Polish (Poland).
			'pt-BR',  // Portuguese (Brazil).
			'pt',     // Portuguese (Brazil).
			'ro',     // Romanian (Romania).
			'ru',     // Russian (Russia).
			'sk',     // Slovak (Slovakia).
			'sl',     // Slovenian (Slovenia).
			'sv',     // Swedish (Sweden).
			'th',     // Thai.
			'tr',     // Turkish (Turkey).
			'zh',     // Chinese Simplified (China).
			'zh-HK',  // Chinese Traditional (Hong Kong).
			'zh-TW',  // Chinese Traditional (Taiwan).
		];

		// Stripe uses '-' instead of '_' (used in WordPress).
		$locale = str_replace( '_', '-', $wc_locale );

		if ( in_array( $locale, $supported, true ) ) {
			return $locale;
		}

		// The plugin has been fully translated for Spanish (Ecuador), Spanish (Mexico), and
		// Spanish(Venezuela), and partially (88% at 2021-05-14) for Spanish (Colombia).
		// We need to map these locales to Stripe's Spanish (Latin America) 'es-419' locale.
		// This list should be updated if more localized versions of Latin American Spanish are
		// made available.
		$lowercase_locale                  = strtolower( $wc_locale );
		$translated_latin_american_locales = [
			'es_co', // Spanish (Colombia).
			'es_ec', // Spanish (Ecuador).
			'es_mx', // Spanish (Mexico).
			'es_ve', // Spanish (Venezuela).
		];
		if ( in_array( $lowercase_locale, $translated_latin_american_locales, true ) ) {
			return 'es-419';
		}

		// Finally, we check if the "base locale" is available.
		$base_locale = substr( $wc_locale, 0, 2 );
		if ( in_array( $base_locale, $supported, true ) ) {
			return $base_locale;
		}

		// Default to 'auto' so Stripe.js uses the browser locale.
		return 'auto';
	}

	/**
	 * Enqueue Stripe assets and include hooks if page supported
	 *
	 * @return void
	 */
	public function enqueue_stripe_js() {
		if ( ! $this->is_page_supported() || ( is_order_received_page() ) ) {
			return;
		}

		/** If Stripe is not enabled bail */
		if ( false === $this->is_available() ) {
			return;
		}

		/** If no SSL bail */
		if ( 'test' !== $this->test_mode && ! is_ssl() ) {

			return;
		}

		wp_enqueue_script( 'fkwcs-stripe-external' );
		$this->tokenization_script();

		wp_enqueue_script( 'fkwcs-stripe-js' );
		wp_localize_script( 'fkwcs-stripe-js', 'fkwcs_data', $this->localize_data() );
		add_action( 'wp_head', [ $this, 'enqueue_cc_css' ] );
		do_action( 'fkwcs_core_element_js_enqueued' );
	}

	/**
	 * Localize important data
	 *
	 * @return mixed|null
	 */
	protected function localize_data() {
		return apply_filters( 'fkwcs_localized_data', [
			'locale'                  => $this->convert_wc_locale_to_stripe_locale( get_locale() ),
			'is_checkout'             => $this->is_checkout() ? 'yes' : 'no',
			'is_product_page'         => is_product() || wc_post_content_has_shortcode( 'product_page' ),
			'is_cart'                 => is_cart(),
			'pub_key'                 => $this->get_client_key(),
			'admin_ajax'              => admin_url( 'admin-ajax.php' ),
			'fkwcs_nonce'             => wp_create_nonce( 'fkwcs_nonce' ),
			'shipping_required'       => wc_bool_to_string( WC()->cart->needs_shipping() ),
			'is_ssl'                  => is_ssl(),
			'mode'                    => $this->test_mode,
			'js_nonce'                => wp_create_nonce( 'fkwcs_js_nonce' ),
			'stripe_localized'        => Helper::get_localized_messages(),
			'default_cards'           => [
				'mastercard' => __( 'MasterCard', 'funnelkit-stripe-woo-payment-gateway' ),
				'visa'       => __( 'Visa', 'funnelkit-stripe-woo-payment-gateway' ),
				'amex'       => __( 'American Express', 'funnelkit-stripe-woo-payment-gateway' ),
				'discover'   => __( 'Discover', 'funnelkit-stripe-woo-payment-gateway' ),
				'jcb'        => __( 'JCB', 'funnelkit-stripe-woo-payment-gateway' ),
				'diners'     => __( 'Diners Club', 'funnelkit-stripe-woo-payment-gateway' ),
				'unionpay'   => __( 'UnionPay', 'funnelkit-stripe-woo-payment-gateway' ),
			],
			'not_allowed_string'      => __( 'is not allowed', 'funnelkit-stripe-woo-payment-gateway' ),
			'get_home_url'            => get_home_url(),
			'current_user_billing'    => $this->get_current_user_billing_details(),
			'sepa_options'            => [
				'supportedCountries' => [ 'SEPA' ],
				'placeholderCountry' => WC()->countries->get_base_country(),
				'style'              => [
					'base' => [
						'fontSize' => '15px',
						'color'    => '#32325d',
					],
				],
			],
			'empty_sepa_iban_message' => __( 'Please enter a IBAN number to proceed.', 'funnelkit-stripe-woo-payment-gateway' ),
			'empty_bank_message'      => __( 'Please select a bank to proceed.', 'funnelkit-stripe-woo-payment-gateway' ),
			'wc_ajax_endpoint'        => WC_AJAX::get_endpoint( '%%endpoint%%' ),
			'assets_url'              => FKWCS_URL . 'assets/',
			'icons'                   => [
				'applepay_gray'  => FKWCS_URL . 'assets/icons/apple_pay_gray.svg',
				'applepay_light' => FKWCS_URL . 'assets/icons/apple_pay_light.svg',
				'gpay_light'     => FKWCS_URL . 'assets/icons/gpay_light.svg',
				'gpay_gray'      => FKWCS_URL . 'assets/icons/gpay_gray.svg',
			],
			'is_change_payment_page'  => isset( $_GET['change_payment_method'] ) ? 'yes' : 'no', //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			'is_add_payment_page'     => is_wc_endpoint_url( 'add-payment-method' ) ? 'yes' : 'no',
			'is_pay_for_order_page'   => is_wc_endpoint_url( 'order-pay' ) ? 'yes' : 'no',
			'debug_log'               => ! empty( get_option( 'fkwcs_debug_log' ) ) ? get_option( 'fkwcs_debug_log' ) : 'no',
			'debug_msg'               => __( 'Stripe enabled Payment Request is not available in this browser', 'funnelkit-stripe-woo-payment-gateway' ),
			'common_style'            => [
				'base'    => [
					'padding'         => '6px',
					'iconColor'       => '#c4f0ff',
					'color'           => '#32325d',
					'fontWeight'      => '500',
					'fontFamily'      => 'Roboto, Open Sans, Segoe UI, sans-serif',
					'fontSize'        => '15px',
					'fontSmoothing'   => 'antialiased',
					'::placeholder'   => [
						'color'           => '#aab7c4',
						'backgroundColor' => '#fff',
					],
					'backgroundColor' => '#fff',
				],
				'invalid' => [ 'iconColor' => 'red', 'color' => 'red' ],
			],
			'inline_style'            => [
				'base' => [
					'color'    => '#32325d',
					'fontSize' => '14px',
				]
			]
		] );
	}

	/**
	 * Get current user billing details
	 *
	 * @return mixed|void|null
	 */
	public function get_current_user_billing_details() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$user    = wp_get_current_user();
		$details = [];
		if ( ! empty( $user->display_name ) ) {
			$details['name'] = $user->display_name;
		}

		if ( ! empty( $user->user_email ) ) {
			$details['email'] = $user->user_email;
		}

		return apply_filters( 'fkwcs_current_user_billing_details', $details, get_current_user_id() );
	}

	/**
	 * Clean/Trim statement descriptor as per stripe requirement.
	 *
	 * @param string $statement_descriptor User Input.
	 *
	 * @return string optimized statement descriptor.
	 */
	public function clean_statement_descriptor( $statement_descriptor = '' ) {
		$disallowed_characters = [ '<', '>', '\\', '*', '"', "'", '/', '(', ')', '{', '}' ];

		/** Strip any tags */
		$statement_descriptor = wp_strip_all_tags( $statement_descriptor );

		/** Strip any HTML entities */
		// Props https://stackoverflow.com/questions/657643/how-to-remove-html-special-chars .
		$statement_descriptor = preg_replace( '/&#?[a-z0-9]{2,8};/i', '', $statement_descriptor );

		/** Next, remove any remaining disallowed characters */
		$statement_descriptor = str_replace( $disallowed_characters, '', $statement_descriptor );

		/** Trim any whitespace at the ends and limit to 22 characters */
		$statement_descriptor = substr( trim( $statement_descriptor ), 0, 22 );

		return $statement_descriptor;
	}

	/**
	 * Controller method to process full OR partial refunds
	 *
	 * @param integer $order_id
	 * @param string $amount
	 * @param string $reason
	 *
	 * @return bool|void|\WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		if ( 0 >= $amount ) {
			return false;
		}

		try {
			$order  = wc_get_order( $order_id );
			$intent = Helper::get_meta( $order, '_fkwcs_intent_id' );

			if ( empty( $intent ) ) {
				$intent = Helper::get_meta( $order, '_stripe_intent_id' );

			} else {
				$intent = $intent['id'];
			}

			$order->update_meta_data( '_fkwcs_webhook_lock', time() );
			$order->save_meta_data();

			$response        = $this->create_refund_request( $order, $amount, $reason, $intent );
			$refund_response = $response['success'] ? $response['data'] : false;

			$user             = wp_get_current_user();
			$refund_user_info = '';
			if ( $user instanceof \WP_User ) {
				$refund_user_info = '<br>' . __( 'Refund by user : ', 'funnelkit-stripe-woo-payment-gateway' ) . $user->display_name . '(#' . $user->ID . ')';
			}

			if ( $refund_response ) {
				if ( isset( $refund_response->balance_transaction ) ) {
					Helper::update_balance( $order, $refund_response->balance_transaction, true );
				}

				$refund_time = gmdate( 'Y-m-d H:i:s', time() );
				$order->update_meta_data( '_fkwcs_refund_id', $refund_response->id );
				$order->delete_meta_data( '_fkwcs_webhook_lock' );
				$order->save_meta_data();
				$order->add_order_note( __( 'Reason : ', 'funnelkit-stripe-woo-payment-gateway' ) . $reason . '.<br>' . __( 'Amount : ', 'funnelkit-stripe-woo-payment-gateway' ) . get_woocommerce_currency_symbol() . $amount . '.<br>' . __( 'Status : ', 'funnelkit-stripe-woo-payment-gateway' ) . ucfirst( $refund_response->status ) . ' [ ' . $refund_time . ' ] ' . ( is_null( $refund_response->id ) ? '' : '<br>' . __( 'Transaction ID : ', 'funnelkit-stripe-woo-payment-gateway' ) . $refund_response->id ) . $refund_user_info );
				Helper::log( __( 'Refund initiated: ', 'funnelkit-stripe-woo-payment-gateway' ) . __( 'Reason : ', 'funnelkit-stripe-woo-payment-gateway' ) . $reason . __( 'Amount : ', 'funnelkit-stripe-woo-payment-gateway' ) . get_woocommerce_currency_symbol() . $amount . __( 'Status : ', 'funnelkit-stripe-woo-payment-gateway' ) . ucfirst( $refund_response->status ) . ' [ ' . $refund_time . ' ] ' . ( is_null( $refund_response->id ) ? '' : __( 'Transaction ID : ', 'funnelkit-stripe-woo-payment-gateway' ) . $refund_response->id ) );

				if ( 'succeeded' === $refund_response->status ) {
					return true;
				} else {
					return new \WP_Error( 'error', __( 'Your refund process is ', 'funnelkit-stripe-woo-payment-gateway' ) . ucfirst( $refund_response->status ) );
				}
			} else {
				$order->add_order_note( __( 'Reason : ', 'funnelkit-stripe-woo-payment-gateway' ) . $reason . '.<br>' . __( 'Amount : ', 'funnelkit-stripe-woo-payment-gateway' ) . get_woocommerce_currency_symbol() . $amount . '.<br>' . __( ' Status : Failed ', 'funnelkit-stripe-woo-payment-gateway' ) . $refund_user_info );
				Helper::log( $response['message'] );

				return new \WP_Error( 'error', $response['message'] );
			}
		} catch ( \Exception $e ) {
			Helper::log( $e->getMessage() );
		}
	}


	/**
	 * Handle API response
	 *
	 * @param $response
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function handle_client_response( $response, $throw_exception = true ) {
		if ( true === wc_string_to_bool( $response['success'] ) ) {
			return $response['data'];
		}

		$localized_message = Helper::get_localized_error_message( $response );
		if ( $throw_exception ) {
			throw new \Exception( $localized_message ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		} else {
			return (object) $response;
		}
	}


	/**
	 * Validates minimum order amount requirement
	 *
	 * @param $order
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function validate_minimum_order_amount( $order ) {
		if ( $order->get_total() * 100 < Helper::get_minimum_amount() ) {
			/* translators: 1) amount (including currency symbol) */
			throw new \Exception( sprintf( __( 'Sorry, the minimum allowed order total is %1$s to use this payment method.', 'funnelkit-stripe-woo-payment-gateway' ), wc_price( Helper::get_minimum_amount() / 100 ) ) ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}




	/**
	 * Create payment intent using source
	 *
	 * @param $order
	 * @param $prepared_source
	 * @param $data
	 *
	 * @return mixed|null
	 * @throws \Exception
	 */
	public function make_payment_by_source( $order, $prepared_source, $data ) {
		$intent_data = [];
		if ( apply_filters( 'fkwcs_execute_payment_intent', true, $order, $prepared_source, $data ) ) {
			$stripe_api  = $this->get_client();
			$response    = $stripe_api->payment_intents( 'create', [ $data ] );
			$intent_data = $this->handle_client_response( $response );
		}

		return apply_filters( 'fkwcs_execute_payment_intent_data', $intent_data, $order, $prepared_source, $data );
	}

	/**
	 * @param $order \WC_Order
	 * @param $prepared_source
	 * @param $data
	 *
	 * @return mixed|null
	 * @throws \Exception
	 */
	public function make_payment( $order, $prepared_source, $data ) {
		$intent_data = [];
		if ( apply_filters( 'fkwcs_execute_payment_intent', true, $order, $prepared_source, $data ) ) {
			$idempotency_key = $prepared_source->source . '_' . $order->get_order_key();
			$intent_data     = $this->get_payment_intent( $order, $idempotency_key, $data );

		}

		return apply_filters( 'fkwcs_execute_payment_intent_data', $intent_data, $order, $prepared_source, $data );
	}

	/**
	 * Get payment intent from order meta
	 *
	 * @param \WC_Order $order
	 * @param $idempotency_key
	 * @param $args
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function get_payment_intent( $order, $idempotency_key, $args ) {
		$stripe_api    = $this->get_client();
		$intent_secret = Helper::get_meta( $order, '_fkwcs_intent_id' );
		$retry_count   = Helper::get_meta( $order, '_fkwcs_retry_count' );
		if ( ! empty( $intent_secret ) ) {
			$secret   = $intent_secret;
			$response = $stripe_api->payment_intents( 'retrieve', [ $secret['id'] ] );
			if ( $response['success'] && ( 'succeeded' === $response['data']->status || 'success' === $response['data']->status ) ) {
				/**
				 * this code here confirms if we have the intent in the order meta and that intent is succeeded
				 * then we need to go ahead and mark the order complete in WooCommerce
				 */
				$this->save_payment_method( $order, $response['data'] );
				$redirect_url = $this->process_final_order( end( $response['data']->charges->data ), $order->get_id() );
				wp_send_json( apply_filters( 'fkwcs_card_payment_return_intent_data', [
					'result'   => 'success',
					'redirect' => $redirect_url
				] ) );
			}
		}

		if ( empty( $args['customer'] ) ) {
			unset( $args['customer'] );
		}

		if ( ! empty( $retry_count ) ) {
			$idempotency_key = $idempotency_key . '_' . $retry_count;

		}
		$args = apply_filters( 'fkwcs_payment_intent_data', $args, $order );

		$args     = [
			[ $args ],
			[ 'idempotency_key' => $idempotency_key ],
		];
		$response = $stripe_api->payment_intents( 'create', $args );
		$intent   = $this->handle_client_response( $response );

		if ( empty( $retry_count ) ) {
			$order->update_meta_data( '_fkwcs_retry_count', 1 );
		} else {
			$order->update_meta_data( '_fkwcs_retry_count', absint( $retry_count ) + 1 );

		}
		$this->save_intent_to_order( $order, $intent );


		return $intent;
	}


	/**
	 * Get/Retrieve stripe customer ID if exists
	 *
	 * @param \WC_Order $order current woocommerce order.
	 *
	 * @return mixed customer id
	 */
	public function get_customer_id( $order = false, $is_recurrence = false ) {
		$user = wp_get_current_user();

		$user_id              = ( $user->ID && $user->ID > 0 ) ? $user->ID : false;
		$absolute_customer_id = null;
		if ( $order instanceof \WC_Order && 0 !== $order->get_customer_id() ) {
			$user_id = $order->get_customer_id();
		}

		/**
		 * Try to find a customer ID through the order meta
		 */
		if ( false === $user_id ) {


			if ( ! $order instanceof \WC_Order ) {
				return $absolute_customer_id;
			}
			$customer_key = '_fkwcs_customer_id';

			$customer_id = Helper::get_meta( $order, $customer_key );
			if ( ! empty( $customer_id ) ) {
				$absolute_customer_id = $customer_id;
			}
		}

		if ( empty( $absolute_customer_id ) ) {
			$customer_id = get_user_option( '_fkwcs_customer_id', $user_id );
			if ( $customer_id ) {
				$absolute_customer_id = $customer_id;
			}
		}


		/**
		 * Try and get stripe customer ID from the WooCommerce stripe
		 */
		if ( empty( $absolute_customer_id ) ) {
			$customer_id = get_user_option( '_stripe_customer_id', $user_id );
			if ( $customer_id ) {
				$absolute_customer_id = $customer_id;
			}
		}


		if ( ! $absolute_customer_id ) {

			/**
			 * Create customer using an API
			 */
			$customer = $this->create_stripe_customer( $order, $user->email );

			if ( false !== $customer ) {
				$absolute_customer_id = $customer->id;
			}
		} else {


			/**
			 * If we have the customer ID, we need to revalidate if it exist in this environment,
			 */
			$client   = $this->get_client();
			$response = $client->customers( 'retrieve', [ $absolute_customer_id ] );

			if ( false === $is_recurrence && false === $response['success'] ) {

				delete_user_option( $user_id, '_fkwcs_customer_id', false );
				delete_user_option( $user_id, '_stripe_customer_id', false );
				if ( $order instanceof \WC_Order ) {
					$order->delete_meta_data( '_fkwcs_customer_id' );
					$order->save_meta_data();

				}

				return $this->get_customer_id( $order, true );
			}
		}

		if ( $absolute_customer_id ) {
			if ( $user_id ) {
				update_user_option( $user_id, '_fkwcs_customer_id', $absolute_customer_id, false );
			}
			if ( $order instanceof \WC_Order ) {
				$order->update_meta_data( '_fkwcs_customer_id', $absolute_customer_id );
				$order->save_meta_data();

			}

			return $absolute_customer_id;
		}
	}

	/**
	 * Creates stripe customer object
	 *
	 * @param object $order woocommerce order object.
	 * @param boolean|string $user_email user email id.
	 *
	 * @return \stdClass|false
	 *
	 */
	public function create_stripe_customer( $order = false, $user_email = false ) {
		if ( $order instanceof \WC_Order ) {
			$args = [
				'description' => __( 'Customer for Order #', 'funnelkit-stripe-woo-payment-gateway' ) . $order->get_order_number(),
				'email'       => $user_email ? $user_email : $order->get_billing_email(),
				'address'     => [ // sending name and billing address to stripe to support indian exports.
					'city'        => method_exists( $order, 'get_billing_city' ) ? $order->get_billing_city() : $order->billing_city,
					'country'     => method_exists( $order, 'get_billing_country' ) ? $order->get_billing_country() : $order->billing_country,
					'line1'       => method_exists( $order, 'get_billing_address_1' ) ? $order->get_billing_address_1() : $order->billing_address_1,
					'line2'       => method_exists( $order, 'get_billing_address_2' ) ? $order->get_billing_address_2() : $order->billing_address_2,
					'postal_code' => method_exists( $order, 'get_billing_postcode' ) ? $order->get_billing_postcode() : $order->billing_postcode,
					'state'       => method_exists( $order, 'get_billing_state' ) ? $order->get_billing_state() : $order->billing_state,
				],
				'name'        => ( method_exists( $order, 'get_billing_first_name' ) ? $order->get_billing_first_name() : $order->billing_first_name ) . ' ' . ( method_exists( $order, 'get_billing_last_name' ) ? $order->get_billing_last_name() : $order->billing_last_name ),
			];
		} else {
			$user_id = get_current_user_id();

			$user               = get_user_by( 'id', $user_id );
			$billing_first_name = get_user_meta( $user->ID, 'billing_first_name', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
			$billing_last_name  = get_user_meta( $user->ID, 'billing_last_name', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta

			/** If billing first name does not exists try the user first name */
			if ( empty( $billing_first_name ) ) {
				$billing_first_name = get_user_meta( $user->ID, 'first_name', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
			}

			/** If billing last name does not exists try the user last name */
			if ( empty( $billing_last_name ) ) {
				$billing_last_name = get_user_meta( $user->ID, 'last_name', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
			}

			// translators: %1$s First name, %2$s Second name, %3$s Username.
			$description = sprintf( __( 'Name: %1$s %2$s, Username: %3$s', 'funnelkit-stripe-woo-payment-gateway' ), $billing_first_name, $billing_last_name, $user->user_login );

			$args = [
				'email'       => $user->user_email,
				'description' => $description,
			];

			$billing_full_name = trim( $billing_first_name . ' ' . $billing_last_name );
			if ( ! empty( $billing_full_name ) ) {
				$args['name'] = $billing_full_name;
			}

		}

		$args     = apply_filters( 'fkwcs_create_stripe_customer_args', $args );
		$client   = $this->get_client();
		$response = $client->customers( 'create', [ $args ] );
		$response = $response['success'] ? $response['data'] : false;
		if ( empty( $response->id ) ) {
			return false;
		}

		return $response;
	}

	/**
	 * Get Order description string
	 *
	 * @param \WC_Order $order
	 *
	 * @return string
	 */
	public function get_order_description( $order ) {


		return apply_filters( 'fkwcs_get_order_description', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) . ' - ' . __( 'Order', 'woocommerce' ) . " " . $order->get_order_number(), $order );
	}

	/**
	 * Checks conditions whether current card should be saved or not
	 *
	 * @param \WC_Order $order WooCommerce order.
	 *
	 * @return boolean
	 */
	public function should_save_card( $order ) {  //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		return true;
	}


	/**
	 * Create setup intent
	 *
	 * @param $source
	 * @param $customer_id
	 * @param $type
	 * @param $confirm
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function create_setup_intent( $source, $customer_id = '', $order = false, $type = 'card' ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
		$customer_id = ! empty( $customer_id ) ? $customer_id : $this->get_customer_id();
		$client      = $this->get_client();
		$response    = apply_filters( 'fkwcs_payment_intent_data', [
			'payment_method_types' => [ $type ],
			'payment_method'       => $source,
			'customer'             => $customer_id
		], $order, true );

		$response = $client->setup_intents( 'create', [ $response ] );
		$obj      = $this->handle_client_response( $response );

		return $obj;
	}


	/**
	 * Get intent from the order
	 *
	 * @param $order \WC_Order
	 *
	 * @return false|mixed
	 * @throws \Exception
	 */
	public function get_intent_from_order( $order ) {
		$intent = Helper::get_meta( $order, '_fkwcs_intent_id' );


		$client = $this->get_client();
		if ( ! empty( $intent ) ) {
			$response = $client->payment_intents( 'retrieve', [ $intent['id'] ] );
			$obj      = $this->handle_client_response( $response );

			return $obj;
		}

		/** The order doesn't have a payment intent, but it may have a setup intent. */
		$intent = Helper::get_meta( $order, '_fkwcs_setup_intent' );


		if ( ! empty( $intent ) ) {
			$response = $client->setup_intents( 'retrieve', [ $intent['id'] ] );
			$obj      = $this->handle_client_response( $response );

			return $obj;
		}

		return false;
	}

	/**
	 * Prepare order source for API call
	 *
	 * @param $order \WC_Order
	 *
	 * @return object
	 */
	public function prepare_order_source( $order = null ) {
		if ( ! $order instanceof \WC_Order ) {
			return (object) [
				'customer'       => false,
				'source'         => false,
				'source_object'  => false,
				'payment_method' => null,
			];
		}

		$client             = $this->get_client();
		$stripe_customer_id = $this->get_customer_id( $order );

		$stripe_source = false;
		$source_object = false;

		$source_id = Helper::get_meta( $order, '_fkwcs_source_id' );
		if ( $source_id ) {
			$stripe_source = $source_id;
			$response      = $client->payment_methods( 'retrieve', [ $source_id ] );
			$source_object = $response['success'] ? $response['data'] : false;
		} elseif ( apply_filters( 'fkwcs_stripe_use_default_customer_source', true ) ) {
			/*
			 * We can attempt to charge the customer's default source
			 * by sending empty source id.
			 */
			$stripe_source = '';
		}

		return (object) [
			'customer'       => $stripe_customer_id,
			'source'         => $stripe_source,
			'source_object'  => $source_object,
			'payment_method' => null,
		];
	}

	/**
	 * Create a SetupIntent for future payments, and saves it to the order
	 *
	 * @param $order
	 * @param $prepared_source
	 *
	 * @return mixed The client secret of the intent, used for confirmation in JS.
	 * @throws \Exception
	 */
	public function setup_intent( $order, $prepared_source ) {
		$client = $this->get_client();

		$data = [
			'payment_method'       => $prepared_source->source,
			'customer'             => $prepared_source->customer,
			'payment_method_types' => [ 'card' ],
			'usage'                => 'off_session',
		];

		$response    = $client->setup_intents( 'create', [ $data ] );
		$obj         = $this->handle_client_response( $response );
		$intent_data = [
			'id'            => $obj->id,
			'client_secret' => $obj->client_secret,
		];
		$order->update_meta_data( '_fkwcs_setup_intent', $intent_data );
		$order->save();

		return $obj;
	}

	/**
	 * Log exception or error before redirecting
	 *
	 * @param $e
	 * @param $redirect_url
	 *
	 * @return void
	 */
	protected function handle_error( $e, $redirect_url ) {
		$message = sprintf( 'PaymentIntent verification exception: %s', $e->getMessage() );
		Helper::log( $message );

		/** `is_ajax` is only used for PI error reporting, a response is not expected */
		if ( isset( $_GET['is_ajax'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			exit;
		}

		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Request for charge contains the metadata for the intent
	 *
	 * @param $order \WC_Order
	 * @param $prepared_source
	 * @param $amount
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function create_and_confirm_intent_for_off_session( $order, $prepared_source ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.Variables.VariableAnalysis.UnusedParameter


		$request = [
			'payment_method'       => $prepared_source->source,
			'payment_method_types' => [ $this->payment_method_types ],
			'amount'               => Helper::get_stripe_amount( $order->get_total(), strtolower( $order->get_currency() ) ),
			'currency'             => strtolower( $order->get_currency() ),
			'description'          => $this->get_order_description( $order ),
			'customer'             => $prepared_source->customer,
			'off_session'          => 'true',
			'confirm'              => 'true',
			'confirmation_method'  => 'automatic',
		];


		if ( false === \in_array( 'card', $request['payment_method_types'], true ) ) {
			$request['statement_descriptor'] = $this->clean_statement_descriptor( Helper::get_gateway_descriptor() );
		}
		if ( empty( $prepared_source->source ) ) {
			unset( $request['payment_method'] );
		}
		if ( isset( $prepared_source->customer ) ) {
			$request['customer'] = $prepared_source->customer;
		}
		$request['metadata'] = $this->add_metadata( $order );
		$request             = apply_filters( 'fkwcs_payment_intent_data', $request, $order );
		$client              = $this->get_client();
		$response            = $client->payment_intents( 'create', [ $request ] );

		return (object) $response;
	}

	/**
	 * Checks if authentication required for payment in the response
	 *
	 * @param $response
	 *
	 * @return bool
	 */
	public function is_authentication_required_for_payment( $response ) {
		return ( ! empty( $response->error ) && 'authentication_required' === $response->error->code ) || ( ! empty( $response->last_payment_error ) && 'authentication_required' === $response->last_payment_error->code );
	}

	/**
	 * @param $source_object
	 * @param $error
	 *
	 * @return bool
	 */
	public function need_update_idempotency_key( $source_object, $error ) {
		return ( $error && 1 < $this->retry_interval && ! empty( $source_object ) && 'chargeable' === $source_object->status && $this->is_same_idempotency_error( $error ) );
	}

	/**
	 * Checks if any error in the given argument
	 *
	 * @param $error
	 *
	 * @return bool
	 */
	public function is_no_such_source_error( $error ) {
		return ( $error && ( 'invalid_request_error' === $error->type || 'payment_method' === $error->type ) && preg_match( '/No such (source|PaymentMethod)/i', $error->message ) );
	}

	/**
	 * Checks if source missing error
	 *
	 * @param $error
	 *
	 * @return bool
	 */
	public function is_no_linked_source_error( $error ) {
		return ( $error && ( 'invalid_request_error' === $error->type || 'payment_method' === $error->type ) && preg_match( '/does not have a linked source with ID/i', $error->message ) );
	}

	/**
	 * Checks to see if error is of same idempotency key
	 * Error due to retries with different parameters
	 *
	 * @param $error
	 *
	 * @return bool
	 */
	public function is_same_idempotency_error( $error ) {
		return ( $error && 'idempotency_error' === $error->type && preg_match( '/Keys for idempotent requests can only be used with the same parameters they were first used with./i', $error->message ) );
	}

	/**
	 * Locks an order for payment intent processing for 5 minutes.
	 *
	 * @param \WC_Order $order The order that is being paid.
	 * @param \stdClass $intent The intent that is being processed.
	 *
	 * @return bool            A flag that indicates whether the order is already locked.
	 */
	public function lock_order_payment( $order, $intent = null ) {
		$order_id       = $order->get_id();
		$transient_name = 'fkwcs_stripe_processing_intent_' . $order_id;
		$processing     = get_transient( $transient_name );

		/** Block the process if the same intent is already being handled */
		if ( '-1' === $processing || ( isset( $intent->id ) && $processing === $intent->id ) ) {
			return true;
		}

		/** Save the new intent as a transient, eventually overwriting another one */
		set_transient( $transient_name, empty( $intent ) ? '-1' : $intent->id, 5 * MINUTE_IN_SECONDS );

		return false;
	}

	/**
	 * Unlocks an order for processing by payment intents.
	 *
	 * @param \WC_Order $order The order that is being unlocked.
	 */
	public function unlock_order_payment( $order ) {
		$order_id = $order->get_id();
		delete_transient( 'fkwcs_stripe_processing_intent_' . $order_id );
	}

	/**
	 * Save the intent data in the order
	 *
	 * @param $order \WC_Order
	 * @param $intent
	 *
	 * @return void
	 */
	public function save_intent_to_order( $order, $intent ) {
		if ( 'payment_intent' === $intent->object ) {
			Helper::add_payment_intent_to_order( $intent, $order );
		} elseif ( 'setup_intent' === $intent->object ) {
			$order->update_meta_data( '_fkwcs_setup_intent', $intent->id );
		}
		$charge = $this->get_latest_charge_from_intent( $intent );

		if ( isset( $charge->payment_method_details->card->mandate ) ) {
			$mandate_id = $charge->payment_method_details->card->mandate;
			$order->update_meta_data( '_stripe_mandate_id', $mandate_id );
		}
		if ( is_callable( [ $order, 'save_meta_data' ] ) ) {
			$order->save_meta_data();
		}
	}

	/**
	 * Checks if a retryable error
	 *
	 * @param $error
	 *
	 * @return bool
	 */
	public function is_retryable_error( $error ) {
		if ( isset( $error->code ) && 'payment_intent_mandate_invalid' === $error->code ) {
			return false;
		}

		return ( 'invalid_request_error' === $error->type || 'idempotency_error' === $error->type || 'rate_limit_error' === $error->type || 'api_connection_error' === $error->type || 'api_error' === $error->type );
	}

	/**
	 * Checks if a current page is a product page
	 *
	 * @return bool
	 */
	public function is_product() {
		return is_product() || wc_post_content_has_shortcode( 'product_page' );
	}

	/**
	 * Checks if a current page is a cart page
	 *
	 * @return bool
	 */
	public function is_cart() {
		return is_cart() || wc_post_content_has_shortcode( 'woocommerce_cart' );
	}

	/**
	 * Checks if a current page is a checkout page
	 *
	 * @return bool
	 */
	public function is_checkout() {
		return is_checkout() || wc_post_content_has_shortcode( 'woocommerce_checkout' );
	}

	/**
	 * Prepare source OR payment method
	 *
	 * @param $order
	 * @param $force_save_source
	 *
	 * @return object|void
	 */
	public function prepare_source( $order, $force_save_source = false ) {
		$customer_id   = $this->get_customer_id( $order );
		$source_object = '';
		$source_id     = '';
		$stripe_api    = $this->get_client();

		/** New CC info was entered and we have a new source to process */
		if ( ! empty( $_POST['fkwcs_source'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$stripe_source = wc_clean( wp_unslash( $_POST['fkwcs_source'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$response      = $stripe_api->payment_methods( 'retrieve', [ $stripe_source ] );
			$source_object = $response['success'] ? $response['data'] : false;
			if ( ! $source_object ) {
				return;
			}
			$source_id = $source_object->id;
			if ( true === $force_save_source ) {
				// Attach Source to customer
				$response = $stripe_api->payment_methods( 'attach', [ $source_id, [ 'customer' => $customer_id ] ] );
				if ( $response['success'] ) {
					$source_object = $response['data'];
				} else {
					throw new \Exception( $response['message'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}

		/** Get payment source id by token id */

		if ( empty( $source_id ) && ! empty( $_POST[ 'wc-' . $this->id . '-payment-token' ] ) && 'new' !== $_POST[ 'wc-' . $this->id . '-payment-token' ] ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$token = $this->find_saved_token();

			$source_id = ( $token ) ? $token->get_token() : '';
		}

		if ( empty( $source_object ) ) {
			$response      = $stripe_api->payment_methods( 'retrieve', [ $source_id ] );
			$source_object = $response['success'] ? $response['data'] : false;
		}
		if ( ! empty( $source_object ) && empty( $source_object->customer ) && ! empty( $customer_id ) ) {
			$source_object->customer = $customer_id;
		}

		return Helper::prepare_payment_method( $source_object, false );
	}

	/**
	 * Setup refund request.
	 *
	 * @param object $order order.
	 * @param string $amount refund amount.
	 * @param string $reason reason of refund.
	 * @param string $intent_secret_id secret key.
	 *
	 * @return array|\WP_Error
	 */
	public function create_refund_request( $order, $amount, $reason, $intent_secret_id ) {
		$response = $this->get_client()->payment_intents( 'retrieve', [ $intent_secret_id ] );
		$status   = $response['success'] && isset( $response['data']->charges->data[0]->captured ) ? $response['data']->charges->data[0]->captured : false;
		if ( ! $status ) {
			Helper::log( 'Un captured Amount cannot be refunded' );

			return new \WP_Error( 'error', __( 'Un captured Amount cannot be refunded', 'funnelkit-stripe-woo-payment-gateway' ) );
		}

		$intent_response = $response['data'];
		$currency        = $intent_response->currency;
		$client_details  = $this->get_client()->get_clients_details();
		$refund_params   = apply_filters( 'fkwcs_refund_request_args', [
			'payment_intent' => $intent_secret_id,
			'amount'         => Helper::get_stripe_amount( $amount, $currency ),
			'reason'         => 'requested_by_customer',
			'metadata'       => [
				'order_id'          => $order->get_order_number(),
				'customer_ip'       => $client_details['ip'],
				'agent'             => $client_details['agent'],
				'referer'           => $client_details['referer'],
				'reason_for_refund' => $reason,
			],
		] );

		return $this->execute_refunds( $refund_params );
	}

	/**
	 * Execute refunds
	 *
	 * @param array $params a full config to support API call for the refunds
	 * https://stripe.com/docs/api/refunds/create
	 *
	 * @return array
	 */
	public function execute_refunds( $params ) {
		return $this->get_client()->refunds( 'create', [ $params ] );
	}

	/**
	 * Get the transaction URL linked to Stripe dashboard
	 *
	 * @param $order
	 *
	 * @return string
	 */
	public function get_transaction_url( $order ) {
		if ( 'test' === $this->test_mode ) {
			$this->view_transaction_url = 'https://dashboard.stripe.com/test/payments/%s';
		} else {
			$this->view_transaction_url = 'https://dashboard.stripe.com/payments/%s';
		}

		return parent::get_transaction_url( $order );
	}

	/**
	 * Modify WC public endpoints
	 *
	 * @return mixed|null
	 */
	public static function get_available_public_endpoints() {
		$endpoints = [
			'fkwcs_button_payment_request'  => 'process_smart_checkout',
			'wc_stripe_create_order'        => 'process_smart_checkout',
			'fkwcs_update_shipping_address' => 'update_shipping_address',
			'fkwcs_update_shipping_option'  => 'update_shipping_option',
			'fkwcs_add_to_cart'             => 'ajax_add_to_cart',
			'fkwcs_selected_product_data'   => 'ajax_fkwcs_selected_product_data',
			'fkwcs_get_cart_details'        => 'ajax_get_cart_details',
		];

		return apply_filters( 'fkwcs_public_endpoints', $endpoints );
	}

	public static function get_public_endpoints() {
		$public_endpoints = self::get_available_public_endpoints();
		if ( empty( $public_endpoints ) || 0 === count( $public_endpoints ) ) {
			return [];
		}

		$endpoints = [];
		foreach ( $public_endpoints as $key => $function ) {
			$endpoints[ $key ] = \WC_AJAX::get_endpoint( $key );
		}

		return $endpoints;
	}

	/**
	 * All payment gateways icons that work with Stripe. Some icons references
	 * WC core icons.
	 *
	 * @return array
	 */
	public function payment_icons() {
		return apply_filters( 'fkwcs_stripe_payment_icons', [
			'bancontact' => '<img src="' . FKWCS_URL . 'assets/icons/bancontact.svg" class="stripe-bancontact-icon stripe-icon" alt="Bancontact" />',
			'ideal'      => '<img src="' . FKWCS_URL . 'assets/icons/ideal.svg" class="stripe-ideal-icon stripe-icon" alt="iDEAL" />',
			'p24'        => '<img src="' . FKWCS_URL . 'assets/icons/p24.svg" class="stripe-p24-icon stripe-icon" alt="P24" />',
			'sepa'       => '<img src="' . FKWCS_URL . 'assets/icons/sepa.svg" class="stripe-sepa-icon stripe-icon" alt="SEPA" />',
		] );
	}



	/**
	 * Get return URL
	 *
	 * @param $order
	 * @param $id
	 *
	 * @return string
	 */
	public function get_stripe_return_url( $order = null, $id = null ) {
		if ( is_object( $order ) ) {
			if ( empty( $id ) ) {
				$id = uniqid();
			}

			$order_id = $order->get_id();

			$args       = [
				'utm_nooverride' => '1',
				'order_id'       => $order_id,
			];
			$return_url = $this->get_return_url( $order );
			Helper::log( "Return URL: $return_url" );

			return wp_sanitize_redirect( esc_url_raw( add_query_arg( $args, $return_url ) ) );
		}

		$return_url = $this->get_return_url();
		Helper::log( "Return URL: $return_url" );

		return wp_sanitize_redirect( esc_url_raw( add_query_arg( [ 'utm_nooverride' => '1' ], $this->get_return_url() ) ) );
	}

	/**
	 * Get WooCommerce store currency
	 *
	 * @return string
	 */
	public function get_currency() {
		global $wp;

		if ( isset( $wp->query_vars['order-pay'] ) ) {
			$order = wc_get_order( absint( $wp->query_vars['order-pay'] ) );

			return $order->get_currency();
		}

		return get_woocommerce_currency();
	}

	/**
	 * Get billing country for gateways
	 *
	 * @return string $billing_country
	 */
	public function get_billing_country() {
		global $wp;

		if ( isset( $wp->query_vars['order-pay'] ) ) {
			$order           = wc_get_order( absint( $wp->query_vars['order-pay'] ) );
			$billing_country = $order->get_billing_country();
		} else {
			$customer        = WC()->customer;
			$billing_country = $customer ? $customer->get_billing_country() : null;

			if ( ! $billing_country ) {
				$billing_country = WC()->countries->get_base_country();
			}
		}

		return $billing_country;
	}

	/**
	 * Return a description for (admin sections) describing the required currency & or billing country(s).
	 *
	 * @return string
	 */
	public function payment_description() {
		$desc = '';
		if ( method_exists( $this, 'get_supported_currency' ) && $this->get_currency() ) {
			// translators: %s: supported currency.
			$desc = sprintf( __( 'This gateway supports the following currencies only : <strong>%s</strong>.', 'funnelkit-stripe-woo-payment-gateway' ), implode( ', ', $this->get_supported_currency() ) );
		}

		return $this->get_description( $desc );
	}

	/**
	 * Get default form fields
	 *
	 * @return mixed|null
	 */
	public function get_default_settings() {
		$method_title = $this->method_title;

		$settings = [
			'enabled'     => [
				'label'   => ' ',
				'type'    => 'checkbox',
				// translators: %s: Method title.
				'title'   => sprintf( __( 'Enable %s', 'funnelkit-stripe-woo-payment-gateway' ), $method_title ),
				'default' => 'no',
			],
			'title'       => [
				'title'       => __( 'Title', 'funnelkit-stripe-woo-payment-gateway' ),
				'type'        => 'text',
				// translators: %s: Method title.
				'description' => sprintf( __( 'Title of the %s gateway.', 'funnelkit-stripe-woo-payment-gateway' ), $method_title ),
				'default'     => $method_title,
				'desc_tip'    => true,
			],
			'description' => [
				'title'       => __( 'Description', 'funnelkit-stripe-woo-payment-gateway' ),
				'type'        => 'textarea',
				'css'         => 'width:25em',
				/* translators: gateway title */
				'description' => sprintf( __( 'Description of the %1s gateway.', 'funnelkit-stripe-woo-payment-gateway' ), $method_title ),
				'desc_tip'    => true,
			]
		];

		$settings = array_merge( $settings, $this->get_countries_admin_fields() );

		return apply_filters( 'fkwcs_default_methods_default_settings', $settings );
	}

	/**
	 * @param $location string Selling location for gateway
	 * @param $except_country string|array Except country for gateway
	 * @param $specific_country string|array specific country for gateway
	 *
	 * @return array[]
	 */
	public function get_countries_admin_fields( $location = 'all', $except_country = [], $specific_country = [] ) {
		return [
			'allowed_countries'  => [
				'title'       => __( 'Selling location(s)', 'funnelkit-stripe-woo-payment-gateway' ),
				'default'     => $location,
				'type'        => 'select',
				'class'       => 'wc-enhanced-select wc-stripe-allowed-countries fkwcs-allowed-countries',
				'css'         => 'min-width: 350px;',
				'desc_tip'    => true,
				/* translators: gateway title */
				'description' => sprintf( __( 'This option lets you limit the %1$s to which countries you are willing to sell to.', 'funnelkit-stripe-woo-payment-gateway' ), $this->method_title ),
				'options'     => array(
					'all'        => __( 'Sell to all countries', 'funnelkit-stripe-woo-payment-gateway' ),
					'all_except' => __( 'Sell to all countries, except for&hellip;', 'funnelkit-stripe-woo-payment-gateway' ),
					'specific'   => __( 'Sell to specific countries', 'funnelkit-stripe-woo-payment-gateway' ),
				),
			],
			'except_countries'   => [
				'title'             => __( 'Sell to all countries, except for&hellip;', 'funnelkit-stripe-woo-payment-gateway' ),
				'type'              => 'multi_select_countries',
				'options'           => [],
				'default'           => $except_country,
				'class'             => 'fkwcs-except-countries',
				'desc_tip'          => true,
				'css'               => 'min-width: 350px;',
				'description'       => __( 'If any of the selected countries matches with the customer\'s billing country, then this payment method will not be visible on the checkout page.', 'funnelkit-stripe-woo-payment-gateway' ),
				'sanitize_callback' => function ( $value ) {
					return is_array( $value ) ? $value : array();
				},
			],
			'specific_countries' => [
				'title'             => __( 'Sell to specific countries', 'funnelkit-stripe-woo-payment-gateway' ),
				'type'              => 'multi_select_countries',
				'options'           => [],
				'default'           => $specific_country,
				'desc_tip'          => true,
				'class'             => 'fkwcs-specific-countries',
				'css'               => 'min-width: 350px;',
				'description'       => __( 'If any of the selected countries matches with the customer\'s billing country, then this payment method will be visible on the checkout page.', 'funnelkit-stripe-woo-payment-gateway' ),
				'sanitize_callback' => function ( $value ) {
					return is_array( $value ) ? $value : array();
				},
			],
		];
	}


	/**
	 * Prepare shipping data to pass onto api calls
	 *
	 * @param array $data
	 * @param \WC_Order $order
	 *
	 * @return mixed
	 */
	public function set_shipping_data( $data, $order ) {
		if ( ! empty( $order->get_shipping_postcode() ) ) {
			$data['shipping'] = [

				/**
				 * Prepare shipping data for the api call
				 */
				'name'    => trim( $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() ),
				'address' => [
					'line1'       => $order->get_shipping_address_1(),
					'line2'       => $order->get_shipping_address_2(),
					'city'        => $order->get_shipping_city(),
					'country'     => $order->get_shipping_country(),
					'postal_code' => $order->get_shipping_postcode(),
					'state'       => $order->get_shipping_state(),
				],
			];
		}

		return $data;
	}


	/**
	 * Prepare metadata to the api calls to create charge/PI
	 *
	 * @param \WC_Order $order
	 *
	 * @return mixed
	 */
	public function add_metadata( $order, $products = [] ) {

		global $sitepress;
		$domain = get_site_url();

		if ( isset( $sitepress ) && method_exists( $sitepress, 'get_default_language' ) && method_exists( $sitepress, 'get_wp_api' ) && method_exists( $sitepress, 'convert_url' ) ) {
			$default_language = $sitepress->get_default_language();
			$domain           = $sitepress->convert_url( $sitepress->get_wp_api()->get_home_url(), $default_language );
		}
		$metadata      = [
			'customer_name'  => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
			'customer_email' => $order->get_billing_email(),
			'order_number'   => $order->get_order_number(),
			'order_id'       => $order->get_id(),
			'site_url'       => esc_url( $domain ),
			'wp_user_id'     => $order->get_user_id(),
			'customer_ip'    => $order->get_customer_ip_address(),
			'user_agent'     => wc_get_user_agent()
		];
		$get_unique_id = get_option( 'fkwcs_wp_hash', '' );
		if ( ! empty( $get_unique_id ) ) {
			$metadata['wp_stripe'] = $get_unique_id;
		}

		if ( empty( $products ) ) {
			$items = [];
			foreach ( $order->get_items( 'line_item' ) as $item ) {
				$items[] = sprintf( '%s x %s', $item->get_name(), $item->get_quantity() );
			}
		} else {
			$items = $products;
		}

		if ( 500 > strlen( implode( ', ', $items ) ) ) {
			$metadata['products'] = implode( ', ', $items );

		}

		return apply_filters( 'fkwcs_payment_metadata', $metadata, $order );
	}

	/**
	 * Add metadata to stripe
	 *
	 * @param int $order_id WooCommerce order Id.
	 *
	 * @return array
	 *
	 */
	public function get_metadata( $order_id ) {
		$order              = wc_get_order( $order_id );
		$details            = [];
		$billing_first_name = $order->get_billing_first_name();
		$billing_last_name  = $order->get_billing_last_name();
		$name               = $billing_first_name . ' ' . $billing_last_name;

		if ( ! empty( $name ) ) {
			$details['name'] = $name;
		}

		if ( ! empty( $order->get_billing_email() ) ) {
			$details['email'] = $order->get_billing_email();
		}

		if ( ! empty( $order->get_billing_phone() ) ) {
			$details['phone'] = $order->get_billing_phone();
		}

		if ( ! empty( $order->get_billing_address_1() ) ) {
			$details['address'] = $order->get_billing_address_1();
		}

		if ( ! empty( $order->get_billing_city() ) ) {
			$details['city'] = $order->get_billing_city();
		}

		if ( ! empty( $order->get_billing_country() ) ) {
			$details['country'] = $order->get_billing_country();
		}

		$details['site_url'] = get_site_url();

		return apply_filters( 'fkwcs_metadata_details', $details, $order );
	}


	/**
	 * Checks if subscription plugin exists and order contains subscription items
	 *
	 * @param $order_id
	 *
	 * @return bool
	 */
	public function has_subscription( $order_id ) {
		return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
	}


	/**
	 * Get key value from the order meta or look for relative area
	 *
	 * @param string $meta_key
	 * @param \WC_Order $order
	 * @param string $context
	 *
	 * @return mixed
	 */
	public function get_order_stripe_data( $meta_key, $order ) {
		$value = Helper::get_meta( $order, $meta_key );

		if ( ! empty( $value ) ) {
			return $value;
		}

		/** value is empty so check metadata from other plugins */
		$keys = array();
		switch ( $meta_key ) {
			case '_fkwcs_source_id':
				$keys = Helper::get_compatibility_keys( '_fkwcs_source_id' );
				break;
			case '_fkwcs_customer_id':
				$keys = Helper::get_compatibility_keys( '_fkwcs_customer_id' );
				break;
			case '_fkwcs_intent_id':
				$keys = Helper::get_compatibility_keys( '_fkwcs_intent_id' );
		}

		if ( empty( $keys ) ) {
			return $value;
		}

		/**
		 * Now that we know we have meta found from other gateway, lets save the value as our key
		 */
		$meta_data = $order->get_meta_data();
		if ( $meta_data ) {
			$keys       = array_intersect( wp_list_pluck( $meta_data, 'key' ), $keys );
			$array_keys = array_keys( $keys );
			if ( ! empty( $array_keys ) ) {
				$value = $meta_data[ current( $array_keys ) ]->value;
				$order->update_meta_data( $meta_key, $value );
				$order->save_meta_data();
			}
		}

		return $value;
	}

	/**
	 * Checks if gateway is available
	 *
	 * @return bool
	 */
	public function is_available() {
		if ( 'yes' !== $this->enabled ) {
			return false;
		}


		if ( false === $this->is_configured() ) {
			return false;
		}

		return true;
	}


	/**
	 * Checks if a given payment gateway is available locally
	 * Invoked from gateways like iDeal, Sepa, Alipay etc
	 *
	 * @return bool
	 */
	public function is_available_local_gateway() {
		if ( 'yes' !== $this->enabled ) {
			return false;
		}

		if ( ! in_array( get_woocommerce_currency(), $this->get_supported_currency(), true ) ) {
			return false;
		}

		if ( ! empty( $this->get_option( 'allowed_countries' ) ) && 'all_except' === $this->get_option( 'allowed_countries' ) ) {
			return ! in_array( $this->get_billing_country(), $this->get_option( 'except_countries', array() ), true );
		} elseif ( ! empty( $this->get_option( 'allowed_countries' ) ) && 'specific' === $this->get_option( 'allowed_countries' ) ) {
			return in_array( $this->get_billing_country(), $this->get_option( 'specific_countries', array() ), true );
		}

		return parent::is_available();
	}

	/**
	 * Get test mode description
	 *
	 * @return string
	 */
	public function get_test_mode_description() {
		return '';
	}

	public function get_latest_charge_from_intent( $intent ) {
		if ( ! empty( $intent->charges->data ) ) {
			return end( $intent->charges->data );
		} else {
			return $this->get_charge_object( $intent->latest_charge );
		}
	}


	/**
	 * Get charge object by charge ID.
	 *
	 * @param string $charge_id The charge ID to get charge object for.
	 *
	 * @return string|object
	 * @throws \Exception Error while retrieving charge object.
	 * @since 1.2.0
	 */
	public function get_charge_object( $charge_id = '' ) {
		if ( empty( $charge_id ) ) {
			return '';
		}

		$charge_object = $this->get_client()->charges( 'GET', [ $charge_id ] );

		if ( $charge_object['success'] === false ) {
			throw new \Exception( $charge_object['success'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		return $charge_object['data'];
	}

	public function enqueue_cc_css() {
		wp_enqueue_style( 'fkwcs-style', FKWCS_URL . 'assets/css/style' . Helper::is_min_suffix() . '.css', [], FKWCS_VERSION );
	}

	/**
	 * Verify intent secret and redirect to the thankyou page
	 *
	 * @return void
	 */
	public function verify_intent() {
		global $woocommerce;
		wc_maybe_define_constant( 'WOOCOMMERCE_CHECKOUT', true );

		try {
			$order_id = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 0; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$order    = wc_get_order( $order_id );

			if ( ! isset( $_GET['order_key'] ) || ! $order instanceof \WC_Order || ! $order->key_is_valid( wc_clean( $_GET['order_key'] ) ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				throw new \Exception( __( 'Invalid Order Key.', 'funnelkit-stripe-woo-payment-gateway' ) );

			}

		} catch ( \Exception $e ) {
			/* translators: Error message text */
			$message = sprintf( __( 'Payment verification error: %s', 'funnelkit-stripe-woo-payment-gateway' ), $e->getMessage() );
			wc_add_notice( esc_html( $message ), 'error' );
			$redirect_url = $woocommerce->cart->is_empty() ? get_permalink( wc_get_page_id( 'shop' ) ) : wc_get_checkout_url();
			$this->handle_error( $e, $redirect_url );
		}

		try {
			$redirect_url = isset( $_GET['fkwcs_redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['fkwcs_redirect_to'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended


			if ( ! $order->has_status( apply_filters( 'fkwcs_stripe_allowed_payment_processing_statuses', [ 'pending', 'failed' ], $order ) ) ) {
				$redirect_url = $this->get_return_url( $order );
				wp_safe_redirect( $redirect_url );
				exit;

			}
			$intent = $this->get_intent_from_order( $order );
			if ( false === $intent ) {
				throw new \Exception( 'Intent Not Found' );
			}
			if ( isset( $_GET['save_card'] ) || 'off_session' === $intent->setup_future_usage ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->save_payment_method( $order, $intent );


				if ( 'setup_intent' === $intent->object ) {
					$mandate_id = isset( $intent->mandate ) ? $intent->mandate : false;
				} else {
					$charge = $this->get_latest_charge_from_intent( $intent );
					if ( isset( $charge->payment_method_details->card->mandate ) ) {
						$mandate_id = $charge->payment_method_details->card->mandate;

					}
				}

				if ( isset( $mandate_id ) && ! empty( $mandate_id ) ) {
					$order->update_meta_data( '_stripe_mandate_id', $mandate_id );
					$order->save_meta_data();
				}

			}

			if ( 'setup_intent' === $intent->object && 'succeeded' === $intent->status ) {
				$order->payment_complete();
				$redirect_url = $this->get_return_url( $order );

				/**
				 * Remove the webhook paid meta data from the order
				 * This is to avoid any extra processing of this order
				 */
				$order->delete_meta_data( '_fkwcs_webhook_paid' );
				$order->save_meta_data();

				// Remove cart.
				if ( ! is_null( WC()->cart ) ) {
					WC()->cart->empty_cart();
				}

			} else if ( 'succeeded' === $intent->status || 'requires_capture' === $intent->status ) {
				$redirect_url = $this->process_final_order( end( $intent->charges->data ), $order_id );
			} else if ( 'requires_payment_method' === $intent->status ) {


				$redirect_url = wc_get_checkout_url();
				wc_add_notice( __( 'Unable to process this payment, please try again or use alternative method.', 'woocommerce-gateway-stripe' ), 'error' );

				/**
				 * Handle intent with no payment method here, we mark the order as failed and show users a notice
				 */
				if ( $order->has_status( 'failed' ) ) {
					wp_safe_redirect( $redirect_url );
					exit;

				}

				// Load the right message and update the status.
				$status_message = isset( $intent->last_payment_error ) /* translators: 1) The error message that was received from Stripe. */ ? sprintf( __( 'Stripe SCA authentication failed. Reason: %s', 'funnelkit-stripe-woo-payment-gateway' ), $intent->last_payment_error->message ) : __( 'Stripe SCA authentication failed.', 'funnelkit-stripe-woo-payment-gateway' );
				$order->update_status( 'failed', $status_message );

			}


			Helper::log( "Redirecting to :" . $redirect_url );
		} catch ( \Exception $e ) {
			$redirect_url = $woocommerce->cart->is_empty() ? get_permalink( wc_get_page_id( 'shop' ) ) : wc_get_checkout_url();
			wc_add_notice( esc_html( $e->getMessage() ), 'error' );
		}
		if ( ! isset( $_GET['is_ajax'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect( $redirect_url );
			exit;

		}
		exit;
	}


	/**
	 * Save Meta Data Like Balance Charge ID & status
	 * Add respective  order notes according to stripe charge status
	 *
	 * @param $response
	 * @param $order_id Int Order ID
	 *
	 * @return string
	 */
	public function process_final_order( $response, $order_id ) {
		$order = wc_get_order( $order_id );
		if ( isset( $response->balance_transaction ) ) {
			Helper::update_balance( $order, $response->balance_transaction );
		}

		if ( wc_string_to_bool( $response->captured ) ) {
			$order->payment_complete( $response->id );

			/* translators: order id */
			Helper::log( sprintf( 'Payment successful Order id - %1s', $order->get_id() ) );

			/* translators: 1: Charge ID. 2: Brand name 3: last four digit */

			$order->add_order_note( sprintf( __( 'Order charge successful in Stripe. Charge: %s. Payment method: %s ending in %d', 'funnelkit-stripe-woo-payment-gateway' ), $response->id, ucfirst( $response->payment_method_details->card->brand ), $response->payment_method_details->card->last4 ) );


			/**
			 * Remove the webhook paid meta data from the order
			 * This is to avoid any extra processing of this order
			 */
			$order->delete_meta_data( '_fkwcs_webhook_paid' );
			$order->save_meta_data();
		} else {
			$order->set_transaction_id( $response->id );
			$order->save();
			/* translators: transaction id */
			$order->update_status( 'on-hold', sprintf( __( 'Charge authorized (Charge ID: %s). Press an eye icon below Transaction Data / Actions to Capture/Void the charge.', 'funnelkit-stripe-woo-payment-gateway' ), $response->id ) );
			/* translators: transaction id */
			Helper::log( sprintf( 'Charge authorized Order id - %1s', $order->get_id() ) );
		}

		/** Empty cart */
		if ( ! is_null( WC()->cart ) ) {
			WC()->cart->empty_cart();
		}
		$return_url = $this->get_return_url( $order );

		return $return_url;
	}

	/**
	 * After verify intent got called its time to save payment method to the order
	 *
	 * @param $order
	 * @param $intent
	 *
	 * @return void
	 */
	public function save_payment_method( $order, $intent ) {
		$payment_method = $intent->payment_method;
		$response       = $this->get_client()->payment_methods( 'retrieve', [ $payment_method ] );
		$payment_method = $response['success'] ? $response['data'] : false;

		$token = null;
		$user  = $order->get_id() ? $order->get_user() : wp_get_current_user();
		if ( $user instanceof \WP_User ) {
			$user_id = $user->ID;
			$token   = $this->create_payment_token_for_user( $user_id, $payment_method, $intent->livemode );
			Helper::log( sprintf( 'Payment method tokenized for Order id - %1$1s with token id - %2$2s', $order->get_id(), $token->get_id() ) );
		}

		$prepared_payment_method = Helper::prepare_payment_method( $payment_method, $token );
		$this->save_payment_method_to_order( $order, $prepared_payment_method );
	}

	/**
	 * Save payment method to meta of the current order
	 *
	 * @param object $order current WooCommerce order.
	 * @param object $payment_method payment method associated with current order.
	 *
	 * @return void
	 */
	public function save_payment_method_to_order( $order, $payment_method ) {
		Helper::log( 'Entering::' . __FUNCTION__ );

		if ( ! empty( $payment_method->customer ) ) {
			$order->update_meta_data( Helper::get_customer_key(), $payment_method->customer );
		}

		$order->update_meta_data( '_fkwcs_source_id', $payment_method->source );

		if ( ! empty( $payment_method->token ) ) {
			$order->update_meta_data( '_fkwcs_token_id', $payment_method->token );
			$token_obj = \WC_Payment_Tokens::get( $payment_method->token );
			if ( ! is_null( $token_obj ) ) {
				$token_obj->add_meta_data( Helper::get_customer_key(), $payment_method->customer );
				$token_obj->save();
			}
		}
		if ( is_callable( [ $order, 'save' ] ) ) {
			$order->save();
		}

		$this->maybe_update_source_on_subscription_order( $order, $payment_method );
	}


	/**
	 * Tokenize card payment
	 *
	 * @param int $user_id id of current user placing .
	 * @param object $payment_method payment method object.
	 *
	 * @return object token object.
	 */
	public function create_payment_token_for_user( $user_id, $payment_method, $is_live ) {
		$token = new \WC_Payment_Token_CC();
		$token->set_expiry_month( $payment_method->card->exp_month );
		$token->set_expiry_year( $payment_method->card->exp_year );
		$token->set_card_type( strtolower( $payment_method->card->brand ) );
		$token->set_last4( $payment_method->card->last4 );
		$token->set_gateway_id( $this->id );
		$token->set_token( $payment_method->id );
		$token->set_user_id( $user_id );
		$token->update_meta_data( 'mode', ( $is_live ) ? 'live' : 'test' );
		$token->save_meta_data();
		$token->save();

		return $token;
	}
	/**
	 * Create multiple countries selection HTML
	 *
	 * @param $key
	 * @param $data
	 *
	 * @return false|string
	 */
	public function generate_multi_select_countries_html( $key, $data ) {
		$field_key = $this->get_field_key( $key );
		$value     = (array) $this->get_option( $key );
		$data      = wp_parse_args( $data, array(
			'title'       => '',
			'class'       => '',
			'style'       => '',
			'description' => '',
			'desc_tip'    => false,
			'id'          => $field_key,
			'options'     => [],
		) );

		ob_start();

		if ( empty( $value ) ) {
			$value = $data['default'];

		}
		$selections = (array) $value;

		if ( ! empty( $data['options'] ) ) {
			$countries = array_intersect_key( WC()->countries->countries, array_flip( $data['options'] ) );
		} else {
			$countries = WC()->countries->countries;
		}

		asort( $countries );
		?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr( $data['id'] ); ?>"><?php echo esc_html( $data['title'] ); ?><?php echo $this->get_tooltip_html( $data ); //phpcs:ignore ?></label>
            </th>
            <td class="forminp">
                <select multiple="multiple" name="<?php echo esc_attr( $data['id'] ); ?>[]" style="width:350px"
                        data-placeholder="<?php esc_attr_e( 'Choose countries / regions&hellip;', 'funnelkit-stripe-woo-payment-gateway' ); ?>"
                        aria-label="<?php esc_attr_e( 'Country / Region', 'funnelkit-stripe-woo-payment-gateway' ); ?>" class="wc-enhanced-select <?php esc_attr_e( $data['class'] ) ?>">
					<?php
					if ( ! empty( $countries ) ) {
						foreach ( $countries as $key => $val ) {
							echo '<option value="' . esc_attr( $key ) . '"' . wc_selected( $key, $selections ) . '>' . esc_html( $val ) . '</option>'; //phpcs:ignore
						}
					}
					?>
                </select>
				<?php echo $this->get_description_html( $data ); //phpcs:ignore ?>
                <br/>
                <a class="select_all button" href="#"><?php esc_html_e( 'Select all', 'funnelkit-stripe-woo-payment-gateway' ); ?></a>
                <a class="select_none button" href="#"><?php esc_html_e( 'Select none', 'funnelkit-stripe-woo-payment-gateway' ); ?></a>
            </td>
        </tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Validate countries from a given list
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return array|string
	 */
	public function validate_multi_select_countries_field( $key, $value ) {
		return is_array( $value ) ? array_map( 'wc_clean', array_map( 'stripslashes', $value ) ) : '';
	}


}
