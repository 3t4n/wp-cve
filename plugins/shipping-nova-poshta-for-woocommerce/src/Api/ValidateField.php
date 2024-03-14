<?php
/**
 * Validate API fields.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api;

use NovaPoshta\Api\Exception\InvalidId;
use NovaPoshta\Api\Exception\InvalidFieldName;
use NovaPoshta\Api\Exception\InvalidPhoneNumber;
use NovaPoshta\Api\Exception\InvalidFieldAddress;
use NovaPoshta\Api\Exception\InvalidFieldDescription;

/**
 * Class ValidateField
 *
 * @package NovaPoshta\Api
 */
class ValidateField {

	/**
	 * Pattern for phone validation.
	 */
	const PHONE_PATTERN = '/^(\+38|38|)0[+0-9]{9}$/';

	/**
	 * Pattern for ID validation.
	 */
	const ID_PATTERN = '/[^a-z0-9-]/';

	/**
	 * Pattern for simple text validation.
	 */
	const TEXT_FIELD_PATTERN = '/[^А-Яа-яґіїєҐІЇЄ\']/u';

	/**
	 * Pattern for rich text validation validation.
	 */
	const RICH_FIELD_PATTERN = '/[^0-9А-Яа-яґіїєҐІЇЄ, -\'\"]/u';

	/**
	 * Validate for number.
	 * Allowed formats: +380660000000, 380660000000, 0660000000.
	 *
	 * @param string $phone Phone number.
	 *
	 * @return string
	 *
	 * @throws InvalidPhoneNumber Invalid phone number.
	 */
	public function validate_phone( string $phone ): string {

		// Remove spaces, round brackets, and hypens.
		$sanitized_phone = preg_replace( '/[\s\(\)-]/', '', $phone );

		if ( ! (bool) preg_match( self::PHONE_PATTERN, $sanitized_phone ) ) {
			throw new InvalidPhoneNumber( $phone );
		}

		return $sanitized_phone;
	}

	/**
	 * Validate ID.
	 *
	 * @param string $id City, warehouse, sender, recipient or other ID.
	 *
	 * @return string
	 *
	 * @throws InvalidId Invalid identifier.
	 */
	public function validate_id( string $id ): string {

		if ( preg_match( self::ID_PATTERN, $id ) || 36 !== strlen( $id ) ) {
			throw new InvalidId( $id );
		}

		return $id;
	}

	/**
	 * Validate sender/recipient name.
	 *
	 * @param string $name Any string.
	 *
	 * @return string
	 *
	 * @throws InvalidFieldName Invalid field name.
	 */
	public function validate_name( string $name ): string {

		if ( preg_match( self::TEXT_FIELD_PATTERN, $name ) || 36 < mb_strlen( $name ) ) {
			throw new InvalidFieldName( $name );
		}

		return $name;
	}

	/**
	 * Validate address.
	 *
	 * @param string $address Any string.
	 *
	 * @return string
	 *
	 * @throws InvalidFieldAddress Invalid field address.
	 */
	public function validate_address( string $address ): string {

		if ( preg_match( self::RICH_FIELD_PATTERN, $address ) || 36 < mb_strlen( $address ) ) {
			throw new InvalidFieldAddress( $address );
		}

		return $address;
	}

	/**
	 * Validate description.
	 *
	 * @param string $description Any string.
	 *
	 * @return string
	 *
	 * @throws InvalidFieldDescription Invalid field description.
	 */
	public function validate_description( string $description ): string {

		if ( preg_match( self::RICH_FIELD_PATTERN, $description ) || 36 < mb_strlen( $description ) ) {
			throw new InvalidFieldDescription( $description );
		}

		return $description;
	}
}
