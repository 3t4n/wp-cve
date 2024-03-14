<?php
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Connector;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Product_Configuration;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_BPost_Shipping_Limitations express what bpost shipping is not capable and returns appropriate error messages
 */
class WC_BPost_Shipping_Limitations {

	/** Limit for address length into SHM */
	const MAX_LENGTH_ADDRESS = 40;

	/** Id of the cash on delivery payment method */
	const CASH_ON_DELIVERY_ID = 'cod';

	/** @var string[] */
	private $errors;

	public function __construct() {
		$this->errors = array();
	}

	/**
	 * @param string $payment_method
	 * @param string $address
	 * @param int $weight
	 * @param WC_BPost_Shipping_Api_Product_Configuration $product_configuration
	 * @param WC_BPost_Shipping_Api_Connector $connector
	 *
	 * @return bool
	 */
	public function validate_limitations(
		$payment_method,
		$address,
		$weight,
		WC_BPost_Shipping_Api_Product_Configuration $product_configuration,
		WC_BPost_Shipping_Api_Connector $connector
	) {
		$limitations_are_ok = true;

		if ( ! $this->check_cash_on_delivery( $payment_method ) ) {
			$this->errors[]     = bpost__( 'bpost doesn\'t allow the "Cash on delivery" on this website. Please use a different payment method' );
			$limitations_are_ok = false;
		}

		if ( ! $this->check_address_field_length( $address ) ) {
			$this->errors[]     = sprintf(
				bpost__( 'Your address can\'t have a length over %s' ),
				self::MAX_LENGTH_ADDRESS
			);
			$limitations_are_ok = false;
		}

		if ( ! $this->is_valid_weight( $product_configuration, $weight ) ) {
			$this->errors[]     = sprintf(
				bpost__( 'Shipments over %s kg are not possible with bpost, the current cart weight is %s kg' ),
				number_format( $product_configuration->get_maximal_allowed_weight() / 1000, 2 ),
				number_format( $weight, 2 )
			);
			$limitations_are_ok = false;
		}

		if ( ! $this->check_webservice_status( $connector ) ) {
			$this->errors[]     =
				bpost__( 'The Shipping Manager is currently unavailable, please contact the webshop\'s manager' );
			$limitations_are_ok = false;
		}

		return $limitations_are_ok;
	}

	/**
	 *If the payment is in 'mode' Cash on delivery
	 *
	 * @param string $payment_method id
	 *
	 * @return bool
	 */
	public function check_cash_on_delivery( $payment_method ) {
		if ( is_null( $payment_method ) ) {
			return false;
		}

		return $payment_method !== self::CASH_ON_DELIVERY_ID;
	}

	/**
	 * Address fields greater 40 characters
	 *
	 * @param string $address
	 *
	 * @return bool
	 */
	public function check_address_field_length( $address ) {
		return strlen( $address ) <= self::MAX_LENGTH_ADDRESS;
	}

	/**
	 * @return string[] errors list
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * The cart weight is greater than MAX_WEIGHT_SHIP kg
	 * TODO This test should be removed and the first check using the weight to WS must be used at this place (an exception is returned and should be used)
	 *
	 * @param WC_BPost_Shipping_Api_Product_Configuration $product_configuration
	 * @param float $weight in kg
	 *
	 * @return bool
	 */
	public function is_valid_weight(
		WC_BPost_Shipping_Api_Product_Configuration $product_configuration,
		$weight
	) {
		return $product_configuration->is_valid_weight( $weight * 1000 );
	}

	/**
	 * @param WC_BPost_Shipping_Api_Connector $connector
	 *
	 * @return bool
	 */
	public function check_webservice_status( WC_BPost_Shipping_Api_Connector $connector ) {
		return $connector->is_online();
	}
}
