<?php
/**
 * Sender entity.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api\V2\Entities;

/**
 * Class Sender
 *
 * @package NovaPoshta\Api\V2\Entities
 */
class Sender {

	/**
	 * Phone number.
	 *
	 * @var string
	 */
	private $phone;

	/**
	 * City ID.
	 *
	 * @var string
	 */
	private $city_id;

	/**
	 * Warehouse ID.
	 *
	 * @var string
	 */
	private $warehouse_id;

	/**
	 * Sender ID.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Sender person ID.
	 *
	 * @var string
	 */
	private $person_id;

	/**
	 * Sender constructor.
	 *
	 * @param string $id           Sender ID.
	 * @param string $person_id    Sender person ID.
	 * @param string $phone        Phone number.
	 * @param string $city_id      City ID.
	 * @param string $warehouse_id Warehouse ID.
	 */
	public function __construct( string $id, string $person_id, string $phone, string $city_id, string $warehouse_id ) {

		$this->id           = $id;
		$this->person_id    = $person_id;
		$this->phone        = $phone;
		$this->city_id      = $city_id;
		$this->warehouse_id = $warehouse_id;
	}

	/**
	 * Get sender phone.
	 *
	 * @return string
	 */
	public function get_phone(): string {

		return $this->phone;
	}

	/**
	 * Get sender city ID.
	 *
	 * @return string
	 */
	public function get_city_id(): string {

		return $this->city_id;
	}

	/**
	 * Get warehouse ID.
	 *
	 * @return string
	 */
	public function get_warehouse_id(): string {

		return $this->warehouse_id;
	}

	/**
	 * Get sender ID.
	 *
	 * @return string
	 */
	public function get_id(): string {

		return $this->id;
	}

	/**
	 * Get sender person ID.
	 *
	 * @return string
	 */
	public function get_person_id(): string {

		return $this->person_id;
	}
}
