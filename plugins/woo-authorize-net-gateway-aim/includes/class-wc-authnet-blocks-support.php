<?php
use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use Automattic\WooCommerce\Blocks\Payments\PaymentResult;
use Automattic\WooCommerce\Blocks\Payments\PaymentContext;

defined( 'ABSPATH' ) || exit;

/**
 * WC_Authnet_Blocks_Support class.
 *
 * @extends AbstractPaymentMethodType
 */
final class WC_Authnet_Blocks_Support extends AbstractPaymentMethodType {
	/**
	 * Payment method name defined by payment methods extending this class.
	 *
	 * @var string
	 */
	protected $name = 'authnet';


	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		add_action( 'woocommerce_rest_checkout_process_payment_with_context', [ $this, 'add_authnet_error' ], 8, 2 );
	}

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$this->settings = get_option( 'woocommerce_authnet_settings', [] );
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active() {
		return ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'];
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment method.
	 *
	 * @return array
	 */
	public function get_payment_method_script_handles() {

		$asset_path   = WC_AUTHNET_PLUGIN_PATH . '/build/index.asset.php';
		$version      = WC_AUTHNET_VERSION;
		$dependencies = [];
		if ( file_exists( $asset_path ) ) {
			$asset        = require $asset_path;
			$version      = is_array( $asset ) && isset( $asset['version'] )
				? $asset['version']
				: $version;
			$dependencies = is_array( $asset ) && isset( $asset['dependencies'] )
				? $asset['dependencies']
				: $dependencies;
		}

		$js_params = $this->get_gateway_javascript_params();

		if( ! empty( $js_params['client_key'] ) ) {
			wp_enqueue_script( 'authnet-accept', $js_params['accept_js_url'], $dependencies, null, true );
			$dependencies = array_merge( [ 'authnet-accept' ], $dependencies );
		}

		wp_enqueue_style(
			'wc-authnet-blocks-checkout-style',
			WC_AUTHNET_PLUGIN_URL . '/build/style-index.css',
			[],
			$version
		);

		wp_register_script(
			'wc-authnet-blocks-integration',
			WC_AUTHNET_PLUGIN_URL . '/build/index.js',
			$dependencies,
			$version,
			true
		);
		wp_set_script_translations(
			'wc-authnet-blocks-integration',
			'wc-authnet'
		);

		return [ 'wc-authnet-blocks-integration' ];
	}


	/**
	 * Returns an array of key=>value pairs of data made available to the payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data() {
		// We need to call array_merge_recursive so the blocks 'button' setting doesn't overwrite
		// what's provided from the gateway or payment request configuration.
		return array_replace_recursive(
			$this->get_gateway_javascript_params(),
			// Blocks-specific options
			[
				'title'                          => $this->get_title(),
				'icons'                          => $this->get_icons(),
				'supports'                       => $this->get_supported_features(),
				'showSavedCards'                 => $this->get_show_saved_cards(),
				'showSaveOption'                 => $this->get_show_save_option(),
				'isAdmin'                        => is_admin(),
			]
		);
	}

	/**
	 * Returns the Authnet Payment Gateway JavaScript configuration object.
	 *
	 * @return array  the JS configuration from the Authnet Payment Gateway.
	 */
	private function get_gateway_javascript_params() {
		$js_configuration = [];

		$gateways = WC()->payment_gateways->get_available_payment_gateways();
		if ( isset( $gateways['authnet'] ) ) {
			$js_configuration = $gateways['authnet']->javascript_params();
		}

		return apply_filters(
			'wc_authnet_params',
			$js_configuration
		);
	}

	/**
	 * Determine if store allows cards to be saved during checkout.
	 *
	 * @return bool True if merchant allows shopper to save card (payment method) during checkout.
	 */
	private function get_show_saved_cards() {
		//return isset( $this->settings['saved_cards'] ) ? 'yes' === $this->settings['saved_cards'] : false;
		return false;
	}

	/**
	 * Determine if the checkbox to enable the user to save their payment method should be shown.
	 *
	 * @return bool True if the save payment checkbox should be displayed to the user.
	 */
	private function get_show_save_option() {
		$saved_cards = $this->get_show_saved_cards();
		return apply_filters( 'wc_authnet_display_save_payment_method_checkbox', filter_var( $saved_cards, FILTER_VALIDATE_BOOLEAN ) );
	}

	/**
	 * Returns the title string to use in the UI (customisable via admin settings screen).
	 *
	 * @return string Title / label string
	 */
	private function get_title() {
		return isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Credit / Debit Card', 'wc-authnet' );
	}

	/**
	 * Return the icons urls.
	 *
	 * @return array Arrays of icons metadata.
	 */
	private function get_icons() {
		$allowed_card_types = $this->settings['allowed_card_types'];
		if( in_array( 'visa', $allowed_card_types ) ) {
			$icons_src['visa'] = [
				'src' => WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/visa.svg' ),
				'alt' => __( 'Visa', 'wc-authnet' ),
			];
		}
		if( in_array( 'mastercard', $allowed_card_types ) ) {
			$icons_src['mastercard'] = [
				'src' => WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/mastercard.svg' ),
				'alt' => __( 'Mastercard', 'wc-authnet' ),
			];
		}
		if( in_array( 'amex', $allowed_card_types ) ) {
			$icons_src['amex'] = [
				'src' => WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/amex.svg' ),
				'alt' => __( 'American Express', 'wc-authnet' ),
			];
		}

		if ( 'USD' === get_woocommerce_currency() ) {
			if( in_array( 'discover', $allowed_card_types ) ) {
				$icons_src['discover'] = [
					'src' => WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/discover.svg' ),
					'alt' => _x( 'Discover', 'Name of credit card', 'wc-authnet' ),
				];
			}
			if( in_array( 'jcb', $allowed_card_types ) ) {
				$icons_src['jcb']      = [
					'src' => WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/jcb.svg' ),
					'alt' => __( 'JCB', 'wc-authnet' ),
				];
			}
			if( in_array( 'diners-club', $allowed_card_types ) ) {
				$icons_src['diners'] = [
					'src' => WC_HTTPS::force_https_url( WC()->plugin_url() . '/assets/images/icons/credit-cards/diners.svg' ),
					'alt' => __( 'Diners', 'wc-authnet' ),
				];
			}
		}
		return $icons_src;
	}

	/**
	 * Returns an array of supported features.
	 *
	 * @return string[]
	 */
	public function get_supported_features() {
		$gateways = WC()->payment_gateways->get_available_payment_gateways();
		if ( isset( $gateways['authnet'] ) ) {
			$gateway = $gateways['authnet'];
			return array_filter( $gateway->supports, [ $gateway, 'supports' ] );
		}
		return [];
	}

	/**
	 * Add Authorize.Net error response to block checkout
	 *
	 * @param PaymentContext $context Holds context for the payment.
	 * @param PaymentResult  $result  Result object for the payment.
	 */
	public function add_authnet_error( PaymentContext $context, PaymentResult &$result ) {

		// hook into authnet error processing so that we can capture the error to
		// payment details (which is added to notices and thus not helpful for
		// this context).
		if ( 'authnet' === $context->payment_method ) {
			add_action(
				'wc_gateway_authnet_process_payment_error',
				function( $error ) use ( &$result ) {
					$payment_details                 = $result->payment_details;
					$payment_details['errorMessage'] = wp_strip_all_tags( $error->getMessage() );
					$result->set_payment_details( $payment_details );
				}
			);
		}
	}
}
