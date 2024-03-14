<?php
/**
 * Revolut Credit Card Gateway
 *
 * Provides a Revolut Payment Gateway to accept credit card payments.
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 */

/**
 * WC_Gateway_Revolut_CC class.
 */
class WC_Gateway_Revolut_CC extends WC_Payment_Gateway_Revolut {
	const GATEWAY_ID = 'revolut_cc';

	/**
	 * Save payment method for merchant or customer.
	 *
	 * @var string
	 */
	public $save_payment_method_for;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id           = self::GATEWAY_ID;
		$this->method_title = __( 'Revolut Gateway - Credit/Debit Cards', 'revolut-gateway-for-woocommerce' );
		$this->tab_title    = __( 'Credit/Debit Cards', 'revolut-gateway-for-woocommerce' );

		$this->default_title = __( 'Pay with card', 'revolut-gateway-for-woocommerce' );
		/* translators:%1s: %$2s: */
		$this->method_description = sprintf( __( 'Accept card payments easily and securely via %1$sRevolut%2$s.', 'revolut-gateway-for-woocommerce' ), '<a href="https://www.revolut.com/business/online-payments">', '</a>' );

		$this->title                   = $this->get_option( 'title' );
		$this->description             = $this->get_option( 'description' );
		$this->save_payment_method_for = 'merchant';

		parent::__construct();

		if ( get_option( 'woocommerce_revolut_cc_settings' ) === false ) {
			$this->add_default_options();
		}

		$this->init_scripts();

		$this->revolut_saved_cards = 'yes' === $this->get_option( 'revolut_saved_cards' );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_payment_scripts' ) );
		add_filter( 'wc_revolut_settings_nav_tabs', array( $this, 'admin_nav_tab' ), 4 );

