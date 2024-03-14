<?php
/**
 * Handle the currency object.
 *
 * @package     EverAccounting\Models
 * @class       Currency
 * @version     1.0.2
 */

namespace EverAccounting\Models;

use EverAccounting\Abstracts\Resource_Model;
use EverAccounting\Repositories;

defined( 'ABSPATH' ) || exit;

/**
 * Class Currency
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Models
 */
class Currency extends Resource_Model {
	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'currency';

	/**
	 * Cache group.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	public $cache_group = 'ea_currencies';

	/**
	 * Item Data array.
	 *
	 * @since 1.0.4
	 *
	 * @var array
	 */
	protected $data = array(
		'name'               => '',
		'code'               => '',
		'rate'               => 1,
		'number'             => '',
		'precision'          => 2,
		'subunit'            => 100,
		'symbol'             => '',
		'position'           => 'before',
		'decimal_separator'  => '.',
		'thousand_separator' => ',',
		'date_created'       => null,
	);

	/**
	 * Get the category if ID is passed, otherwise the category is new and empty.
	 *
	 * @param int|string|object|Item $code Item object to read.
	 */
	public function __construct( $code = 0 ) {
		parent::__construct( $code );

		if ( $code instanceof self ) {
			$this->set_code( $code->get_code() );
		} elseif ( is_string( $code ) ) {
			$this->set_code( $code );
		} elseif ( is_array( $code ) && ! empty( $code['code'] ) ) {
			$this->set_code( $code['code'] );
		} elseif ( is_object( $code ) && ! empty( $code->code ) ) {
			$this->set_code( $code->code );
		} else {
			$this->set_object_read( true );
		}

		$this->repository = Repositories::load( 'currencies' );

		if ( ! empty( $this->get_code() ) ) {
			$this->repository->read( $this );
		}

		$this->required_props = array(
			'code'               => __( 'Currency code', 'wp-ever-accounting' ),
			'rate'               => __( 'Currency rate', 'wp-ever-accounting' ),
			'symbol'             => __( 'Currency symbol', 'wp-ever-accounting' ),
			'position'           => __( 'Currency position', 'wp-ever-accounting' ),
			'decimal_separator'  => __( 'Currency decimal separator', 'wp-ever-accounting' ),
			'thousand_separator' => __( 'Currency thousand separator', 'wp-ever-accounting' ),
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	|
	| Functions for getting item data. Getter methods wont change anything unless
	| just returning from the props.
	|
	*/

	/**
	 * Get currency name.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_name( $context = 'edit' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get currency code.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_code( $context = 'edit' ) {
		return $this->get_prop( 'code', $context );
	}

	/**
	 * Get currency rate.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_rate( $context = 'edit' ) {
		return $this->get_prop( 'rate', $context );
	}

	/**
	 * Get currency number.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_number( $context = 'edit' ) {
		return $this->get_prop( 'number', $context );
	}

	/**
	 * Get number of decimal points.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_precision( $context = 'edit' ) {
		return $this->get_prop( 'precision', $context );
	}

	/**
	 * Get number of decimal points.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_subunit( $context = 'edit' ) {
		return $this->get_prop( 'subunit', $context );
	}

	/**
	 * Get currency symbol.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_symbol( $context = 'edit' ) {
		return $this->get_prop( 'symbol', $context );
	}

	/**
	 * Get symbol position.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_position( $context = 'edit' ) {
		return $this->get_prop( 'position', $context );
	}

	/**
	 * Get decimal separator.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_decimal_separator( $context = 'edit' ) {
		return $this->get_prop( 'decimal_separator', $context );
	}

	/**
	 * Get thousand separator.
	 *
	 * @since 1.0.2
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return string
	 */
	public function get_thousand_separator( $context = 'edit' ) {
		return $this->get_prop( 'thousand_separator', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	|
	| Functions for setting item data. These should not update anything in the
	| database itself and should only change what is stored in the class
	| object.
	*/

	/**
	 * Overwrite base so it can accept string.
	 *
	 * Set ID.
	 *
	 * @since 1.1.0
	 *
	 * @param int $id ID.
	 */
	public function set_id( $id ) {
		$this->id = eaccounting_clean( $id );
	}

	/**
	 * Set the currency name.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value  Currency name.
	 */
	public function set_name( $value ) {
		$this->set_prop( 'name', eaccounting_clean( $value ) );
	}

	/**
	 * Set the code.
	 *
	 * @since 1.0.2
	 *
	 * @param string $code Currency code.
	 */
	public function set_code( $code ) {
		$code = eaccounting_sanitize_currency_code( $code );
		if ( ! empty( $code ) ) {
			$this->set_prop( 'code', $code );
		}
	}

	/**
	 * Set the rate.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Currency rate.
	 */
	public function set_rate( $value ) {
		$this->set_prop( 'rate', eaccounting_format_decimal( $value, 7 ) );
	}

	/**
	 * Set the code.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Currency number.
	 */
	public function set_number( $value ) {
		$this->set_prop( 'number', intval( $value ) );
	}

	/**
	 * Set precision.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Currency precision.
	 */
	public function set_precision( $value ) {
		$this->set_prop( 'precision', eaccounting_sanitize_number( $value ) );
	}

	/**
	 * Set precision.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Currency subunit.
	 */
	public function set_subunit( $value ) {
		$this->set_prop( 'subunit', intval( $value ) );
	}

	/**
	 * Set symbol.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Currency symbol.
	 */
	public function set_symbol( $value ) {
		$this->set_prop( 'symbol', eaccounting_clean( $value ) );
	}

	/**
	 * Set symbol position.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Currency symbol position.
	 */
	public function set_position( $value ) {
		if ( in_array( $value, array( 'before', 'after' ), true ) ) {
			$this->set_prop( 'position', eaccounting_clean( $value ) );
		}
	}

	/**
	 * Set decimal separator.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Currency decimal separator.
	 */
	public function set_decimal_separator( $value ) {
		$this->set_prop( 'decimal_separator', eaccounting_clean( $value ) );
	}

	/**
	 * Set thousand separator.
	 *
	 * @since 1.0.2
	 *
	 * @param string $value Currency thousand separator.
	 */
	public function set_thousand_separator( $value ) {
		$this->set_prop( 'thousand_separator', eaccounting_clean( $value ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Additional methods
	|--------------------------------------------------------------------------
	|
	| Does extra thing as helper functions.
	|
	*/

	/**
	 * getPrefix.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_prefix() {
		if ( ! $this->is_symbol_first() ) {
			return '';
		}

		return $this->get_symbol( 'edit' );
	}

	/**
	 * getSuffix.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_suffix() {
		if ( $this->is_symbol_first() ) {
			return '';
		}

		return ' ' . $this->get_symbol( 'edit' );
	}

	/**
	 * equals.
	 *
	 * @since 1.0.2
	 *
	 * @param Currency $currency Currency.
	 *
	 * @return bool
	 */
	public function equals( $currency ) {
		return $this->get_code( 'edit' ) === $currency->get_code( 'edit' );
	}

	/**
	 * is_symbol_first.
	 *
	 * @since 1.0.2
	 *
	 * @return bool
	 */
	public function is_symbol_first() {
		return 'before' === $this->get_position( 'edit' );
	}

}
