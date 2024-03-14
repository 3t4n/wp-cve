<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_BPost_Shipping_Address retrieves specific data from posted var
 */
class WC_BPost_Shipping_Posted {
	/** @var string[] */
	private $posted;
	/** @var bool */
	private $ship_to_different_address;

	/**
	 * WC_BPost_Shipping_Posted constructor.
	 *
	 * @param string[] $posted
	 */
	public function __construct( array $posted ) {
		$this->posted = $posted;

		// Merge with an empty (about values) array to avoid a notice if the key is not in $posted
		$this->posted = array_merge(
			array(
				'billing_first_name'  => '',
				'billing_last_name'   => '',
				'billing_company'     => '',
				'shipping_first_name' => '',
				'shipping_last_name'  => '',
				'shipping_company'    => '',
				'billing_email'       => '',
				'billing_phone'       => '',
				'payment_method'      => '',
			),
			$this->posted
		);

		$this->ship_to_different_address = isset( $this->posted['ship_to_different_address'] ) && (bool) $this->posted['ship_to_different_address'];
	}

	/**
	 * @return string
	 */
	public function get_payment_method() {
		return $this->posted['payment_method'];
	}

	/**
	 * @return string
	 */
	public function get_first_name() {
		return $this->posted[ $this->get_address_type() . '_first_name' ];
	}

	/**
	 * @return string return 'shipping' or 'billing' depending of ship_to_different_address flag
	 */
	private function get_address_type() {
		return $this->ship_to_different_address ? 'shipping' : 'billing';
	}

	/**
	 * @return string
	 */
	public function get_last_name() {
		return $this->posted[ $this->get_address_type() . '_last_name' ];
	}

	/**
	 * @return string
	 */
	public function get_company() {
		return $this->posted[ $this->get_address_type() . '_company' ];
	}

	/**
	 * @return string the email value from billing email every time
	 */
	public function get_email() {
		return $this->posted['billing_email'];
	}

	/**
	 * @return string the phone value from billing phone every time
	 */
	public function get_phone() {
		return $this->posted['billing_phone'];
	}

	/**
	 * @return string
	 */
	public function get_shipping_method() {
		if ( ! is_array( $this->posted['shipping_method'] ) ) {
			return '';
		}

		return join( '', $this->posted['shipping_method'] );
	}

	/**
	 * @return boolean
	 */
	public function is_ship_to_different_address() {
		return $this->ship_to_different_address;
	}
}
