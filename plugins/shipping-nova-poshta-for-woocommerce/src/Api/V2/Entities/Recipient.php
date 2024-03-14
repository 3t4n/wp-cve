<?php
/**
 * Recipient entity.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api\V2\Entities;

use NovaPoshta\Api\Exception\AddRecipientAddress;
use NovaPoshta\Api\Exception\AllowOnlyOneRecipientAddress;

/**
 * Class Recipient.
 *
 * @package NovaPoshta\Api\V2\Entities
 */
class Recipient {

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
	 * Delivery type.
	 *
	 * @var string
	 */
	private $delivery_type;

	/**
	 * Address.
	 *
	 * @var string
	 */
	private $address;

	/**
	 * Recipient ID.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Recipient person ID.
	 *
	 * @var string
	 */
	private $person_id;

	/**
	 * Recipient constructor.
	 *
	 * @param string $id        Recipient ID.
	 * @param string $person_id Recipient person ID.
	 * @param string $phone     Phone number.
	 * @param string $city_id   City ID.
	 */
	public function __construct( string $id, string $person_id, string $phone, string $city_id ) {

		$this->id        = $id;
		$this->person_id = $person_id;
		$this->phone     = $phone;
		$this->city_id   = $city_id;
	}

	/**
	 * Add a home delivery.
	 *
	 * @param string $address Address name.
	 *
	 * @throws AllowOnlyOneRecipientAddress Only one address can added to recipient.
	 */
	public function add_home_delivery( string $address ) {

		if ( ! empty( $this->address ) ) {
			throw new AllowOnlyOneRecipientAddress();
		}

		$this->delivery_type = 'address';
		$this->address       = $address;
	}

	/**
	 * Add a warehouse delivery.
	 *
	 * @param string $warehouse_id Warehouse ID.
	 *
	 * @throws AllowOnlyOneRecipientAddress Only one address can added to recipient.
	 */
	public function add_warehouse_delivery( string $warehouse_id ) {

		if ( ! empty( $this->address ) ) {
			throw new AllowOnlyOneRecipientAddress();
		}

		$this->delivery_type = 'warehouse';
		$this->address       = $warehouse_id;
	}

	/**
	 * Get delivery type.
	 *
	 * @return string address|warehouse
	 *
	 * @throws AddRecipientAddress You must to add a recipient address.
	 */
	public function get_delivery_type(): string {

		if ( empty( $this->delivery_type ) ) {
			throw new AddRecipientAddress();
		}

		return $this->delivery_type;
	}

	/**
	 * Get phone number.
	 *
	 * @return string
	 */
	public function get_phone(): string {

		return $this->phone;
	}

	/**
	 * Get city ID.
	 *
	 * @return string
	 */
	public function get_city_id(): string {

		return $this->city_id;
	}

	/**
	 * Get address.
	 *
	 * @return string
	 */
	public function get_address(): string {

		return $this->address;
	}

	/**
	 * Get recipient ID.
	 *
	 * @return string
	 */
	public function get_id(): string {

		return $this->id;
	}

	/**
	 * Get recipient person ID.
	 *
	 * @return string
	 */
	public function get_person_id(): string {

		return $this->person_id;
	}

}
