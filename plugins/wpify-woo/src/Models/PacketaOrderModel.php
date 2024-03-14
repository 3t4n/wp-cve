<?php

namespace WpifyWoo\Models;

use WpifyWoo\Modules\PacketaShipping\PacketaShippingModule;
use WpifyWoo\Plugin;

/**
 * @property Plugin $plugin
 */
class PacketaOrderModel extends WooOrderModel {
	public const FIELD_PACKETA_ID = '_packeta_id';
	public const FIELD_PACKETA_NAME = '_packeta_name';
	public const FIELD_PACKETA_STREET = '_packeta_street';
	public const FIELD_PACKETA_CITY = '_packeta_city';
	public const FIELD_PACKETA_POSTCODE = '_packeta_postcode';
	public const FIELD_PACKETA_PACKAGE_ID = '_packeta_package_id';
	public const FIELD_PACKETA_PACKAGE_BARCODE = '_packeta_package_barcode';
	public const FIELD_PACKETA_URL = '_packeta_url';
	public const FIELD_PACKETA_DETAILS = '_packeta_details';
	public const FIELD_PACKETA_ORDER_DETAILS = '_packeta_order_details';
	public const FIELD_PACKETA_INVOICE_ID = '_packeta_api_invoice_id';

	private $packeta_id;
	private $packeta_name;
	private $packeta_street;
	private $packeta_city;
	private $packeta_postcode;
	private $package_id;
	private $barcode;
	private $packeta_url;
	private $packeta_details;
	private $packeta_order_details;
	private $packeta_weight;
	private $packeta_invoice_id;

	public function setup() {
		$this->packeta_id      = $this->get_custom_field( $this::FIELD_PACKETA_ID );
		$this->packeta_details = $this->get_custom_field( $this::FIELD_PACKETA_DETAILS );
		if ( $this->packeta_details ) {
			$this->packeta_name     = $this->packeta_details['name'];
			$this->packeta_street   = $this->packeta_details['street'];
			$this->packeta_city     = $this->packeta_details['city'];
			$this->packeta_postcode = $this->packeta_details['postcode'];
			$this->packeta_url      = $this->packeta_details['url'];
		} else {
			$this->packeta_name     = $this->get_custom_field( $this::FIELD_PACKETA_NAME );
			$this->packeta_street   = $this->get_custom_field( $this::FIELD_PACKETA_STREET );
			$this->packeta_city     = $this->get_custom_field( $this::FIELD_PACKETA_CITY );
			$this->packeta_postcode = $this->get_custom_field( $this::FIELD_PACKETA_POSTCODE );
			$this->packeta_url      = $this->get_custom_field( $this::FIELD_PACKETA_URL );
		}

		$this->package_id            = $this->get_custom_field( $this::FIELD_PACKETA_PACKAGE_ID );
		$this->barcode               = $this->get_custom_field( $this::FIELD_PACKETA_PACKAGE_BARCODE );
		$this->packeta_order_details = $this->get_custom_field( $this::FIELD_PACKETA_ORDER_DETAILS );
		$this->packeta_invoice_id    = $this->get_custom_field( $this::FIELD_PACKETA_INVOICE_ID );
		$this->packeta_weight        = $this->packeta_order_details['weight'] ?? 0;
	}

	public function is_packeta_shipping() {
		foreach ( $this->get_shipping_items() as $item ) {
			if ( strpos( $item->get_method_id(), 'packeta' ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_id() {
		return $this->packeta_id;
	}

	/**
	 * @param  mixed  $packeta_id
	 */
	public function set_packeta_id( $packeta_id ): void {
		$this->packeta_id = $packeta_id;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_name() {
		return $this->packeta_name;
	}

	/**
	 * @param  mixed  $packeta_name
	 */
	public function set_packeta_name( $packeta_name ): void {
		$this->packeta_name = $packeta_name;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_street() {
		return $this->packeta_street;
	}

	/**
	 * @param  mixed  $packeta_street
	 */
	public function set_packeta_street( $packeta_street ): void {
		$this->packeta_street = $packeta_street;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_city() {
		return $this->packeta_city;
	}

	/**
	 * @param  mixed  $packeta_city
	 */
	public function set_packeta_city( $packeta_city ): void {
		$this->packeta_city = $packeta_city;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_postcode() {
		return $this->packeta_postcode;
	}

	/**
	 * @param  mixed  $packeta_postcode
	 */
	public function set_packeta_postcode( $packeta_postcode ): void {
		$this->packeta_postcode = $packeta_postcode;
	}

	/**
	 * @return mixed
	 */
	public function get_package_id() {
		return $this->package_id;
	}

	/**
	 * @param  mixed  $package_id
	 */
	public function set_package_id( $package_id ): void {
		$this->package_id = $package_id;
	}

	/**
	 * @return mixed
	 */
	public function get_barcode() {
		return $this->barcode;
	}

	/**
	 * @param  mixed  $barcode
	 */
	public function set_barcode( $barcode ): void {
		$this->barcode = $barcode;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_url() {
		return $this->packeta_url;
	}

	/**
	 * @param  mixed  $url
	 */
	public function set_packeta_url( $url ): void {
		$this->packeta_url = $url;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_details() {
		return $this->packeta_details;
	}

	/**
	 * @param  mixed  $packeta_details
	 */
	public function set_packeta_details( $packeta_details ): void {
		$this->packeta_details = $packeta_details;
	}

	/**
	 * @return bool
	 * @throws \ReflectionException
	 * @throws \Wpify\Core_4_0\Exceptions\PluginException
	 */
	public function is_external_carrier(): bool {
		foreach ( $this->get_shipping_items() as $item ) {
			if ( 'packeta' === $item->get_method_id() ) {
				return false;
			}
		}

		return true;
	}

	public function get_carrier_id() {
		foreach ( $this->get_shipping_items() as $item ) {
			if ( strpos( $item->get_method_id(), 'packeta' ) !== false ) {
				return (int) str_replace( 'packeta_', '', $item->get_method_id() );
			}
		}

		return null;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_order_details() {
		return $this->packeta_order_details;
	}


	public function get_package_weight() {
		$order_details = $this->get_packeta_order_details();
		$weight        = ! empty( $order_details['weight'] ) && $order_details['weight'] > 0 ? $order_details['weight'] : $this->get_weight();

		if ( ! $weight ) {
			$weight = $this->plugin->get_module( PacketaShippingModule::class )->get_setting( 'order_weight' );
		}

		return $weight;
	}

	/**
	 * @param  mixed  $packeta_weight
	 */
	public function set_packeta_weight( $packeta_weight ): void {
		$this->packeta_weight = $packeta_weight;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_weight() {
		return $this->packeta_weight;
	}

	/**
	 * @return mixed
	 */
	public function get_packeta_invoice_id() {
		return $this->packeta_invoice_id;
	}

	/**
	 * @param  mixed  $packeta_invoice_id
	 */
	public function set_packeta_invoice_id( $packeta_invoice_id ): void {
		$this->packeta_invoice_id = $packeta_invoice_id;
	}
}
