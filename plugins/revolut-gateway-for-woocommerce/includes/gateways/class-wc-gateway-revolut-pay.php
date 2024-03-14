<?php
/**
 * Revolut Pay
 *
 * Provides a gateway to accept payments through Revolut Pay.
 *
 * @package WooCommerce
 * @category Payment Gateways
 * @author Revolut
 * @since 2.0
 */

/**
 * WC_Gateway_Revolut_Pay class
 */
class WC_Gateway_Revolut_Pay extends WC_Payment_Gateway_Revolut {

	const GATEWAY_ID = 'revolut_pay';
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id           = self::GATEWAY_ID;
		$this->method_title = __( 'Revolut Gateway - Revolut Pay', 'revolut-gateway-for-woocommerce' );
		$this->tab_title    = __( 'Revolut Pay', 'revolut-gateway-for-woocommerce' );

		$this->default_title = __( 'Revolut Pay', 'revolut-gateway-for-woocommerce' );
		/* translators:%1s: %$2s: */
		$this->method_description = sprintf( __( 'Accept payments easily and securely via %1$sRevolut%2$s.', 'revolut-gateway-for-woocommerce' ), '<a href="https://www.revolut.com/business/online-payments">', '</a>' );

		$this->title       = __( 'Revolut Pay', 'revolut-gateway-for-woocommerce' );
		$this->description = $this->get_option( 'description' );

		parent::__construct();

		if ( get_option( 'woocommerce_revolut_pay_settings' ) === false ) {
			$this->add_default_options();
		}

		$this->activate_default_express_checkout();

		if ( ! $this->is_revolut_cc_gateway_active() ) {
			$this->init_scripts();
		}

		add_action( 'wp', array( $this, 'check_revolut_pay_payment_result' ) );
		add_filter( 'wc_revolut_settings_nav_tabs', array( $this, 'admin_nav_tab' ), 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wc_revolut_pay_enqueue_scripts' ) );
		add_action( 'woocommerce_after_add_to_cart_quantity', array( $this, 'display_payment_request_button_html' ), 1 );
		add_action( 'woocommerce_proceed_to_checkout', array( $this, 'display_payment_request_button_html' ), 1 );
		add_action( 'wc_ajax_revolut_payment_request_load_order_data', array( $this, 'revolut_payment_request_ajax_load_order_data' ) );
		add_action( 'wc_ajax_revolut_payment_request_get_express_checkout_params', array( $this, 'revolut_payment_request_ajax_get_express_checkout_params' ) );
	}

	/**
	 * Get express checkout params
	 */
	public function check_revolut_pay_payment_result() {
		if ( empty( get_query_var( '_rp_oid' ) ) ) {
			return;
		}

		$public_id = get_query_var( '_rp_oid' );

		global $wpdb;
		$wc_order_id      = $wpdb->get_row( $wpdb->prepare( 'SELECT wc_order_id, HEX(order_id) as order_id FROM ' . $wpdb->prefix . "wc_revolut_orders WHERE public_id=UNHEX(REPLACE(%s, '-', ''))", array( $public_id ) ), ARRAY_A ); // db call ok; no-cache ok.
		$revolut_order_id = $this->uuid_dashes( $wc_order_id['order_id'] );

		if ( ! empty( $wc_order_id ) && empty( $wc_order_id['wc_order_id'] ) && $this->is_order_payment_page() ) {
			$wc_order_id['wc_order_id'] = $this->wc_revolut_get_current_order_id();
		}

		if ( empty( $revolut_order_id ) || $this->is_pending_payment( $revolut_order_id ) ) {
			return;
		}

		if ( empty( $wc_order_id ) || empty( $wc_order_id['wc_order_id'] ) ) {
			// check if fast checkout.
			if ( ! empty( $wc_order_id['order_id'] ) ) {
				$temp_session = $wpdb->get_row( $wpdb->prepare( 'SELECT temp_session FROM ' . $wpdb->prefix . 'wc_revolut_temp_session WHERE order_id=%s', array( $this->uuid_dashes( $wc_order_id['order_id'] ) ) ), ARRAY_A ); // db call ok; no-cache ok.

				if ( ! empty( $temp_session ) ) {
					$this->log_error( 'order processing FC - public_id: ' . $public_id );
					return $this->process_revolut_pay_fc_payment( $public_id );
				}
			}

			return;
		}

		$wc_order_id = $wc_order_id['wc_order_id'];

		$wc_order = wc_get_order( $wc_order_id );

		if ( empty( $wc_order->get_id() ) ) {
			return;
		}

		$this->log_error( 'order processing - public_id: ' . $public_id . ' - wc_order_id: ' . $wc_order_id );

		$this->process_payment( $wc_order_id, $public_id, false, '', false, true );
	}

