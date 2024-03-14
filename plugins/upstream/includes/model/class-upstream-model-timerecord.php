<?php
/**
 * UpStream_Model_TimeRecord
 *
 * WordPress Coding Standart (WCS) note:
 * All camelCase methods and object properties on this file are not converted to snake_case,
 * because it being used (heavily) on another add-on plugins.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UpStream_Model_TimeRecord
 */
class UpStream_Model_TimeRecord {

	/**
	 * Id
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * User
	 *
	 * @var int
	 */
	protected $user = 0;

	/**
	 * Start Timestamp
	 *
	 * @var int
	 */
	protected $startTimestamp = 0; // phpcs:ignore

	/**
	 * Elapsed Time
	 *
	 * @var int
	 */
	protected $elapsedTime = 0; // phpcs:ignore

	/**
	 * Budgeted
	 *
	 * @var int
	 */
	protected $budgeted = 0;

	/**
	 * Spent
	 *
	 * @var int
	 */
	protected $spent = 0;

	/**
	 * Note
	 *
	 * @var string
	 */
	protected $note = '';

	/**
	 * Construct
	 *
	 * @param  mixed $item_metadata Item Metadata.
	 * @return void
	 */
	public function __construct( $item_metadata ) {
		$this->loadFromArray( $item_metadata );
	}

	/**
	 * Load From Array
	 *
	 * @param  mixed $item_metadata Item Metadata.
	 * @return void
	 */
	protected function loadFromArray( $item_metadata ) { // phpcs:ignore
		$this->id             = ! empty( $item_metadata['id'] ) ? $item_metadata['id'] : 0;
		$this->user           = ! empty( $item_metadata['user'] ) ? $item_metadata['user'] : 0;
		$this->startTimestamp = ! empty( $item_metadata['startTimestamp'] ) ? $item_metadata['startTimestamp'] : 0; // phpcs:ignore
		$this->elapsedTime    = ! empty( $item_metadata['elapsedTime'] ) ? $item_metadata['elapsedTime'] : 0; // phpcs:ignore
		$this->budgeted       = ! empty( $item_metadata['budgeted'] ) ? $item_metadata['budgeted'] : null;
		$this->spent          = ! empty( $item_metadata['spent'] ) ? $item_metadata['spent'] : null;
		$this->note           = ! empty( $item_metadata['note'] ) ? $item_metadata['note'] : null;
	}

	/**
	 * Store To Array
	 *
	 * @param  mixed $item_metadata Item Metadata.
	 * @return void
	 */
	public function storeToArray( &$item_metadata ) { // phpcs:ignore
		if ( $this->startTimestamp > 0 ) { // phpcs:ignore
			$hours             = round( ( time() - $this->startTimestamp ) / 3600, 1 ); // phpcs:ignore
			$this->elapsedTime = $hours; // phpcs:ignore
		}

		$item_metadata['id']             = $this->id;
		$item_metadata['user']           = $this->user;
		$item_metadata['startTimestamp'] = $this->startTimestamp; // phpcs:ignore
		$item_metadata['elapsedTime']    = $this->elapsedTime; // phpcs:ignore
		$item_metadata['budgeted']       = $this->budgeted;
		$item_metadata['spent']          = $this->spent;
		$item_metadata['note']           = $this->note;
	}

	/**
	 * Work Hours Per Day
	 */
	public static function workHoursPerDay() { // phpcs:ignore
		$options     = get_option( 'upstream_general' );
		$option_name = 'local_work_hours_per_day';
		$hrs         = isset( $options[ $option_name ] ) ? (int) $options[ $option_name ] : 8;

		return $hrs;
	}

	/**
	 * Monetary Symbol
	 */
	public static function monetarySymbol() { // phpcs:ignore
		$options     = get_option( 'upstream_general' );
		$option_name = 'local_monetary_symbol';
		$sym         = isset( $options[ $option_name ] ) ? $options[ $option_name ] : '$';

		return $sym;
	}

	// Phpcs ignore camelCase methods and object properties.
	// phpcs:disable

	/**
	 * Start Timing
	 */
	public function startTiming() {
		$this->startTimestamp = time();
		$this->elapsedTime    = 0;
	}

