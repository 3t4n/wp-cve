<?php

namespace NativeRent;

defined( 'ABSPATH' ) || exit;

/**
 * Site moderation status implementation.
 */
class Site_Moderation_Status {
	const MODERATION = 1;
	const APPROVED = 2;
	const REJECTED = 3;

	/**
	 * Current status.
	 *
	 * @var int
	 */
	private $value;

	/**
	 * @param  int|null $value  Status value.
	 */
	public function __construct( $value = null ) {
		$this->value = $this->is_valid_status( $value ) ? (int) $value : self::MODERATION;
	}

	/**
	 * Checking status value.
	 *
	 * @param  int $value  Status value.
	 *
	 * @return bool
	 */
	private function is_valid_status( $value ) {
		return in_array( $value, array( self::MODERATION, self::REJECTED, self::APPROVED ) );
	}

	/**
	 * Status value MODERATION ?
	 *
	 * @return bool
	 */
	public function is_moderation() {
		return self::MODERATION === $this->value;
	}

	/**
	 * Status value REJECTED ?
	 *
	 * @return bool
	 */
	public function is_rejected() {
		return self::REJECTED === $this->value;
	}

	/**
	 * Get current value.
	 *
	 * @return int
	 */
	public function get_value() {
		return $this->value;
	}
}