	/**
	 * Process Revolut Pay Fast Checkout payment
	 *
	 * @param string $revolut_public_id Revolut order public id.
	 * @throws Exception Exception.
	 */
	public function process_revolut_pay_fc_payment( $revolut_public_id ) {
		try {
			if ( ! empty( get_query_var( '_rp_fr' ) ) ) {
				wc_add_notice( get_query_var( '_rp_fr' ), 'error' );
				return;
			}

			$order_data = $this->load_order_data( $revolut_public_id );

			$revolut_order_id = $this->get_revolut_order_by_public_id( $revolut_public_id );
			if ( empty( $order_data ) ) {
				return;
			}

			$address                  = $order_data['address_info'];
			$selected_shipping_option = $order_data['selected_shipping_option'];

			if ( WC()->cart->is_empty() ) {
				$this->convert_revolut_order_metadata_into_wc_session( $revolut_order_id );
			}

			if ( WC()->cart->is_empty() ) {
				throw new Exception( 'Cannot initialize cart' );
			}

			if ( ! defined( 'WOOCOMMERCE_CHECKOUT' ) ) {
				define( 'WOOCOMMERCE_CHECKOUT', true );
			}

			$wc_order_data = $this->format_wc_order_details(
				$address,
				WC()->cart->needs_shipping(),
				self::GATEWAY_ID
			);

			$wc_order_data['shipping_method']         = array( $selected_shipping_option );
			$wc_order_data['revolut_create_wc_order'] = 1;
			$wc_order_data['revolut_pay_redirected']  = 1;
			$wc_order_data['is_express_checkout']     = 1;
			$wc_order_data['payment_method']          = self::GATEWAY_ID;
			$wc_order_data['revolut_public_id']       = $revolut_public_id;

			$_POST                = $wc_order_data;
			$_POST['_wpnonce']    = wp_create_nonce( 'woocommerce-process_checkout' );
			$_REQUEST['_wpnonce'] = $_POST['_wpnonce']; // phpcs:ignore
			WC()->checkout()->process_checkout();
		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
		}
	}

	/**
	 * Get express checkout params
	 */
	public function revolut_payment_request_ajax_get_express_checkout_params() {
		check_ajax_referer( 'wc-revolut-get-express-checkout-params', 'security' );

		try {
			wp_send_json(
				array(
					'success'           => true,
					'revolut_public_id' => $this->create_express_checkout_public_id(),
					'checkout_nonce'    => wp_create_nonce( 'woocommerce-process_checkout' ),
				)
			);
		} catch ( Exception $e ) {
			wp_send_json( array( 'success' => false ) );
			$this->log_error( $e );
		}
	}

	/**
	 * Load express checkout information from api
	 */
	public function activate_default_express_checkout() {
		try {
			if ( 'yes' !== $this->get_option( 'revolut_pay_express_checkout_activate_default' ) && 'yes' === $this->get_option( 'enabled' ) && empty( $this->get_option( 'revolut_pay_button_locations' ) ) && $this->is_revolut_payment_request_gateway_active() ) {
				$this->update_option( 'revolut_pay_button_locations', array( 'product', 'cart' ) );
			}
			$this->update_option( 'revolut_pay_express_checkout_activate_default', 'yes' );
		} catch ( Exception $e ) {
			$this->log_error( $e->getMessage() );
		}
	}

	/**
	 * Load express checkout information from api
	 *
	 * @param string $revolut_public_id Revolut order public id.
	 */
	public function load_order_data( $revolut_public_id ) {
		try {
			$order_id = $this->get_revolut_order_by_public_id( $revolut_public_id );

			$revolut_order = $this->api_client->get( "/orders/{$order_id}" );

			$shipping_address = $revolut_order['shipping_address'];

			$this->log_info( 'load_order_data' );
			$this->log_info( $shipping_address );

			$address_info['fullname'] = ! empty( $revolut_order['full_name'] ) ? $revolut_order['full_name'] : '';
			$address_info['email']    = ! empty( $revolut_order['email'] ) ? $revolut_order['email'] : '';
			$address_info['phone']    = ! empty( $revolut_order['phone'] ) ? $revolut_order['phone'] : '';

			$shipping_address['recipient'] = $address_info['fullname'];
			$shipping_address['phone']     = $address_info['phone'];
			$shipping_address              = $this->convert_revolut_address_to_express_checkout_address( $shipping_address );

			$address_info['shippingAddress'] = $shipping_address;
			$address_info['billingAddress']  = $address_info['shippingAddress'];

			$selected_shipping_option = 0;

			if ( ! empty( $revolut_order['delivery_method'] ) ) {
				$selected_shipping_option = $revolut_order['delivery_method']['ref'];
				$this->get_shipping_options( $shipping_address );
				$this->update_shipping_method( array( $selected_shipping_option ) );
			}

			return array(
				'address_info'             => $address_info,
				'selected_shipping_option' => $selected_shipping_option,
			);
		} catch ( Exception $e ) {
			return array();
		}
	}

