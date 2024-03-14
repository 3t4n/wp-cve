<?php
/**
 * Payment gateway functionality.
 *
 * @package     \StorePress\TwoCheckoutPaymentGateway
 */

namespace StorePress\TwoCheckoutPaymentGateway;

defined( 'ABSPATH' ) || die( 'Keep Silent' );

use WC_Payment_Gateway;
use WC_Logger_Interface;
use WC_Order;

/**
 * StorePress 2Checkout Payment Gateway base class.
 *
 * Extended by individual payment gateway style to handle payments.
 *
 * @class       Payment_Gateway
 * @extends     WC_Payment_Gateway
 */
class Payment_Gateway extends WC_Payment_Gateway {

	/**
	 * 2Checkout merchant code.
	 *
	 * @var string
	 */
	protected string $merchant_code;

	/**
	 * 2Checkout merchant secret key.
	 *
	 * @var string
	 */
	protected string $secret_key;
	/**
	 * 2Checkout buy link secret word.
	 *
	 * @var string
	 */
	protected string $buy_link_secret_word;
	/**
	 * Is Debugging Mode
	 *
	 * @var boolean
	 */
	protected bool $debug;
	/**
	 * Is Demo Mode
	 *
	 * @var boolean
	 */
	protected bool $demo;
	/**
	 * Get Icon Style
	 *
	 * @var string
	 */
	protected string $icon_style;
	/**
	 * Get Icon Width in percent (%). for Classic checkout.
	 *
	 * @var string
	 */
	protected string $icon_width;
	/**
	 * Get 2Checkout API
	 *
	 * @var API
	 */
	protected API $api;
	/**
	 * Get WooCommerce Log.
	 *
	 * @var WC_Logger_Interface
	 */
	protected WC_Logger_Interface $log;