	/**
	 * Stop Timing
	 */
	public function stopTiming() {
		$hours                = round( ( time() - $this->startTimestamp ) / 3600, 1 );
		$this->elapsedTime    = $hours;
		$this->startTimestamp = 0;
	}

	/**
	 * Is Timing
	 */
	public function isTiming() {
		return ( 0 !== $this->startTimestamp );
	}

	// phpcs:enable

	/**
	 * Format Elapsed
	 *
	 * @param  mixed $elapsed Elapsed.
	 */
	public static function formatElapsed( $elapsed ) { // phpcs:ignore
		$days  = floor( $elapsed / self::workHoursPerDay() );
		$hours = $elapsed - ( $days * self::workHoursPerDay() );
		return round( $days, 0 ) . ' days' . ( $hours > 0 ? ', ' . round( $hours, 1 ) . ' hours' : '' );
	}

	/**
	 * Format Budgeted
	 *
	 * @param  mixed $budgeted Budgeted.
	 */
	public static function formatBudgeted( $budgeted ) { // phpcs:ignore
		return self::monetarySymbol() . $budgeted;
	}

	/**
	 * Format Spent
	 *
	 * @param  mixed $spent Spent.
	 */
	public static function formatSpent( $spent ) { // phpcs:ignore
		return self::monetarySymbol() . $spent;
	}

	/**
	 * Get
	 *
	 * @param  mixed $property Property.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public function __get( $property ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'id':
			case 'startTimestamp':
			case 'user':
			case 'note':
			case 'budgeted':
			case 'spent':
				return $this->{$property};

			case 'elapsedTime':
				if ( $this->startTimestamp > 0 ) { // phpcs:ignore
					$hours             = round( ( time() - $this->startTimestamp ) / 3600, 1 ); // phpcs:ignore
					$this->elapsedTime = $hours; // phpcs:ignore
				}

				return $this->elapsedTime; // phpcs:ignore

			default:
				throw new UpStream_Model_ArgumentException(
					sprintf(
					// translators: %s: property.
						__( 'This (%s) is not a valid property.', 'upstream' ),
						$property
					)
				);
		}
	}

	/**
	 * Set
	 *
	 * @param  mixed $property Property.
	 * @param  mixed $value Value.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public function __set( $property, $value ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'id':
				if ( ! preg_match( '/^[a-zA-Z0-9]+$/', $value ) ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
						// translators: %s: ID.
							__( 'ID %s must be a valid alphanumeric.', 'upstream' ),
							$value
						)
					);
				}
				$this->{$property} = $value;
				break;

			case 'note':
				$this->{$property} = wp_kses_post( $value );
				break;

			case 'user':
			case 'user:byUsername':
			case 'user:byEmail':
				$uid  = $value;
				$user = false;

				if ( 'user' === $property ) {
					$user = get_user_by( 'id', $uid );
				}
				if ( 'user:byUsername' === $property ) {
					$user = get_user_by( 'login', $uid );
				}
				if ( 'user:byEmail' === $property ) {
					$user = get_user_by( 'email', $uid );
				}

				if ( false === $user ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
						// translators: %1$s: user.
						// translators: %2$s: field.
							__( 'User "%1$s" (for field %2$s) does not exist.', 'upstream' ),
							$uid,
							$property
						)
					);
				}

				$this->user = $user->ID;
				break;

			case 'spent':
			case 'budgeted':
				if ( $value && ! is_numeric( $value ) ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
						// translators: %s: value.
							__( 'This (%s) is not a number.', 'upstream' ),
							$property
						)
					);
				}

				$this->{$property} = $value;

				break;

			case 'startTimestamp':
			case 'elapsedTime':
				if ( ! is_numeric( $value ) ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
						// translators: %s: number value.
							__( 'This (%s) is not a number.', 'upstream' ),
							$property
						)
					);
				}

				$this->{$property} = $value;

				break;

			default:
				throw new UpStream_Model_ArgumentException(
					sprintf(
					// translators: %s: property.
						__( 'This (%s) is not a valid property.', 'upstream' ),
						$property
					)
				);
		}
	}

}