	/**
	 * Load express checkout information from api
	 */
	public function revolut_payment_request_ajax_load_order_data() {
		try {
			check_ajax_referer( 'wc-revolut-load-order-data', 'security' );

			$revolut_public_id = isset( $_POST['revolut_public_id'] ) ? wc_clean( wp_unslash( $_POST['revolut_public_id'] ) ) : '';

			wp_send_json(
				$this->load_order_data( $revolut_public_id )
			);
		} catch ( Exception $e ) {
			wp_send_json( array( 'success' => false ) );
			$this->log_info( 'load_order_data_error:' );
			$this->log_error( $e );
		}
	}

	/**
	 * Display payment request button html
	 */
	public function display_payment_request_button_html() {
		if ( 'yes' !== $this->enabled || ! $this->page_supports_payment_request_button( $this->get_option( 'revolut_pay_button_locations' ) ) || ! $this->is_shipping_required() ) {
			return false;
		}

		if ( ! $this->is_revolut_payment_request_gateway_active() ) {
			?>
			<div class="wc-revolut-pay-express-checkout-instance" id="wc-revolut-pay-express-checkout-container" style="clear:both;padding-top:1.5em;">
				<div id="revolut-pay-express-checkout-button"></div>
				<p id="wc-revolut-pay-express-checkout-button-separator" style="text-align:center;margin-bottom:1.5em;">&mdash;&nbsp;<?php echo esc_html( __( 'OR', 'revolut-gateway-for-woocommerce' ) ); ?>
					&nbsp;&mdash;</p>
			</div>
			<?php
			return;
		}

		?>
		<div class="wc-revolut-pay-express-checkout-instance" id="wc-revolut-pay-express-checkout-container" style="clear:both;padding-top:1.5em;">
			<div id="revolut-pay-express-checkout-button"></div>
		</div>
		<?php
	}

	/**
	 * Add script to load card form
	 */
	public function wc_revolut_pay_enqueue_scripts() {
		wp_localize_script(
			'revolut-woocommerce',
			'revolut_pay_button_style',
			array(
				'revolut_pay_button_theme'  => $this->get_option( 'revolut_pay_button_theme' ),
				'revolut_pay_button_size'   => $this->get_option( 'revolut_pay_button_size' ),
				'revolut_pay_button_radius' => $this->get_option( 'revolut_pay_button_radius' ),
				'revolut_pay_origin_url'    => str_replace( array( 'https://', 'http://' ), '', get_site_url() ),
			)
		);

		if ( 'yes' !== $this->enabled || ! $this->page_supports_payment_request_button( $this->get_option( 'revolut_pay_button_locations' ) ) || ! $this->is_shipping_required() ) {
			return false;
		}

		wp_register_script( 'revolut-core', $this->api_client->base_url . '/embed.js', false, WC_GATEWAY_REVOLUT_VERSION, true );
			wp_register_script(
				'revolut-woocommerce-payment-request',
				plugins_url( 'assets/js/revolut-payment-request.js', WC_REVOLUT_MAIN_FILE ),
				array(
					'revolut-core',
					'jquery',
				),
				WC_GATEWAY_REVOLUT_VERSION,
				true
			);

		wp_localize_script(
			'revolut-woocommerce-payment-request',
			'wc_revolut_payment_request_params',
			$this->get_wc_revolut_payment_request_params()
		);

		wp_enqueue_script( 'revolut-woocommerce-payment-request' );
	}

	/**
	 * Check if card payments is active.
	 */
	public function is_revolut_cc_gateway_active() {
		$revolut_cc_gateway_options = get_option( 'woocommerce_revolut_cc_settings' );
		return isset( $revolut_cc_gateway_options['enabled'] ) && 'yes' === $revolut_cc_gateway_options['enabled'];
	}

	/**
	 * Check if the request payments active.
	 */
	public function is_revolut_payment_request_gateway_active() {
		$woocommerce_revolut_payment_request_settings = get_option( 'woocommerce_revolut_payment_request_settings' );
		return isset( $woocommerce_revolut_payment_request_settings['enabled'] ) && 'yes' === $woocommerce_revolut_payment_request_settings['enabled'] && $this->api_settings->get_option( 'mode' ) !== 'sandbox';
	}

	/**
	 * Supported functionality
	 */
	public function init_supports() {
		parent::init_supports();
		$this->supports[] = 'refunds';
	}