		if ( class_exists( 'WC_Subscriptions_Order' ) ) {
			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
			add_action( 'woocommerce_subscriptions_change_payment_before_submit', array( $this, 'differentiate_change_payment_method_form' ) );
			add_action( 'wcs_resubscribe_order_created', array( $this, 'delete_resubscribe_meta' ), 10 );
			// display the credit card used for a subscription in the "My Subscriptions" table.
			add_filter( 'woocommerce_my_subscriptions_payment_method', array( $this, 'maybe_render_subscription_payment_method' ), 10, 2 );
			add_action( 'woocommerce_subscription_failing_payment_method_updated_' . $this->id, array( $this, 'update_failing_payment_method' ), 10, 2 );
			add_action( 'woocommerce_subscription_token_changed', array( $this, 'update_changed_subscription_token' ), 10, 2 );
		}
	}

	/**
	 * Load required payment scripts.
	 */
	public function load_payment_scripts() {
		$this->tokenization_script();
	}


	/**
	 * Supported functionality
	 */
	public function init_supports() {
		parent::init_supports();
		$this->supports = array(
			'refunds',
			'tokenization',
			'add_payment_method',
			'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change',
			'subscription_payment_method_change_customer',
			'subscription_payment_method_change_admin',
			'multiple_subscriptions',
			'pre-orders',
		);
	}

	/**
	 * Initialize Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'                 => array(
				'title'       => __( 'Enable/Disable', 'revolut-gateway-for-woocommerce' ),
				'label'       => __( 'Enable ', 'revolut-gateway-for-woocommerce' ) . $this->method_title,
				'type'        => 'checkbox',
				'description' => __( 'This controls whether or not this gateway is enabled within WooCommerce.', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'title'                   => array(
				'title'       => __( 'Title', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This controls the title that the user sees during checkout.', 'revolut-gateway-for-woocommerce' ),
				'default'     => $this->default_title,
				'desc_tip'    => true,
			),
			'card_widget_type'        => array(
				'title'       => __( 'Card widget type', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Select the card widget type.', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'card_field',
				'desc_tip'    => true,
				'options'     => array(
					'card_field' => __( 'Card field', 'revolut-gateway-for-woocommerce' ),
					'popup'      => __( 'Popup', 'revolut-gateway-for-woocommerce' ),
				),
			),
			'revolut_saved_cards'     => array(
				'title'       => __( 'Save Cards', 'revolut-gateway-for-woocommerce' ),
				'label'       => __( 'Enable Payment with Saved Cards', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'If enabled, users will be able to save their cards and use them to checkout in subsequent purchases. Card details are stored securely on Revolut servers', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'enable_cardholder_name'  => array(
				'title'       => __( 'Cardholder\'s Name Field', 'revolut-gateway-for-woocommerce' ),
				'label'       => __( 'Enable', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'checkbox',
				'default'     => 'no',
				'description' => __( 'When enabled, input for Cardholder\'s Name will be activated.', 'revolut-gateway-for-woocommerce' ),
				'desc_tip'    => true,
			),
			'styling_title'           => array(
				'title' => __( 'Card widget style', 'revolut-gateway-for-woocommerce' ),
				'type'  => 'title',
			),
			'widget_styling'          => array(
				'title'       => __( 'Customize card widget style', 'revolut-gateway-for-woocommerce' ),
				'label'       => __( 'Enable', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'checkbox',
				'description' => __( 'By enabling this option you can customize the style of the Revolut card widget', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'no',
				'class'       => 'revolut_styling_option_enable',
				'desc_tip'    => true,
			),
			'widget_background_color' => array(
				'title'       => __( 'Card widget background color', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'color',
				'description' => __( 'This controls the background color of the Revolut card widget', 'revolut-gateway-for-woocommerce' ),
				'default'     => WC_REVOLUT_CARD_WIDGET_BG_COLOR,
				'class'       => 'revolut_styling_option',
				'desc_tip'    => true,
			),
			'widget_text_color'       => array(
				'title'       => __( 'Card widget font color', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'color',
				'description' => __( 'This controls the text color of the Revolut Card widget', 'revolut-gateway-for-woocommerce' ),
				'default'     => WC_REVOLUT_CARD_WIDGET_TEXT_COLOR,
				'class'       => 'revolut_styling_option',
				'desc_tip'    => true,
			),
			'revolut_logo_color'      => array(
				'title'       => __( 'Revolut logo theme', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'select',
				'default'     => '#7a7a7a',
				'class'       => 'revolut_styling_option',
				'options'     => array(
					'#7a7a7a' => __( 'Dark', 'revolut-gateway-for-woocommerce' ),
					'#cccccc' => __( 'Light', 'revolut-gateway-for-woocommerce' ),
				),
				'description' => __( 'This controls the color of the Revolut', 'revolut-gateway-for-woocommerce' ),
				'desc_tip'    => true,
			),
			'restore_button'          => array(
				'title'       => '',
				'type'        => 'text',
				'description' => '<button class="revolut_style_restore revolut_styling_option setup-webhook" style="min-height: 30px;"><span id="span-for-active-button-sandbox">Restore defaults</span></button>',
			),
		);
	}

	/**
	 * Scheduled_subscription_payment function.
	 *
	 * @param float    $amount_to_charge The amount to charge.
	 * @param WC_Order $renewal_order WC_Order order.
	 *
	 * @throws Exception Exception.
	 */
	public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
		try {
			$wc_order_id = $renewal_order->get_id();

			$payment_token_id = $renewal_order->get_meta( '_payment_token_id', true );

			if ( empty( $payment_token_id ) ) {
				throw new Exception( "Subscription order payment token is missing: $payment_token_id" );
			}

			$wc_customer_id = $renewal_order->get_meta( '_wc_customer_id', true );

			if ( empty( $wc_customer_id ) ) {
				throw new Exception( "Subscription customer ID is missing: $payment_token_id" );
			}

			$revolut_customer_id = $this->get_revolut_customer_id( $wc_customer_id );

			if ( empty( $revolut_customer_id ) ) {
				throw new Exception( "Can not find Subscription Revolut customer ID: $revolut_customer_id" );
			}

			$descriptor = new WC_Revolut_Order_Descriptor( $amount_to_charge, $renewal_order->get_currency(), $revolut_customer_id );

			$revolut_payment_public_id = $this->create_revolut_order( $descriptor );

			// resolve revolut_public_id into revolut_order_id.
			$revolut_order_id = $this->get_revolut_order_by_public_id( $revolut_payment_public_id );

			if ( empty( $revolut_order_id ) ) {
				throw new Exception( 'Can not find Revolut order ID' );
			}

			// update internal table to avoid piggybacking on already paid order.
			$this->save_wc_order_id( $revolut_payment_public_id, $revolut_order_id, $wc_order_id );

			// make the payment with saved card $payment_method_id.
			$wc_token = WC_Payment_Tokens::get( $payment_token_id );
			$this->pay_by_saved_method( $revolut_order_id, $wc_token );

			// payment should be processed until this point, if not throw an error.
			$this->check_payment_processed( $revolut_order_id );

			// payment process began...
			$renewal_order->update_status( 'on-hold' );
			$renewal_order->add_order_note( 'Payment has been successfully authorized (Order ID: ' . $revolut_order_id . ').' );

			// check payment result and update order status.
			$this->handle_revolut_order_result( $renewal_order, $revolut_order_id );

			$message = sprintf( 'Subscription charge successfully completed by Revolut (Order ID: %s)', $revolut_order_id );
			$renewal_order->add_order_note( $message );
			$renewal_order->set_transaction_id( $revolut_order_id );
			WC_Subscriptions_Manager::process_subscription_payments_on_order( $renewal_order );
		} catch ( Exception $e ) {
			WC_Subscriptions_Manager::process_subscription_payment_failure_on_order( $renewal_order );
			$renewal_order->update_status( 'failed', 'An error occurred while Payment processing: ' . $e->getMessage() );
			$this->log_error( $e->getMessage() );
			return false;
		}

		return true;
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int    $wc_order_id WooCommerce order id.
	 * @param string $revolut_payment_public_id Revolut payment public id.
	 * @param bool   $is_express_checkout Express checkout identifier.
	 * @param string $revolut_payment_error Payment error.
	 * @param bool   $reload_checkout Indicates if the page should reloaded.
	 * @param bool   $revolut_pay_redirected Indicates Revolut Pay webflow redirection.
	 * @param bool   $is_using_saved_payment_method Indicates payments by payment token.
	 * @param bool   $save_payment_method_requested Indicates if payment token should be saved.
	 * @param int    $wc_token_id WooCommerce token id.
	 *
	 * @throws Exception Exception.
	 */
	public function process_payment( $wc_order_id, $revolut_payment_public_id = '', $is_express_checkout = false, $revolut_payment_error = '', $reload_checkout = false, $revolut_pay_redirected = false, $is_using_saved_payment_method = false, $save_payment_method_requested = false, $wc_token_id = 0 ) {
		if ( $this->has_subscription( $wc_order_id ) ) {
			if ( isset( $_POST['_wcsnonce'] ) && wp_verify_nonce( wc_clean( wp_unslash( $_POST['_wcsnonce'] ) ), 'wcs_change_payment_method' ) ) {
				if ( isset( $_POST['woocommerce_change_payment'] ) ) {
					return $this->change_subs_payment_method( $wc_order_id );
				}
			}
		}

		return parent::process_payment( $wc_order_id, $revolut_payment_public_id, $is_express_checkout, $revolut_payment_error, $reload_checkout, $revolut_pay_redirected, $is_using_saved_payment_method, $save_payment_method_requested, $wc_token_id );
	}

	/**
	 * Render an input in the "Change payment method" form which does not appear in the "Pay for order" page
	 */
	public function differentiate_change_payment_method_form() {
		echo wp_kses(
			'<input type="hidden" id="wc-revolut-change-payment-method" />',
			array(
				'input' => array(
					'id'          => array(),
					'type'        => array(),
					'name'        => array(),
					'value'       => array(),
					'checked'     => array(),
					'class'       => array(),
					'placeholder' => array(),
					'style'       => array(),
				),
			),
		);
	}

	/**
	 * Process the payment method change for subscriptions.
	 *
	 * @param int $wc_order_id WooCommerce order id.
	 *
	 * @throws Exception Exception.
	 */
	public function change_subs_payment_method( $wc_order_id ) {
		try {
			if ( ! isset( $_POST['_wcsnonce'] ) || ! wp_verify_nonce( wc_clean( wp_unslash( $_POST['_wcsnonce'] ) ), 'wcs_change_payment_method' ) ) {
				return;
			}

			$subscription = wc_get_order( $wc_order_id );

			$revolut_payment_public_id               = isset( $_POST['revolut_public_id'] ) ? wc_clean( wp_unslash( $_POST['revolut_public_id'] ) ) : '';
			$wc_payment_token_id                     = isset( $_POST[ 'wc-' . $this->id . '-payment-token' ] ) ? wc_clean( wp_unslash( $_POST[ 'wc-' . $this->id . '-payment-token' ] ) ) : '';
			$is_using_saved_payment_method           = ! empty( $wc_payment_token_id ) && 'new' !== $wc_payment_token_id;
			$update_all_subscriptions_payment_method = isset( $_POST[ 'wc-' . $this->id . '-update-subs-payment-method-card' ] ) || isset( $_POST['update_all_subscriptions_payment_method'] );

			if ( empty( $revolut_payment_public_id ) ) {
				throw new Exception( 'Missing revolut_public_id parameter' );
			}

			// resolve revolut_public_id into revolut_order_id.
			$revolut_order_id = $this->get_revolut_order_by_public_id( $revolut_payment_public_id );

			if ( empty( $revolut_order_id ) ) {
				throw new Exception( 'Can not find Revolut order ID' );
			}

			if ( $is_using_saved_payment_method ) {
				$wc_token = $this->get_selected_payment_token( $wc_payment_token_id );
			} else {
				$wc_token = $this->save_payment_method( $revolut_order_id );
				if ( null === $wc_token ) {
					throw new Exception( 'An error occurred while saving payment method' );
				}
			}

			$this->save_payment_token_to_order( $subscription, $wc_token, get_current_user_id() );
			$this->handle_add_payment_method( $subscription, $wc_token, get_current_user_id(), $update_all_subscriptions_payment_method );

			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $subscription ),
			);
		} catch ( Exception $e ) {
			$this->log_error( $e->getMessage() );
			wc_add_notice( $e->getMessage(), 'error' );
			return false;
		}
	}


	/**
	 * Updates all active subscriptions payment method.
	 *
	 * @param  WC_Subscription $current_subscription WooCommerce Subscription.
	 * @param  object          $wc_token WooCommerce Payment Token.
	 * @param  int             $wc_customer_id WooCommerce Customer id.
	 * @param  bool            $update_all_subscriptions_payment_method Indicates if payment methods should be updated for all subscriptions.
	 * @return bool
	 */
	public function handle_add_payment_method( $current_subscription, $wc_token, $wc_customer_id, $update_all_subscriptions_payment_method ) {
		// remove public ID after saving the card.
		$this->unset_revolut_public_id();

		if ( $update_all_subscriptions_payment_method ) {
			$all_subs = wcs_get_users_subscriptions();

			if ( ! empty( $all_subs ) ) {
				foreach ( $all_subs as $sub ) {
					$this->update_payment_subscription_method( $sub, $wc_token, $wc_customer_id );
				}
			}

			return true;
		}

		if ( null !== $current_subscription ) {
			return $this->update_payment_subscription_method( $current_subscription, $wc_token, $wc_customer_id );
		}

		return true;
	}

	/**
	 * Updates active subscription payment method.
	 *
	 * @param WC_Subscription $subscription WooCommerce Subscription.
	 * @param object          $wc_token WooCommerce Payment Token id.
	 * @param int             $wc_customer_id WooCommerce Customer id.
	 * @return bool
	 */
	public function update_payment_subscription_method( $subscription, $wc_token, $wc_customer_id ) {
		if ( $subscription->has_status( 'active' ) ) {
			WC_Subscriptions_Change_Payment_Gateway::update_payment_method(
				$subscription,
				$this->id,
				array(
					'post_meta' => array(
						'_payment_token'    => array( 'value' => $wc_token->get_token() ),
						'_payment_token_id' => array( 'value' => $wc_token->get_id() ),
						'_wc_customer_id'   => array( 'value' => $wc_customer_id ),
					),
				)
			);
		}

		return true;
	}

	/**
	 * Check is $order_id a subscription.
	 *
	 * @param int $order_id  WooCommerce Order id.
	 * @return boolean
	 */
	public function has_subscription( $order_id ) {
		return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
	}

	/**
	 * Remove previous subscription information from resubscribe orders.
	 *
	 * @param WC_Order $resubscribe_order instance of a order object.
	 * @return void
	 */
	public function delete_resubscribe_meta( $resubscribe_order ) {
		$resubscribe_order->delete_meta_data( $resubscribe_order->get_id(), '_payment_token' );
		$resubscribe_order->delete_meta_data( $resubscribe_order->get_id(), '_payment_token_id' );
		$resubscribe_order->delete_meta_data( $resubscribe_order->get_id(), '_wc_customer_id' );
	}

	/**
	 * Render the payment method used for a subscription in the "My Subscriptions" table
	 *
	 * @param string          $payment_method_to_display the default payment method text to display.
	 * @param WC_Subscription $subscription the subscription details.
	 * @return string the subscription payment method.
	 */
	public function maybe_render_subscription_payment_method( $payment_method_to_display, $subscription ) {
		$customer_user = $subscription->get_customer_id();

		// fail for other payment methods.
		if ( $subscription->get_payment_method() !== $this->id || ! $customer_user ) {
			return $payment_method_to_display;
		}

		$revolut_payment_token_id  = get_post_meta( $subscription->get_id(), '_payment_token_id', true );
		$payment_method_to_display = __( 'N/A', 'revolut-gateway-for-woocommerce' );
		$wc_token                  = WC_Payment_Tokens::get( $revolut_payment_token_id );

		if ( $wc_token ) {
			/* translators:%1s: Card type %$2s: Card last four digits */
			$payment_method_to_display = sprintf( __( 'Via %1$s card ending in %2$s', 'revolut-gateway-for-woocommerce' ), $wc_token->get_card_type(), $wc_token->get_last4() );
		}

		return $payment_method_to_display;
	}

	/**
	 * Update the customer_id for a subscription after using Revolut to complete a payment to make up for
	 * an automatic renewal payment which previously failed.
	 *
	 * @param WC_Subscription $subscription The subscription for which the failing payment method relates.
	 * @param WC_Order        $renewal_order The order which recorded the successful payment (to make up for the failed automatic payment).
	 * @return void
	 */
	public function update_failing_payment_method( $subscription, $renewal_order ) {
		$subscription->update_meta_data( '_payment_token', $renewal_order->get_meta( '_payment_token' ), $subscription->get_id() );
		$subscription->update_meta_data( '_payment_token_id', $renewal_order->get_meta( '_payment_token_id' ), $subscription->get_id() );
		$subscription->update_meta_data( '_wc_customer_id', $renewal_order->get_meta( '_wc_customer_id' ), $subscription->get_id() );
		$subscription->save();
	}

	/**
	 * Update the subscription payment meta to change from an old payment token to a new one.
	 *
	 * @param WC_Subscription  $subscription The subscription to update.
	 * @param WC_Payment_Token $new_token The new payment token.
	 * @return void
	 */
	public function update_changed_subscription_token( $subscription, $new_token ) {
		if ( $new_token->get_gateway_id() === $this->id ) {
			$subscription_id = $subscription->get_id();
			$subscription->update_meta_data( '_payment_token', $new_token->get_token(), $subscription_id );
			$subscription->update_meta_data( '_payment_token_id', $new_token->get_id(), $subscription_id );
			$subscription->save();
		}
	}

	/**
	 * Display payment icons
	 */
	public function get_icon() {
		$icons_str = '';

		$available_card_brands = array();

		if ( WC()->cart ) {
			$total                 = WC()->cart->get_total( '' );
			$currency              = get_woocommerce_currency();
			$total                 = $this->get_revolut_order_total( $total, $currency );
			$available_card_brands = $this->get_available_card_brands( $total, $currency );
		}

		if ( in_array( 'amex', $available_card_brands, true ) ) {
			$icons_str .= '<img class="revolut-card-gateway-icon-amex" src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/amex.svg" style="margin-left:2px" alt="Amex" />';
		}

		$icons_str .= '<img class="revolut-card-gateway-icon-visa" src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/visa.svg" style="margin-left:2px" alt="Visa" />';
		$icons_str .= '<img class="revolut-card-gateway-icon-mastercard" src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/mastercard.svg" alt="MasterCard" />';

		return apply_filters( 'woocommerce_gateway_icon', $icons_str, $this->id );
	}

	/**
	 * Grab selected payment token from Request
	 *
	 * @param int $wc_token_id WooCommerce payment token id.
	 * @return string
	 * @throws Exception Exception.
	 */
	public function get_selected_payment_token( $wc_token_id ) {
		$wc_token          = WC_Payment_Tokens::get( $wc_token_id );
		$payment_method_id = $wc_token->get_token();

		if ( empty( $payment_method_id ) || $wc_token->get_user_id() !== get_current_user_id() ) {
			throw new Exception( 'Can not process payment token' );
		}

		return $wc_token;
	}

	/**
	 * Add public_id field and logo on card form
	 *
	 * @param String $public_id            Revolut public id.
	 * @param String $merchant_public_key  Revolut public key.
	 * @param String $display_tokenization Available saved card tokens.
	 *
	 * @return string
	 */
	public function generate_inline_revolut_form( $public_id, $merchant_public_key, $display_tokenization ) {
		if ( ! in_array( get_woocommerce_currency(), $this->card_payments_currency_list, true ) ) {
			return get_woocommerce_currency() . ' currency is not available for card payments';
		}

		$total               = WC()->cart->get_total( '' );
		$currency            = get_woocommerce_currency();
		$total               = $this->get_revolut_order_total( $total, $currency );
		$mode                = $this->api_settings->get_option( 'mode' );
		$hide_fieldset       = $this->get_option( 'card_widget_type' ) === 'popup' || get_query_var( 'pay_for_order' ) ? 'height:0px;padding:0' : '';
		$shipping_total      = $this->get_cart_total_shipping();
		$hide_payment_method = ! empty( $hide_fieldset ) && ! $display_tokenization ? true : false;

		$display_banner = $this->api_settings->get_option( 'disable_banner' ) === 'yes' ? '<div id="revolut-upsell-banner"></div>' : '';

		$cardholder_name_field = '';

		if ( 'yes' === $this->get_option( 'enable_cardholder_name', 'yes' ) && ! $hide_fieldset ) {
			$cardholder_name_field .= '<p class="form-row form-row-first validate-required" id="cardholder-name" data-priority="10" style="display: block;width:100%;float:none">';
			$cardholder_name_field .= '<span class="woocommerce-input-wrapper"><input type="text" class="input-text" name="wc-revolut-cardholder-name" id="wc-revolut-cardholder-name" placeholder="Cardholder name" autocomplete="cardholder" required></span>';
			$cardholder_name_field .= '</p>';
		}

		return '<fieldset id="wc-' . $this->id . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;' . $hide_fieldset . '"> ' . $cardholder_name_field . '
        <div style="background: ' . $this->get_option( 'widget_background_color' ) . ';' . $hide_fieldset . '" id="woocommerce-revolut-card-element" data-mode="' . $mode . '" data-shipping-total="' . $shipping_total . '" data-currency="' . $currency . '" data-total="' . $total . '" data-textcolor="' . $this->get_option( 'widget_text_color' ) . '" data-widget-type="' . $this->get_option( 'card_widget_type' ) . '" data-hide-payment-method="' . $hide_payment_method . '" data-locale="' . $this->get_lang_iso_code() . '" data-public-id="' . $public_id . '" data-merchant-public-key="' . $merchant_public_key . '" data-save-payment-for="' . $this->save_payment_method_for . '" data-payment-method-save-is-mandatory="' . $this->is_save_payment_method_mandatory() . '"></div>' . $this->getSvgImage() . $display_banner . '</fieldset>';
	}

	/**
	 * Check if cart contains subscription.
	 */
	public function cart_contains_subscription() {
		try {
			$cart_contains_subscription = WC_Subscriptions_Cart::cart_contains_subscription();
		} catch ( Exception $e ) {
			$cart_contains_subscription = false;
		}

		return $cart_contains_subscription;
	}

	/**
	 * Check is payment method available.
	 */
	public function is_available() {
		if ( is_add_payment_method_page() && ! $this->revolut_saved_cards ) {
			return false;
		}

		return 'yes' === $this->enabled;
	}

	/**
	 * Check currency support for card payments
	 */
	public function check_currency_support() {
		if ( ! in_array( get_woocommerce_currency(), $this->card_payments_currency_list, true ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check if saving payment method is mandatory
	 */
	public function is_save_payment_method_mandatory() {

		if ( is_add_payment_method_page() ) {
			return true;
		}

		if ( ! class_exists( 'WC_Subscriptions_Order' ) ) {
			return false;
		}

		return get_query_var( 'change_payment_method' ) || $this->cart_contains_subscription();
	}

	/**
	 * Displays a checkbox to allow users to save payment methods.
	 */
	public function save_payment_method_checkbox() {
		if ( $this->get_option( 'card_widget_type' ) !== 'popup' && ! $this->is_save_payment_method_mandatory() && $this->revolut_saved_cards && $this->check_currency_support() && get_current_user_id() ) {
			return '<p class="form-row woocommerce-SavedPaymentMethods-saveNew revolut-payment-method-save">
				<input id="wc-' . $this->id . '-new-payment-method" name="wc-' . $this->id . '-new-payment-method" type="checkbox" value="' . $this->save_payment_method_for . '" style="width:auto;" />
				<label for="wc-' . $this->id . '-new-payment-method">' . __( 'Save payment information to my account for future purchases.', 'revolut-gateway-for-woocommerce' ) . '</label>
			</p>';
		}

		return '';
	}

	/**
	 * Displays a checkbox to allow users to update all subs payments with new payment.
	 */
	public function display_update_subs_payment_checkout() {
		if ( ! class_exists( 'WC_Subscriptions_Order' ) ) {
			return false;
		}

		if (
			wcs_user_has_subscription( get_current_user_id(), '', array( 'active' ) ) &&
			is_add_payment_method_page()
		) {
			$label = wp_kses_post( __( 'Update the Payment Method used for all of my active subscriptions.', 'revolut-gateway-for-woocommerce' ) );
			$id    = sprintf( 'wc-%1$s-update-subs-payment-method-card', $this->id );
			woocommerce_form_field(
				$id,
				array(
					'type'    => 'checkbox',
					'label'   => $label,
					'default' => false,
				)
			);
		}
	}

	/**
	 * Revolut logo in SVG format.
	 */
	public function getSvgImage() {
		return '<svg width="94" height="17" viewBox="0 0 94 17" fill="' . $this->get_option( 'revolut_logo_color' ) . '" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.68302 12V9.714H3.22202C4.49102 9.714 5.30102 8.895 5.30102 7.689C5.30102 6.519 4.50902 5.7 3.22202 5.7H0.900024V12H1.68302ZM1.68302 6.411H3.12302C3.96902 6.411 4.49102 6.879 4.49102 7.689C4.49102 8.535 3.99602 8.994 3.12302 8.994H1.68302V6.411Z" />
                    <path d="M7.92129 12.081C9.36129 12.081 10.0003 11.019 10.0003 9.705C10.0003 8.382 9.34329 7.32 7.92129 7.32C6.49929 7.32 5.84229 8.382 5.84229 9.705C5.84229 11.028 6.48129 12.081 7.92129 12.081ZM7.92129 11.406C6.99429 11.406 6.60729 10.695 6.60729 9.705C6.60729 8.724 6.98529 7.995 7.92129 7.995C8.85729 7.995 9.23529 8.715 9.23529 9.705C9.23529 10.695 8.85729 11.406 7.92129 11.406Z" />
                    <path d="M12.534 12L13.641 8.427H13.677L14.757 12H15.54L16.836 7.491V7.401H16.071L15.153 10.974H15.117L14.037 7.401H13.263L12.183 10.974H12.147L11.229 7.401H10.446V7.491L11.751 12H12.534Z" />
                    <path d="M19.374 12.081C20.355 12.081 21.165 11.514 21.291 10.668H20.535C20.409 11.154 19.977 11.406 19.374 11.406C18.519 11.406 18.078 10.803 18.078 9.93H21.354V9.561C21.354 8.283 20.661 7.32 19.365 7.32C17.943 7.32 17.286 8.373 17.286 9.705C17.286 11.055 18.015 12.081 19.374 12.081ZM18.087 9.3C18.114 8.58 18.501 7.995 19.365 7.995C20.184 7.995 20.571 8.571 20.571 9.3H18.087Z" />
                    <path d="M23.1878 12V9.768C23.1878 8.688 23.5838 8.076 24.4568 8.076H24.8168V7.347H24.4028C23.8358 7.347 23.3768 7.662 23.1878 8.175H23.1518L23.0888 7.401H22.4318V12H23.1878Z" />
                    <path d="M27.3109 12.081C28.2919 12.081 29.1019 11.514 29.2279 10.668H28.4719C28.3459 11.154 27.9139 11.406 27.3109 11.406C26.4559 11.406 26.0149 10.803 26.0149 9.93H29.2909V9.561C29.2909 8.283 28.5979 7.32 27.3019 7.32C25.8799 7.32 25.2229 8.373 25.2229 9.705C25.2229 11.055 25.9519 12.081 27.3109 12.081ZM26.0239 9.3C26.0509 8.58 26.4379 7.995 27.3019 7.995C28.1209 7.995 28.5079 8.571 28.5079 9.3H26.0239Z" />
                    <path d="M31.9617 12.081C32.6457 12.081 33.1047 11.766 33.3477 11.361H33.3837L33.4827 12H34.1397V5.34H33.3837V8.04H33.3477C33.1047 7.635 32.6457 7.32 31.9617 7.32C30.6657 7.32 30.0177 8.337 30.0087 9.696C30.0177 11.181 30.7557 12.081 31.9617 12.081ZM32.0877 11.406C31.1337 11.406 30.7737 10.623 30.7737 9.696C30.7737 8.778 31.1337 7.995 32.0877 7.995C33.0057 7.995 33.4017 8.787 33.4017 9.696C33.4017 10.614 33.0057 11.406 32.0877 11.406Z" />
                    <path d="M40.1847 12.081C41.3907 12.081 42.1287 11.181 42.1377 9.696C42.1287 8.337 41.4807 7.32 40.1847 7.32C39.5007 7.32 39.0417 7.635 38.7987 8.04H38.7627V5.34H38.0067V12H38.6637L38.7627 11.361H38.7987C39.0417 11.766 39.5007 12.081 40.1847 12.081ZM40.0587 11.406C39.1407 11.406 38.7447 10.614 38.7447 9.696C38.7447 8.787 39.1407 7.995 40.0587 7.995C41.0127 7.995 41.3727 8.778 41.3727 9.696C41.3727 10.623 41.0127 11.406 40.0587 11.406Z" />
                    <path d="M44.3326 14.061L46.8796 7.491V7.401H46.0875L44.7375 11.01H44.7015L43.3065 7.401H42.4965V7.491L44.3415 11.946L44.3505 11.964L43.5676 13.971V14.061H44.3326Z" />
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M59.0134 13.0369L56.793 9.12191C58.1976 8.60604 58.8939 7.70677 58.8939 6.39711C58.8954 4.79652 57.6336 3.77778 55.6244 3.77778H51.6981V13.0369H53.4318V9.28037H54.9032L57.0304 13.0369H59.0134ZM55.6244 5.31189C56.6488 5.31189 57.1613 5.72178 57.1613 6.52858C57.1613 7.33537 56.6483 7.74526 55.6244 7.74526H53.4318V5.31189H55.6244ZM82.044 13.0371V3.9604H80.3892V13.0371H82.044ZM78.5549 7.06465C77.8585 6.44281 77.018 6.13889 76.0461 6.13889C75.0876 6.13889 74.247 6.44331 73.5502 7.06465C72.8538 7.673 72.5 8.51977 72.5 9.60449C72.5 10.6892 72.8538 11.5355 73.5502 12.1574C74.2465 12.7657 75.0876 13.0701 76.0461 13.0701C77.018 13.0701 77.8585 12.7657 78.5549 12.1574C79.2646 11.5355 79.6185 10.6892 79.6185 9.60449C79.6185 8.51977 79.2641 7.673 78.5549 7.06465ZM74.7332 10.9931C74.366 10.6362 74.1811 10.1729 74.1811 9.60449C74.1811 9.03565 74.3645 8.57276 74.7332 8.22885C75.1005 7.87195 75.5346 7.69998 76.0456 7.69998C76.557 7.69998 77.0041 7.87195 77.3719 8.22885C77.752 8.57276 77.9368 9.03565 77.9368 9.60449C77.9368 10.1733 77.7535 10.6362 77.3719 10.9931C77.0046 11.3371 76.5576 11.509 76.0456 11.509C75.5346 11.509 75.1015 11.3371 74.7332 10.9931ZM87.3855 10.0976V6.47599H89.0443V10.3755C89.0443 11.8566 88.1001 13.2218 86.0384 13.2218H86.0255C83.9509 13.2218 83.0052 11.8861 83.0052 10.3755V6.47599H84.6631V10.0976C84.6631 10.9708 85.1191 11.5487 86.0255 11.5487C86.9172 11.5487 87.3855 10.9703 87.3855 10.0976ZM70.6804 6.47599L69.0255 10.9204L67.3707 6.47599H65.6107L68.2514 13.0368H69.8017L72.4418 6.47599H70.6804ZM64.7265 7.2166C65.3177 7.83844 65.6196 8.61874 65.6196 9.571H65.6191V10.1533H60.603C60.708 11.1586 61.4044 11.7934 62.3891 11.7934C63.1901 11.7934 63.7818 11.3965 64.1883 10.5897L65.4223 11.3041C64.8063 12.5872 63.7952 13.2221 62.3629 13.2221C61.4306 13.2221 60.6426 12.9046 59.9855 12.2563C59.3417 11.6085 59.0135 10.7752 59.0135 9.75644C59.0135 8.73771 59.3411 7.91792 59.9984 7.26959C60.6684 6.62175 61.4832 6.29084 62.4417 6.29084C63.3745 6.29084 64.1352 6.59476 64.7265 7.2166ZM64.0956 8.93616C63.9385 8.08937 63.3204 7.56051 62.3882 7.56051C61.5342 7.56051 60.8522 8.15586 60.602 8.93616H64.0956ZM89.9207 11.2769C89.9207 12.3511 90.7845 13.2214 91.8496 13.2214H92.9945V11.7398H92.142C91.8288 11.7398 91.5755 11.4848 91.5755 11.1699V7.82728H92.9945V6.47813H91.5755V4.70308H89.9207V11.2769Z" />
                </svg>';
	}
}
