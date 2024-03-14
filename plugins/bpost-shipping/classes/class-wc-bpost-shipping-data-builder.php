<?php
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Locale\WC_BPost_Shipping_Locale_Locale;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_BPost_Shipping_Data_Builder provides data to inject to js shm.
 * TODO: some restructuring can be clean this class
 */
class WC_BPost_Shipping_Data_Builder {
	/** @var WC_BPost_Shipping_Options_Base */
	private $shipping_options;
	/** @var WC_BPost_Shipping_Street_Builder */
	private $bpost_street_builder;
	/** @var WC_BPost_Shipping_Address */
	private $shipping_address;
	/** @var WC_BPost_Shipping_Delivery_Methods */
	private $delivery_methods;

	/**
	 * WC_BPost_Shipping_Data_Builder constructor.
	 *
	 * @param WC_BPost_Shipping_Cart $cart
	 * @param WC_BPost_Shipping_Address $shipping_address
	 * @param WC_BPost_Shipping_Options_Base $shipping_options
	 * @param WC_BPost_Shipping_Street_Builder $bpost_street_builder
	 * @param WC_BPost_Shipping_Delivery_Methods $delivery_methods
	 */
	public function __construct(
		WC_BPost_Shipping_Cart $cart,
		WC_BPost_Shipping_Address $shipping_address,
		WC_BPost_Shipping_Options_Base $shipping_options,
		WC_BPost_Shipping_Street_Builder $bpost_street_builder,
		WC_BPost_Shipping_Delivery_Methods $delivery_methods
	) {
		$this->cart                 = $cart;
		$this->shipping_options     = $shipping_options;
		$this->bpost_street_builder = $bpost_street_builder;
		$this->shipping_address     = $shipping_address;
		$this->delivery_methods     = $delivery_methods;
	}

	/**
	 * Various bpost data needed
	 * @return string[]
	 */
	public function get_bpost_data() {

		// Build data to inject
		$order_reference = uniqid();

		$callback_url  = WC()->api_request_url( 'shm-callback' );
		$callback_url .= strpos( $callback_url, '?' ) === false ? '?' : '&';
		$callback_url .= 'result=';

		$bpost_data = array(
			'account_id'                => $this->shipping_options->get_account_id(),
			'order_reference'           => $order_reference,
			'callback_url'              => $callback_url,
			// Euro-cents
			'sub_total'                 => round( WC()->cart->subtotal * 100 ),
			// In grams, if 0, then we set 1kg (1000g)
			'sub_weight'                => ceil( $this->cart->get_weight_in_g() ?: 1000 ),
			'language'                  => $this->get_language_for_shm(),
			'additional_customer_ref'   => 'WORDPRESS ' . get_bloginfo( 'version' ) . ' / WOOCOMMERCE ' . WC()->version,
			'delivery_method_overrides' => $this->shipping_options->get_delivery_method_overrides(
				$this->shipping_address,
				$this->cart,
				$this->delivery_methods
			),
			'extra'                     => $this->get_extra_json(),
		);

		$bpost_data['hash'] = $this->shipping_options->get_hash(
			$bpost_data,
			$this->shipping_address->get_shipping_country()
		);

		return $bpost_data;
	}

	/**
	 * @return string
	 */
	private function get_extra_json() {
		if ( $this->shipping_address->get_shipping_state() ) {
			return json_encode(
				array( 'customerState' => $this->shipping_address->get_shipping_state() )
			);
		}

		return '';
	}

	/**
	 * @return string
	 */
	private function get_language_for_shm() {
		$locale = new WC_BPost_Shipping_Locale_Locale(
			new WC_BPost_Shipping_Adapter_Woocommerce()
		);

		$language = $locale->get_language();

		$shm_supported_languages = array(
			WC_BPost_Shipping_Locale_Locale::LANGUAGE_EN,
			WC_BPost_Shipping_Locale_Locale::LANGUAGE_FR,
			WC_BPost_Shipping_Locale_Locale::LANGUAGE_NL,
		);

		if ( in_array( $language, $shm_supported_languages, true ) ) {
			return strtoupper( $language );
		}

		return WC_BPost_Shipping_Locale_Locale::LANGUAGE_DEFAULT;
	}

	/**
	 * Shipping address to pre-fill shm form
	 * @return string[]
	 */
	public function get_shipping_address() {
		$shipping_address = array(
			'first_name'   => $this->shipping_address->get_first_name(),
			'last_name'    => $this->shipping_address->get_last_name(),
			'company'      => $this->shipping_address->get_company(),
			'post_code'    => $this->shipping_address->get_shipping_postcode(),
			'city'         => $this->shipping_address->get_shipping_city(),
			'country_code' => $this->shipping_address->get_shipping_country(),
			'email'        => $this->shipping_address->get_email(),
			'phone_number' => $this->shipping_address->get_phone(),
		);

		$street_data = $this->shipping_address->get_street_items();

		$shipping_address['address']       = $street_data->get_street();
		$shipping_address['street_number'] = $street_data->get_number();
		$shipping_address['street_box']    = $street_data->get_box();

		return $shipping_address;
	}
}
