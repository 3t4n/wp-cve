<?php
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Builder;
use WC_BPost_Shipping\street\WC_BPost_Shipping_Street_Result;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WC_BPost_Shipping_Address retrieves specific data address
 * It is capable to use WC_BPost_Shipping_Street_Builder|Solver to return a well parsed address
 */
class WC_BPost_Shipping_Address {
	/** @var WC_BPost_Shipping_Posted */
	private $posted;
	/** @var WC_BPost_Shipping_Street_Builder */
	private $bpost_street_builder;
	/** @var WC_Customer */
	private $customer;

	/**
	 * WC_BPost_Shipping_Address constructor.
	 *
	 * @param WC_BPost_Shipping_Street_Builder $bpost_street_builder
	 * @param WC_Customer $customer
	 * @param WC_BPost_Shipping_Posted $posted
	 */
	public function __construct(
		WC_BPost_Shipping_Street_Builder $bpost_street_builder,
		WC_Customer $customer,
		WC_BPost_Shipping_Posted $posted
	) {
		$this->bpost_street_builder = $bpost_street_builder;
		$this->customer             = $customer;
		$this->posted               = $posted;
	}

	/**
	 * @return string
	 */
	public function get_first_name() {
		return stripslashes( $this->posted->get_first_name() );
	}

	/**
	 * @return string
	 */
	public function get_last_name() {
		return stripslashes( $this->posted->get_last_name() );
	}

	/**
	 * @return string
	 */
	public function get_company() {
		return stripslashes( $this->posted->get_company() );
	}

	/**
	 * @return string
	 */
	public function get_shipping_postcode() {
		return stripslashes( $this->customer->get_shipping_postcode() );
	}

	/**
	 * @return string
	 */
	public function get_shipping_city() {
		return stripslashes( $this->customer->get_shipping_city() );
	}

	/**
	 * @return string
	 */
	public function get_shipping_country() {
		return stripslashes( $this->customer->get_shipping_country() );
	}

	/**
	 * @return string the email value from billing email every time
	 */
	public function get_email() {
		//TODO nothing to do with shipping address
		return $this->posted->get_email();
	}

	/**
	 * @return string the phone value from billing phone every time
	 */
	public function get_phone() {
		//TODO nothing to do with shipping address
		return $this->posted->get_phone();
	}

	/**
	 * @return string
	 */
	public function get_shipping_state() {
		return $this->customer->get_shipping_state();
	}

	/**
	 * make a call to street builder/solver to solve the address in splitted parts
	 * @return WC_BPost_Shipping_Street_Result
	 */
	public function get_street_items() {
		return $this->bpost_street_builder->get_street_items(
			stripslashes( $this->customer->get_shipping_address() ),
			stripslashes( $this->customer->get_shipping_address_2() )
		);
	}

	/**
	 * @return boolean
	 */
	public function is_ship_to_different_address() {
		return $this->posted->is_ship_to_different_address();
	}

}
