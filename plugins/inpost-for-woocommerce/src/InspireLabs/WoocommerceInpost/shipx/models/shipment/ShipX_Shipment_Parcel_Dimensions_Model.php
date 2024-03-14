<?php

namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;

class ShipX_Shipment_Parcel_Dimensions_Model {

	/**
	 * @var int|float
	 */
	private $length;

	/**
	 * @var int|float
	 */
	private $width;

	/**
	 * @var int|float
	 */
	private $height;

	/**
	 * @var string
	 */
	private $unit = 'mm';

	/**
	 * @return int
	 */
	public function getLength() {
		return $this->length;
	}

	/**
	 * @param int $length
	 */
	public function setLength( $length ) {
		$this->length = $length;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param int $width
	 */
	public function setWidth( $width ) {
		$this->width = $width;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @param int $height
	 */
	public function setHeight( $height ) {
		$this->height = $height;
	}

	/**
	 * @return int
	 */
	public function getUnit() {
		return $this->unit;
	}

	/**
	 * @param int $unit
	 */
	public function setUnit( $unit ) {
		$this->unit = $unit;
	}
}