	/**
	 * Init gateway base class.
	 */
	public function __construct() {

		$this->id                 = 'woo-2checkout';
		$this->icon               = woo_2checkout()->images_url() . '/2checkout-dark.svg';
		$this->has_fields         = false;
		$this->method_title       = esc_html__( '2Checkout Payment Gateway', 'woo-2checkout' );
		$this->method_description = esc_html__( '2Checkout accept mobile and online payments from customers worldwide.', 'woo-2checkout' );

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Define user set variables.
		$this->title                = $this->get_option( 'title', esc_html__( '2Checkout', 'woo-2checkout' ) );
		$this->description          = $this->get_option( 'description', esc_html__( 'Pay via 2Checkout. Accept Credit Cards, PayPal and Debit Cards.', 'woo-2checkout' ) );
		$this->order_button_text    = $this->get_option( 'order_button_text', esc_html__( 'Proceed to 2Checkout', 'woo-2checkout' ) );
		$this->merchant_code        = $this->get_option( 'merchant_code' );
		$this->secret_key           = htmlspecialchars_decode( $this->get_option( 'secret_key' ) );
		$this->buy_link_secret_word = htmlspecialchars_decode( $this->get_option( 'buy_link_secret_word' ) );
		$this->debug                = wc_string_to_bool( $this->get_option( 'debug', 'no' ) );
		$this->demo                 = wc_string_to_bool( $this->get_option( 'demo', 'yes' ) );
		$this->icon_style           = $this->get_option( 'icon_style', 'dark' );
		$this->icon_width           = $this->get_option( 'icon_width', '50' );
		$this->log                  = wc_get_logger();

		// Supports.
		$this->supports = array( 'products' );

		if ( $this->demo ) {

			$demo_description = '<br />' . sprintf( /* translators: Demo mode test payment. %s: Test payment card link. */ __( '<strong>DEMO MODE ENABLED.</strong> Use a %s', 'woo-2checkout' ), '<a target="_blank" href="https://verifone.cloud/docs/2checkout/Documentation/09Test_ordering_system/01Test_payment_methods">test payment cards</a>' );

			$this->description .= $demo_description;
		}

		// Show admin notices.
		$this->admin_notices();

		// Load WordPress Hooks.
		$this->hook();

		do_action( 'woo_2checkout_gateway_init', $this );
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	public function hook() {

		add_action(
			'woocommerce_update_options_payment_gateways_woo-2checkout',
			array(
				$this,
				'process_admin_options',
			)
		);

		// Scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		/**
		 * Will return to site/?wc-api=woo-2checkout-gateway-return
		 * Will return to site/?wc-api=woo-2checkout-ipn-response
		 * Will return to site/?wc-api=woo-2checkout-lcn-response
		 * Will return to site/?wc-api=woo-2checkout-ins-response
		 */

		add_action( 'woocommerce_api_woo-2checkout-gateway-return', array( $this, 'process_gateway_redirect' ) );
		add_action( 'woocommerce_api_woo-2checkout-ipn-response', array( $this, 'process_gateway_ipn_response' ) );
		add_action( 'woocommerce_api_woo-2checkout-lcn-response', array( $this, 'process_gateway_lcn_response' ) );
		add_action( 'woocommerce_api_woo-2checkout-ins-response', array( $this, 'process_gateway_ins_response' ) );
		add_action( 'woocommerce_api_woo-2checkout-gateway-return-inline', array( $this, 'process_gateway_return_inline' ) );

		add_action( 'woocommerce_receipt_woo-2checkout', array( $this, 'order_pay_page' ) );
		add_action( 'woocommerce_thankyou_woo-2checkout', array( $this, 'order_received_page' ) );

		// Custom ajax hook.
		add_action( 'wp_ajax_woo-2checkout_order_pay_page', array( $this, 'ajax_order_pay_page' ) );
		add_action( 'wp_ajax_nopriv_woo-2checkout_order_pay_page', array( $this, 'ajax_order_pay_page' ) );

		add_action( 'wp_ajax_woo-2checkout_order_received_page', array( $this, 'ajax_order_received_page' ) );
		add_action( 'wp_ajax_nopriv_woo-2checkout_order_received_page', array( $this, 'ajax_order_received_page' ) );
	}

	/**
	 * Return is this gateway still requires setup to function.
	 *
	 * When this gateway is toggled on via AJAX, if this returns true a
	 * redirect will occur to the settings page instead.
	 *
	 * @return bool
	 */
	public function needs_setup(): bool {
		return ( empty( $this->merchant_code ) || empty( $this->secret_key ) || empty( $this->buy_link_secret_word ) );
	}

	/**
	 * Get gateway ID.
	 *
	 * @return string
	 */
	public function get_id(): string {
		return $this->id;
	}

	/**
	 * Get 2Checkout Rest API
	 *
	 * @return API
	 */
	public function get_api(): API {
		return API::instance( $this->merchant_code, $this->secret_key );
	}

	/**
	 * Return the gateway's icon.
	 *
	 * @return string
	 */
	public function get_icon(): string {

		// Override Icon.
		$icon_url = $this->get_icon_url();

		return sprintf( '<img  class="woo-2checkout-gateway-pay-image" alt="%s" src="%s" style="width: %d%%" />', esc_attr( $this->order_button_text ), esc_url( $icon_url ), absint( $this->icon_width ) );
	}

	/**
	 * Get gateway icon url.
	 *
	 * @return string
	 */
	public function get_icon_url(): string {
		return apply_filters( 'woo_2checkout_icon', sprintf( '%s/2checkout-%s.svg', woo_2checkout()->images_url(), $this->icon_style ) );
	}

	/**
	 * Initialise settings form fields.
	 *
	 * Add an array of fields to be displayed on the gateway's settings screen.
	 */
	public function init_form_fields() {

		$this->form_fields = array();

		$this->form_fields['enabled'] = array(
			'title'   => esc_html__( 'Enable/Disable', 'woo-2checkout' ),
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Enable 2Checkout Payment Gateway', 'woo-2checkout' ),
			'default' => 'yes',
		);

		$this->form_fields['title'] = array(
			'title'       => esc_html__( 'Title', 'woo-2checkout' ),
			'type'        => 'text',
			'description' => esc_html__( 'This controls the title which the user sees during checkout.', 'woo-2checkout' ),
			'default'     => esc_html__( '2Checkout', 'woo-2checkout' ),
			'desc_tip'    => true,
		);

		$this->form_fields['description'] = array(
			'title'       => esc_html__( 'Description', 'woo-2checkout' ),
			'type'        => 'textarea',
			'description' => esc_html__( 'This controls the description which the user sees during checkout.', 'woo-2checkout' ),
			'default'     => esc_html__( 'Pay via 2Checkout. Accept Credit Cards, PayPal and Debit Cards.', 'woo-2checkout' ),
		);

		$this->form_fields['order_button_text'] = array(
			'title'       => esc_html__( 'Order button text', 'woo-2checkout' ),
			'type'        => 'text',
			'description' => esc_html__( 'Checkout order button text.', 'woo-2checkout' ),
			'default'     => esc_html__( 'Proceed to 2Checkout', 'woo-2checkout' ),
			'desc_tip'    => true,
		);

		$this->form_fields['webhook'] = array(
			'title'       => sprintf( '<a href="https://getwooplugins.com/documentation/woocommerce-2checkout/" target="_blank">%s</a>', esc_html__( 'Read How to Setup', 'woo-2checkout' ) ),
			'type'        => 'title',
			'description' => $this->display_admin_settings_webhook_description(),
		);

		$this->form_fields['merchant_code'] = array(
			'title'       => esc_html__( 'Merchant Code', 'woo-2checkout' ),
			'type'        => 'text',
			'default'     => '',
			'desc_tip'    => false,
			'description' => sprintf( /* translators: Webhook URL */ __( 'Please enter 2Checkout <strong>Merchant Code</strong> from <a target="_blank" href="%s">Integrations > Webhooks &amp; API > API Section</a>.', 'woo-2checkout' ), 'https://secure.2checkout.com/cpanel/webhooks_api.php' ),
		);

		$this->form_fields['secret_key'] = array(
			'title'       => esc_html__( 'Secret Key', 'woo-2checkout' ),
			'type'        => 'text',
			'description' => sprintf( /* translators: Webhook URL */ __( 'Please enter 2Checkout <strong>Secret Key</strong> from <a target="_blank" href="%s">Integrations > Webhooks &amp; API > API Section</a>', 'woo-2checkout' ), 'https://secure.2checkout.com/cpanel/webhooks_api.php' ),
			'default'     => '',
			'desc_tip'    => false,
		);

		$this->form_fields['buy_link_secret_word'] = array(
			'title'       => esc_html__( 'Buy Link Secret Word', 'woo-2checkout' ),
			'type'        => 'text',
			'description' => sprintf( /* translators: Webhook URL */ __( 'Please enter 2Checkout <strong>Buy link secret word</strong> from <a target="_blank" href="%s">Integrations > Webhooks &amp; API > Secret word</a> section', 'woo-2checkout' ), 'https://secure.2checkout.com/cpanel/webhooks_api.php' ),
			'default'     => '',
			'desc_tip'    => false,
		);

		$this->form_fields['demo'] = array(
			'title'       => esc_html__( 'Demo Mode', 'woo-2checkout' ),
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Enable Demo Mode', 'woo-2checkout' ),
			'default'     => 'yes',
			'description' => esc_html__( 'This mode allows you to test your setup to make sure everything works as expected without take real payment.', 'woo-2checkout' ),
		);

		$this->form_fields['debug'] = array(
			'title'       => esc_html__( 'Debug Log', 'woo-2checkout' ),
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Enable Logging', 'woo-2checkout' ),
			'default'     => 'no',
			'description' => sprintf( /* translators: WooCommerce Log URL */ __( 'Log 2Checkout events, <strong>DON\'T ALWAYS ENABLE THIS.</strong> You can check this log in %s.', 'woo-2checkout' ), '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . esc_attr( $this->get_id() ) . '-' . sanitize_file_name( wp_hash( $this->get_id() ) ) . '.log' ) ) . '">' . esc_html__( 'System Status &gt; Logs', 'woo-2checkout' ) . '</a>' ),
		);

		$this->form_fields['icon_style'] = array(
			'title'   => esc_html__( 'Gateway Icon Style', 'woo-2checkout' ),
			'type'    => 'select',
			'class'   => 'wc-enhanced-select',
			'label'   => esc_html__( 'Choose Gateway a Icon Style', 'woo-2checkout' ),
			'options' => array(
				'dark'  => esc_html__( 'Dark', 'woo-2checkout' ),
				'light' => esc_html__( 'Light', 'woo-2checkout' ),
			),
			'default' => 'dark',
		);

		$this->form_fields['icon_width'] = array(
			'title'             => esc_html__( 'Gateway Icon Width', 'woo-2checkout' ),
			'type'              => 'number',
			'description'       => esc_html__( 'Gateway Icon Width in %. Limit: 1-100', 'woo-2checkout' ),
			'default'           => '50',
			'desc_tip'          => false,
			'custom_attributes' => array(
				'min'  => '1',
				'max'  => '100',
				'size' => '3',
			),
		);

		$this->form_fields['checkout_type'] = array(
			'title'    => esc_html__( 'Choose checkout type', 'woo-2checkout' ),
			'type'     => 'select',
			'class'    => 'wc-enhanced-select',
			'label'    => esc_html__( 'Choose checkout type', 'woo-2checkout' ),
			'options'  => array(
				'standard'             => esc_html__( 'Standard Checkout ( Process on 2Checkout Site )', 'woo-2checkout' ),
				'popup-after-checkout' => esc_html__( 'Popup After Checkout - Inline Checkout - PRO FEATURE', 'woo-2checkout' ),
				'popup-on-checkout'    => esc_html__( 'Popup During Checkout - Inline Checkout - PRO FEATURE', 'woo-2checkout' ),
				'card'                 => esc_html__( 'On Page Credit Card Only - PRO FEATURE', 'woo-2checkout' ),
			),
			'default'  => 'standard',
			'disabled' => true,
		);

		$this->form_fields = apply_filters( 'woo_2checkout_admin_form_fields', $this->form_fields );
	}

	/**
	 * Generate Select HTML.
	 *
	 * @param string $key  Field key.
	 * @param array  $data Field data.
	 *
	 * @return string
	 */
	public function generate_select_html( $key, $data ): string {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
			'options'           => array(),
		);

		$data  = wp_parse_args( $data, $defaults );
		$value = $this->get_option( $key, $data['default'] );

		ob_start();
		?>
		<tr>
			<th scope="row" class="titledesc">
				<label
					for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?><?php echo $this->get_tooltip_html( $data ); // phpcs:ignore ?></label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span>
					</legend>
					<select class="select <?php echo esc_attr( $data['class'] ); ?>"
							name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $field_key ); ?>"
							style="<?php echo esc_attr( $data['css'] ); ?>" <?php echo $this->get_custom_attribute_html( $data ); // phpcs:ignore ?>>
						<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
							<option
								<?php
								disabled( $data['disabled'], true );
								?>
								value="<?php echo esc_attr( $option_key ); ?>"
								<?php
								selected( (string) $option_key, esc_attr( $value ) );
								?>
							><?php echo esc_html( $option_value ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php echo $this->get_description_html( $data ); // phpcs:ignore ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * WebHook Instruction.
	 *
	 * @return string
	 */
	public function display_admin_settings_webhook_description(): string {
		/* translators: %s: WebHook link. */
		return sprintf( __( '<strong>Webhook endpoint: </strong> <code style="background-color:#ddd;">%s</code> to your <a href="https://secure.2checkout.com/cpanel/ipn_settings.php" target="_blank">2Checkout IPN settings</a>', 'woo-2checkout' ), esc_url( $this->get_ipn_response_url() ) );
	}

	/**
	 * Payment Gateway Return URL After Payment.
	 *
	 * @return string
	 */
	public static function get_gateway_return_url(): string {
		return WC()->api_request_url( 'woo-2checkout-gateway-return' );
	}

	/**
	 * Payment Gateway Return URL After Inline Payment.
	 *
	 * @return string
	 */
	public function get_gateway_return_inline_url(): string {
		return WC()->api_request_url( 'woo-2checkout-gateway-return-inline' );
	}

	/**
	 * Payment Gateway WebHook URL After Payment process on 2Checkout Site.
	 *
	 * @return string
	 */
	public static function get_ipn_response_url(): string {
		return WC()->api_request_url( 'woo-2checkout-ipn-response' );
	}

	/**
	 * Payment Gateway Return URL Subscription Process.
	 *
	 * @return string
	 */
	public static function get_lcn_response_url(): string {
		return WC()->api_request_url( 'woo-2checkout-lcn-response' );
	}

	/**
	 * Payment Gateway WebHook URL After Payment process on 2Checkout Site.
	 *
	 * @return string
	 */
	public static function get_ins_response_url(): string {
		return WC()->api_request_url( 'woo-2checkout-ins-response' );
	}

	/**
	 * Register Admin Notices.
	 *
	 * @return void
	 */
	public function admin_notices() {
		if ( is_admin() ) {
			// Checks if account number and secret is not empty.
			if ( wc_string_to_bool( $this->get_option( 'enabled' ) ) && ( empty( $this->merchant_code ) || empty( $this->secret_key ) || empty( $this->buy_link_secret_word ) ) ) {
				add_action( 'admin_notices', array( $this, 'plugin_not_configured_message' ) );
			}

			// Checks that the currency is supported.
			if ( ! $this->using_supported_currency() ) {
				add_action( 'admin_notices', array( $this, 'currency_not_supported_message' ) );
			}
		}
	}

	/**
	 * Check current currency is 2Checkout supported currency.
	 *
	 * @link https://verifone.cloud/docs/2checkout/Documentation/07Commerce/Checkout-links-and-options/Billing-currencies
	 * @return bool
	 */
	public function using_supported_currency(): bool {

		$woocommerce_currency = get_woocommerce_currency();
		$supported_currencies = apply_filters(
			'woo_2checkout_supported_currencies',
			array(
				'AED',
				'AFN',
				'ALL',
				'AUD',
				'AZN',

				'BBD',
				'BDT',
				'BGN',
				'BHD',
				'BMD',
				'BND',
				'BOB',
				'BRL',
				'BSD',
				'BWP',
				'BYN',
				'BZD',

				'CAD',
				'CHF',

				'CNY',
				'COP',
				'CRC',
				'CZK',

				'DKK',
				'DOP',
				'DZD',

				'EGP',
				'EUR',

				'FJD',

				'GBP',
				'GTQ',

				'HKD',
				'HNL',
				'HTG',
				'HUF',

				'IDR',
				'ILS',
				'INR',

				'JMD',
				'JOD',
				'JPY',

				'KES',
				'KRW',
				'KWD',
				'KZT',

				'LAK',
				'LBP',
				'LKR',
				'LRD',

				'MAD',
				'MDL',
				'MMK',
				'MOP',
				'MRU',
				'MUR',
				'MVR',
				'MXN',
				'MYR',

				'NAD',
				'NGN',
				'NIO',
				'NOK',
				'NPR',
				'NZD',

				'OMR',

				'PAB',
				'PEN',
				'PGK',
				'PHP',
				'PKR',
				'PLN',
				'PYG',

				'QAR',

				'RON',
				'RSD',
				'RUB',

				'SAR',
				'SBD',
				'SCR',
				'SEK',
				'SGD',
				'SVC',
				'SYP',

				'THB',
				'TND',
				'TOP',
				'TRY',
				'TTD',
				'TWD',

				'UAH',
				'USD',
				'UYU',

				'VEF',
				'VND',
				'VUV',

				'WST',

				'XCD',
				'XOF',
				'YER',
				'ZAR',

			)
		);

		return in_array( $woocommerce_currency, $supported_currencies, true );
	}

	/**
	 * Check if the gateway is available for use.
	 *
	 * @return bool
	 */
	public function is_available(): bool {
		return parent::is_available() && ! empty( $this->merchant_code ) && ! empty( $this->secret_key ) && ! empty( $this->buy_link_secret_word ) && $this->using_supported_currency();
	}

	/** Log
	 *
	 * @param string $message log message.
	 * @param string $level   log label.
	 *                        'emergency': System is unusable.
	 *                        'alert': Action must be taken immediately.
	 *                        'critical': Critical conditions.
	 *                        'error': Error conditions.
	 *                        'warning': Warning conditions.
	 *                        'notice': Normal but significant condition.
	 *                        'info': Informational messages.
	 *                        'debug': Debug-level messages.
	 */
	public function log( string $message, string $level = 'info' ) {

		if ( ! $this->debug ) {
			return;
		}

		$context = array( 'source' => $this->get_id() );

		$this->log->log( $level, $message, $context );
	}

	/**
	 * Supported shop language for 2checkout.
	 *
	 * @link: https://verifone.cloud/docs/2checkout/Documentation/07Commerce/Checkout-links-and-options/2Checkout-supported-languages
	 * @return string
	 */
	public function shop_language(): string {

		$lang = apply_filters( 'woo_2checkout_shop_language', '' );

		if ( '' !== $lang ) {
			return $lang;
		}

		$_lang = explode( '_', ( get_locale() ? get_locale() : get_option( 'WPLANG' ) ) );
		$lang  = $_lang[0];
		if ( 'pt' === $lang ) {
			$lang = 'pt-br';
		}

		$available = array(
			'ar',
			'pt-br',
			'bg',
			'zy',
			'zh',
			'hr',
			'cs',
			'da',
			'nl',
			'en',
			'fi',
			'fr',
			'de',
			'el',
			'he',
			'hi',
			'hu',
			'it',
			'ja',
			'ko',
			'no',
			'fa',
			'pl',
			'pt',
			'ro',
			'ru',
			'sr',
			'sk',
			'sl',
			'es',
			'sv',
			'th',
			'tr',
		);

		if ( ! in_array( $lang, $available, true ) ) {
			return 'en';
		}

		return $lang;
	}

	/**
	 * Format item price for 2checkout.
	 *
	 * @param numeric $price product price.
	 *
	 * @return float
	 */
	public function format_item_price( $price ): float {
		return (float) number_format( (float) $price, wc_get_price_decimals(), wc_get_price_decimal_separator(), '' );
	}

	/**
	 * Format and limit item name for 2checkout.
	 *
	 * @param string $item_name product name.
	 *
	 * @return string
	 */
	public function format_item_name( string $item_name ): string {
		return trim( html_entity_decode( wc_trim_string( $item_name ? wp_strip_all_tags( $item_name ) : esc_html__( 'Item', 'woo-2checkout' ), 127 ), ENT_NOQUOTES, 'UTF-8' ) );
	}

	/**
	 * Get a link to the transaction on the 2checkout site.
	 *
	 * @param WC_Order $order Order object.
	 *
	 * @return string
	 */
	public function get_transaction_url( $order ): string {
		$this->view_transaction_url = 'https://secure.2checkout.com/cpanel/order_info.php?refno=%s';

		return parent::get_transaction_url( $order );
	}

	/**
	 * Plugin not configured notice.
	 *
	 * @return void
	 */
	public function plugin_not_configured_message() {

		$merchant_code        = sprintf( 'woocommerce_%s_merchant_code', $this->get_id() );
		$secret_key           = sprintf( 'woocommerce_%s_secret_key', $this->get_id() );
		$buy_link_secret_word = sprintf( 'woocommerce_%s_buy_link_secret_word', $this->get_id() );
		$data                 = stripslashes_deep( $_POST ); // phpcs:ignore

		if ( ! empty( $data[ $merchant_code ] ) && ! empty( $data[ $secret_key ] ) && ! empty( $data[ $buy_link_secret_word ] ) ) {
			return;
		}

		echo '<div class="error"><p><strong>' . esc_html__( 'Payment Gateway - 2Checkout for WooCommerce disabled', 'woo-2checkout' ) . '</strong>: ' . esc_html__( 'You must fill the "Merchant Code" and the "Secret Key" and "Buy Link Secret Word" fields.', 'woo-2checkout' ) . '</p></div>';
	}

	/**
	 * Current currency not support notice.
	 *
	 * @return void
	 */
	public function currency_not_supported_message() {
		echo '<div class="error"><p><strong>' . esc_html__( 'Billing Currency not supported. Payment Gateway - 2Checkout for WooCommerce disabled', 'woo-2checkout' ) . '</strong>: ' . esc_html__( '2Checkout does not support your store currency.', 'woo-2checkout' ) . '</p></div>';
	}

	/**
	 * Process after gateway return.
	 *
	 * @return void
	 */
	public function process_gateway_redirect() {
	}

	/**
	 * Process after gateway return.
	 *
	 * @return void
	 */
	public function process_gateway_return_inline() {
	}

	/**
	 * Process after payment received.
	 *
	 * @return void
	 */
	public function process_gateway_ipn_response() {
		wc_doing_it_wrong( __METHOD__, esc_html__( 'Should override on child class.', 'woo-2checkout' ), '2.1.0' );
	}


	/**
	 * Process after subscription payment received.
	 *
	 * @return void
	 */
	public function process_gateway_lcn_response() {
	}

	/**
	 * Process 2Checkout INS Response.
	 *
	 * @return void
	 */
	public function process_gateway_ins_response() {
		$data = stripslashes_deep( $_POST ); // phpcs:ignore
		$this->log( "INS Response: \n" . print_r( $data, true ), 'info' ); // phpcs:ignore
		do_action( 'woo_2checkout_gateway_process_ins_response', $data, $this );
	}

	/**
	 * Check is INS response is valid.
	 *
	 * @return boolean
	 */
	public function is_valid_ins_response(): bool {
		return true;
	}

	/**
	 * Do something on order pay page.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 */
	public function order_pay_page( int $order_id ) {
	}

	/**
	 * Order pay page ajax. Use wp_send_json* for response.
	 *
	 * @return void
	 */
	public function ajax_order_pay_page() {
	}

	/**
	 * Do something on order received page.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return void
	 */
	public function order_received_page( int $order_id ) {
	}

	/**
	 * Order received page ajax. Use wp_send_json* for response.
	 *
	 * @return void
	 */
	public function ajax_order_received_page() {
	}

	/**
	 * Load Scripts.
	 *
	 * @return void
	 */
	public function frontend_scripts() {
	}
}
