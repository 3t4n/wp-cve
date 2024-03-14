<?php


namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;


class ShipX_Shipment_Status_History_Item_Model {

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var int
	 */
	private $timestamp;

	/**
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function set_name( string $name ) {
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function get_timestamp(): int {
		return $this->timestamp;
	}

	/**
	 * @param int $timestamp
	 */
	public function set_timestamp( int $timestamp ) {
		$this->timestamp = $timestamp;
	}
}