	/**
	 * Initialize Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'                      => array(
				'title'       => __( 'Enable/Disable', 'revolut-gateway-for-woocommerce' ),
				'label'       => __( 'Enable ', 'revolut-gateway-for-woocommerce' ) . $this->method_title,
				'type'        => 'checkbox',
				'description' => __( 'This controls whether or not this gateway is enabled within WooCommerce.', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'yes',
				'desc_tip'    => true,
			),
			'revolut_pay_button_locations' => array(
				'title'             => __( 'Revolut Pay Express Checkout', 'revolut-gateway-for-woocommerce' ),
				'type'              => 'multiselect',
				'description'       => __( 'Select where you would like Revolut Pay Button to be displayed as express checkout button', 'revolut-gateway-for-woocommerce' ),
				'desc_tip'          => true,
				'class'             => 'wc-enhanced-select',
				'options'           => array(
					'product' => __( 'Product', 'revolut-gateway-for-woocommerce' ),
					'cart'    => __( 'Cart', 'revolut-gateway-for-woocommerce' ),
				),
				'default'           => array(),
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select pages', 'revolut-gateway-for-woocommerce' ),
				),
			),
			'revolut_pay_button_theme'     => array(
				'title'       => __( 'Revolut Pay Button Theme', 'revolut-gateway-for-woocommerce' ),
				'label'       => __( 'Button Theme', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Select the button theme you would like to show.', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'dark',
				'desc_tip'    => true,
				'options'     => array(
					'dark'           => __( 'Dark', 'revolut-gateway-for-woocommerce' ),
					'light'          => __( 'Light', 'revolut-gateway-for-woocommerce' ),
					'light-outlined' => __( 'Light-Outline', 'revolut-gateway-for-woocommerce' ),
				),
			),
			'revolut_pay_button_size'      => array(
				'title'       => __( 'Revolut Pay Button Size', 'revolut-gateway-for-woocommerce' ),
				'label'       => __( 'Button Size', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Select the button size you would like to show.', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'large',
				'desc_tip'    => true,
				'options'     => array(
					'large' => __( 'Large', 'revolut-gateway-for-woocommerce' ),
					'small' => __( 'Small', 'revolut-gateway-for-woocommerce' ),
				),
			),
			'revolut_pay_button_radius'    => array(
				'title'       => __( 'Revolut Pay Button Radius', 'revolut-gateway-for-woocommerce' ),
				'label'       => __( 'Button Radius', 'revolut-gateway-for-woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Select the button radius you would like to show.', 'revolut-gateway-for-woocommerce' ),
				'default'     => 'none',
				'desc_tip'    => true,
				'options'     => array(
					'small' => __( 'Small', 'revolut-gateway-for-woocommerce' ),
					'large' => __( 'Large', 'revolut-gateway-for-woocommerce' ),
					'none'  => __( 'None', 'revolut-gateway-for-woocommerce' ),
				),
			),
		);
	}

	/**
	 * Display Revolut Pay icon
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
		$icons_str .= '<img class="revolut-card-gateway-icon-mastercard" src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/mastercard.svg" style="margin-left:2px" alt="MasterCard" />';
		$icons_str .= '<img class="rev-pay-v2" src="' . WC_REVOLUT_PLUGIN_URL . '/assets/images/revolut.svg" alt="Revolut Pay" />';

		return apply_filters( 'woocommerce_gateway_icon', $icons_str, $this->id );
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
		$total = WC()->cart->get_total( '' );

		if ( get_query_var( 'pay_for_order' ) && ! empty( get_query_var( 'key' ) ) ) {
			global $wp;
			$order = wc_get_order( wc_clean( $wp->query_vars['order-pay'] ) );
			$total = $order->get_total();
		}

		$currency                       = get_woocommerce_currency();
		$total                          = $this->get_revolut_order_total( $total, $currency );
		$mode                           = $this->api_settings->get_option( 'mode' );
		$shipping_total                 = $this->get_cart_total_shipping();
		$mobile_redirect_url            = $this->is_order_payment_page() ? $this->wc_revolut_get_checkout_payment_url() : wc_get_checkout_url();
		$revolut_pay_v2_class_indicator = '';

		if ( ! empty( $merchant_public_key ) ) {
			$revolut_pay_v2_class_indicator = 'revolut-pay-v2';
		}

		return '<div id="woocommerce-revolut-pay-element" class="revolut-pay ' . $revolut_pay_v2_class_indicator . '" data-redirect-url = "' . $mobile_redirect_url . '" data-mode="' . $mode . '" data-shipping-total="' . $shipping_total . '" data-currency="' . $currency . '" data-total="' . $total . '" data-textcolor="" data-locale="' . $this->get_lang_iso_code() . '" data-public-id="' . $public_id . '"  data-merchant-public-key="' . $merchant_public_key . '"></div>
		<input type="hidden" id="wc_' . $this->id . '_payment_nonce" name="wc_' . $this->id . '_payment_nonce" />';
	}
}
