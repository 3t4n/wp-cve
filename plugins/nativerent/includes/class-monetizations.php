<?php

namespace NativeRent;

use function defined;

defined( 'ABSPATH' ) || exit;

/**
 * Native Rent monetizations struct.
 *
 * @package    nativerent
 */
class Monetizations {
	const MODERATION = - 1;
	const REJECTED = 0;
	const APPROVED = 1;

	/**
	 * Regular monetization status.
	 *
	 * @var int Status value.
	 */
	protected $regular;

	/**
	 * NTGB monetization status.
	 *
	 * @var int Status value.
	 */
	protected $ntgb;

	/**
	 * Constructor
	 *
	 * @param  int $regular  Regular status.
	 * @param  int $ntgb     NTGB status.
	 */
	public function __construct(
		$regular = self::MODERATION,
		$ntgb = self::MODERATION
	) {
		$this->set_regular_status( $regular );
		$this->set_ntgb_status( $ntgb );
	}

	/**
	 * Status value validation
	 *
	 * @param  int|string $val  Status value.
	 *
	 * @return bool
	 */
	protected function is_valid_status( $val ) {
		return in_array(
			$val,
			array(
				self::APPROVED,
				self::MODERATION,
				self::REJECTED,
			)
		);
	}

	/**
	 * Regular monetization status setter.
	 *
	 * @param  int $val  Status value.
	 *
	 * @return void
	 */
	protected function set_regular_status( $val ) {
		$this->regular = (int) ( $this->is_valid_status( $val ) ? $val : self::MODERATION );
	}

	/**
	 * NTGB monetization status setter.
	 *
	 * @param  int $val  Status value.
	 *
	 * @return void
	 */
	protected function set_ntgb_status( $val ) {
		$this->ntgb = (int) ( $this->is_valid_status( $val ) ? $val : self::MODERATION );
	}

	/**
	 * Regular status getter
	 *
	 * @return int
	 */
	public function get_regular_status() {
		return $this->regular;
	}

	/**
	 * NTGB status getter
	 *
	 * @return int
	 */
	public function get_ntgb_status() {
		return $this->ntgb;
	}

	/**
	 * Check to all monetizations is rejected.
	 *
	 * @return bool
	 */
	public function is_all_rejected() {
		return (
			$this->is_regular_rejected() &&
			$this->is_ntgb_rejected()
		);
	}

	/**
	 * Check to all monetizations on moderation.
	 *
	 * @return bool
	 */
	public function is_all_on_moderation() {
		return (
			$this->is_regular_on_moderation() &&
			$this->is_ntgb_on_moderation()
		);
	}

	/**
	 * Check to all monetizations is approved.
	 *
	 * @return bool
	 */
	public function is_all_approved() {
		return (
			$this->is_regular_approved() &&
			$this->is_ntgb_approved()
		);
	}

	/**
	 * Check to REGULAR monetization is rejected.
	 *
	 * @return bool
	 */
	public function is_regular_rejected() {
		return ( self::REJECTED === $this->regular );
	}

	/**
	 * Check to REGULAR monetization is approved.
	 *
	 * @return bool
	 */
	public function is_regular_approved() {
		return ( self::APPROVED === $this->regular );
	}

	/**
	 * Check to REGULAR monetization on moderation.
	 *
	 * @return bool
	 */
	public function is_regular_on_moderation() {
		return ( self::MODERATION === $this->regular );
	}

	/**
	 * Check to NTGB monetizations is rejected.
	 *
	 * @return bool
	 */
	public function is_ntgb_rejected() {
		return ( self::REJECTED === $this->ntgb );
	}

	/**
	 * Check to NTGB monetizations is approved.
	 *
	 * @return bool
	 */
	public function is_ntgb_approved() {
		return ( self::APPROVED === $this->ntgb );
	}

	/**
	 * Check to NTGB monetizations on moderation.
	 *
	 * @return bool
	 */
	public function is_ntgb_on_moderation() {
		return ( self::MODERATION === $this->ntgb );
	}

	/**
	 * Has approved monetizations.
	 *
	 * @return bool
	 */
	public function has_approved() {
		return (
			self::APPROVED === $this->regular ||
			self::APPROVED === $this->ntgb
		);
	}

	/**
	 * Has on moderation monetizations.
	 *
	 * @return bool
	 */
	public function has_on_moderation() {
		return (
			self::MODERATION === $this->regular ||
			self::MODERATION === $this->ntgb
		);
	}

	/**
	 * Hydrator
	 *
	 * @param  array{regular?: int, ntgb?: int} $data  Arrayed data.
	 *
	 * @return self
	 */
	public static function hydrate( $data ) {
		return new self(
			(int) ( isset( $data['regular'] ) ? $data['regular'] : self::MODERATION ),
			(int) ( isset( $data['ntgb'] ) ? $data['ntgb'] : self::MODERATION )
		);
	}

	/**
	 * Convert to array.
	 *
	 * @return array{regular: int, ntgb: int}
	 */
	public function convert_to_array() {
		return array(
			'regular' => $this->get_regular_status(),
			'ntgb' => $this->get_ntgb_status(),
		);
	}
}
