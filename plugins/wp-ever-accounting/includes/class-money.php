<?php
/**
 *
 * Handle the money object
 *
 * @package        EverAccounting
 * @version        1.0.2
 */

namespace EverAccounting;

use EverAccounting\Models\Currency;

defined( 'ABSPATH' ) || exit;

/**
 * Class Money
 *
 * @since   1.0.2
 * @package EverAccounting
 */
class Money {
	const ROUND_HALF_UP   = PHP_ROUND_HALF_UP;
	const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
	const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
	const ROUND_HALF_ODD  = PHP_ROUND_HALF_ODD;

	/**
	 * Amount.
	 *
	 * @since 1.0.2
	 * @var float|int
	 */
	protected $amount;

	/**
	 * Currency object
	 *
	 * @since 1.0.2
	 * @var Currency
	 */
	protected $currency;

	/**
	 * Money constructor.
	 *
	 * @since 1.0.2
	 *
	 * @param string $amount Amount to convert.
	 * @param string $code Currency object.
	 * @param bool   $convert Convert to default currency.
	 *
	 * @throws \Exception If currency is not found.
	 */
	public function __construct( $amount, $code, $convert = false ) {
		$this->currency = new Currency( $code );
		$this->amount   = $this->parse_amount( $amount, $convert );
	}

	/**
	 * parse_amount.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $amount Amount to convert.
	 * @param bool  $convert Convert to default currency.
	 *
	 * @throws \UnexpectedValueException If currency is not found.
	 * @return int|float
	 */
	protected function parse_amount( $amount, $convert = false ) {
		$amount = $this->parse_amount_from_string( $this->parse_amount_from_callable( $amount ) );

		if ( is_int( $amount ) ) {
			return (int) $this->convert_amount( $amount, $convert );
		}
		if ( is_float( $amount ) ) {
			return (float) round( $this->convert_amount( $amount, $convert ), $this->currency->get_precision() );
		}

		return 0;
	}

	/**
	 * parse_amount_from_callable.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $amount Amount to convert.
	 *
	 * @return mixed
	 */
	protected function parse_amount_from_callable( $amount ) {
		if ( ! is_callable( $amount ) ) {
			return $amount;
		}

		return $amount();
	}

	/**
	 * parse_amount_from_string.
	 *
	 * @since 1.0.2
	 *
	 * @param mixed $amount Amount to convert.
	 *
	 * @return int|float|mixed
	 */
	protected function parse_amount_from_string( $amount ) {
		if ( ! is_string( $amount ) ) {
			return $amount;
		}

		$thousands_separator = $this->currency->get_thousand_separator( 'edit' );
		$decimal_mark        = $this->currency->get_decimal_separator( 'edit' );

		$amount = str_replace( $this->currency->get_symbol(), '', $amount );
		$amount = preg_replace( '/[^0-9\\' . $thousands_separator . '\\' . $decimal_mark . '\-\+]/', '', $amount );
		$amount = str_replace(
			array(
				$thousands_separator,
				$decimal_mark,
			),
			array( '', '.' ),
			$amount
		);

		if ( preg_match( '/^([\-\+])?\d+$/', $amount ) ) {
			$amount = (int) $amount;
		} elseif ( preg_match( '/^([\-\+])?\d+\.\d+$/', $amount ) ) {
			$amount = (float) $amount;
		}

		return $amount;
	}

	/**
	 * convert_amount.
	 *
	 * @since 1.0.2
	 *
	 * @param int|float $amount Amount to convert.
	 * @param bool      $convert Convert to default currency.
	 *
	 * @return int|float
	 */
	protected function convert_amount( $amount, $convert = false ) {
		if ( ! $convert ) {
			return $amount;
		}

		return $amount * $this->currency->get_subunit();
	}

	/**
	 * __callStatic.
	 *
	 * @since 1.0.2
	 *
	 * @param string $method    Method name.
	 * @param array  $arguments Method arguments.
	 *
	 * @return Money
	 */
	public static function __callStatic( $method, array $arguments ) {
		$convert = ( isset( $arguments[1] ) && is_bool( $arguments[1] ) ) ? (bool) $arguments[1] : false;

		return new static( $arguments[0], new Currency( $method ), $convert );
	}

	/**
	 * assert_same_currency.
	 *
	 * @since 1.0.2
	 *
	 * @param Money $other Other money object.
	 *
	 * @throws \InvalidArgumentException If currency is not the same.
	 */
	protected function assert_same_currency( $other ) {
		if ( ! $this->is_same_currency( $other ) ) {
			throw new \InvalidArgumentException( 'Different currencies "' . $this->currency . '" and "' . $other->currency . '"' );
		}
	}

	/**
	 * assert_operand.
	 *
	 * @since 1.0.2
	 *
	 * @param int|float $operand    Operand.
	 *
	 * @throws \InvalidArgumentException If operand is not numeric.
	 */
	protected function assert_operand( $operand ) {
		if ( ! is_int( $operand ) && ! is_float( $operand ) ) {
			throw new \InvalidArgumentException( 'Operand "' . $operand . '" should be an integer or a float' );
		}
	}

	/**
	 * assert_rounding_mode.
	 *
	 * @since 1.0.2
	 *
	 * @param int $rounding_mode Rounding mode.
	 *
	 * @throws \OutOfBoundsException If rounding mode is not valid.
	 */
	protected function assert_rounding_mode( $rounding_mode ) {
		$rounding_modes = array( self::ROUND_HALF_DOWN, self::ROUND_HALF_EVEN, self::ROUND_HALF_ODD, self::ROUND_HALF_UP );

		if ( ! in_array( $rounding_mode, $rounding_modes, true ) ) {
			throw new \OutOfBoundsException( 'Rounding mode should be ' . implode( ' | ', $rounding_modes ) );
		}
	}

	/**
	 * getAmount.
	 *
	 * @since       1.0.2
	 *
	 * @return int|float
	 */
	public function get_amount() {
		return $this->amount;
	}

	/**
	 * get_value.
	 *
	 * @since 1.0.2
	 *
	 * @return float
	 */
	public function get_value() {
		if ( $this->currency->get_subunit() && $this->currency->get_precision() ) {
			return round( $this->amount / $this->currency->get_subunit(), $this->currency->get_precision() );
		}

		return $this->amount;
	}

	/**
	 * getCurrency.
	 *
	 * @since 1.0.2
	 *
	 * @return Currency
	 */
	public function get_currency() {
		return $this->currency;
	}

	/**
	 * is_same_currency.
	 *
	 * @since 1.0.2
	 *
	 * @param Money $other Other money object.
	 *
	 * @return bool
	 */
	public function is_same_currency( $other ) {
		return $this->currency->equals( $other->currency );
	}

	/**
	 * compare.
	 *
	 * @since       1.0.2
	 *
	 * @param Money $other Money to compare.
	 *
	 * @throws \InvalidArgumentException If currency is not the same.
	 * @return int
	 */
	public function compare( $other ) {
		$this->assert_same_currency( $other );

		if ( $this->amount < $other->amount ) {
			return - 1;
		}

		if ( $this->amount > $other->amount ) {
			return 1;
		}

		return 0;
	}

	/**
	 * equals.
	 *
	 * @since 1.0.2
	 *
	 * @param Money $other Money to compare.
	 *
	 * @return bool
	 */
	public function equals( $other ) {
		return $this->compare( $other ) === 0;
	}


	/**
	 * convert.
	 *
	 * @since 1.0.2
	 *
	 * @param Currency  $currency    Currency.
	 * @param int|float $ratio    Conversion ratio.
	 * @param int       $rounding_mode Rounding mode.
	 *
	 * @throws \InvalidArgumentException If ratio is not numeric.
	 * @throws \OutOfBoundsException If rounding mode is not valid.
	 *
	 * @return Money
	 */
	public function convert( Currency $currency, $ratio, $rounding_mode = self::ROUND_HALF_UP ) {
		$this->currency = $currency;

		$this->assert_operand( $ratio );
		$this->assert_rounding_mode( $rounding_mode );

		if ( $ratio < 1 ) {
			return $this->divide( $ratio, $rounding_mode );
		}

		return $this->multiply( $ratio, $rounding_mode );
	}

	/**
	 * add.
	 *
	 * @since 1.0.2
	 *
	 * @param Money $addend Money to add.
	 *
	 * @return Money
	 */
	public function add( $addend ) {
		$this->assert_same_currency( $addend );

		return new self( $this->amount + $addend->amount, $this->currency );
	}

	/**
	 * subtract.
	 *
	 * @since 1.0.2
	 *
	 * @param Money $subtrahend Money to subtract.
	 *
	 * @throws \InvalidArgumentException|\Exception If currency is not the same.
	 * @return Money
	 */
	public function subtract( $subtrahend ) {
		$this->assert_same_currency( $subtrahend );

		return new self( $this->amount - $subtrahend->amount, $this->currency );
	}

	/**
	 * multiply.
	 *
	 * @since 1.0.2
	 *
	 * @param int|float $multiplier Multiplier.
	 * @param int       $rounding_mode Rounding mode.
	 *
	 * @throws \InvalidArgumentException If multiplier is not numeric.
	 * @throws \OutOfBoundsException If rounding mode is not valid.
	 *
	 * @return Money
	 */
	public function multiply( $multiplier, $rounding_mode = self::ROUND_HALF_UP ) {
		return new self( round( $this->amount * $multiplier, $this->currency->get_precision(), $rounding_mode ), $this->currency );
	}

	/**
	 * divide.
	 *
	 * @since 1.0.2
	 *
	 * @param int|float $divisor Divisor.
	 * @param int       $rounding_mode Rounding mode.
	 *
	 * @throws \InvalidArgumentException If divisor is not numeric.
	 * @throws \OutOfBoundsException If rounding mode is not valid.
	 *
	 * @return Money
	 */
	public function divide( $divisor, $rounding_mode = self::ROUND_HALF_UP ) {
		$this->assert_operand( $divisor );
		$this->assert_rounding_mode( $rounding_mode );
		if ( empty( $divisor ) ) {
			/* translators: %s amount %s currency */
			eaccounting_doing_it_wrong( __METHOD__, sprintf( __( 'Division by zero is not permitted amount %1$s currency %2$s', 'wp-ever-accounting' ), $this->amount, $this->currency ), null );
			$divisor = 1;
		}

		return new self( round( $this->amount / $divisor, $this->currency->get_precision(), $rounding_mode ), $this->currency );
	}

	/**
	 * allocate.
	 *
	 * @since  1.0.2
	 *
	 * @param array $ratios Ratios.
	 *
	 * @return array
	 */
	public function allocate( array $ratios ) {
		$remainder = $this->amount;
		$results   = array();
		$total     = array_sum( $ratios );

		foreach ( $ratios as $ratio ) {
			$share      = floor( $this->amount * $ratio / $total );
			$results[]  = new self( $share, $this->currency );
			$remainder -= $share;
		}

		for ( $i = 0; $remainder > 0; $i ++ ) {
			$results[ $i ]->amount ++;
			$remainder --;
		}

		return $results;
	}

	/**
	 * is_zero.
	 *
	 * @since 1.0.2
	 *
	 * @return bool
	 */
	public function is_zero() {
		return 0 === $this->amount;
	}

	/**
	 * is_positive.
	 *
	 * @since 1.0.2
	 *
	 * @return bool
	 */
	public function is_positive() {
		return $this->amount > 0;
	}

	/**
	 * is_negative.
	 *
	 * @since 1.0.2
	 *
	 * @return bool
	 */
	public function is_negative() {
		return $this->amount < 0;
	}

	/**
	 * formatSimple.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function format_simple() {
		return number_format(
			$this->get_value(),
			$this->currency->get_precision(),
			$this->currency->get_decimal_separator( 'edit' ),
			$this->currency->get_thousand_separator( 'edit' )
		);
	}

	/**
	 * format.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function format() {
		$negative  = $this->is_negative();
		$value     = $this->get_value();
		$amount    = $negative ? - $value : $value;
		$thousands = $this->currency->get_thousand_separator( 'edit' );
		$decimals  = $this->currency->get_decimal_separator( 'edit' );
		$prefix    = $this->currency->get_prefix();
		$suffix    = $this->currency->get_suffix();
		$value     = number_format( $amount, $this->currency->get_precision(), $decimals, $thousands );

		return ( $negative ? '-' : '' ) . $prefix . $value . $suffix;
	}

	/**
	 * Get the instance as an array.
	 *
	 * @since  1.0.2
	 *
	 * @return array
	 */
	public function to_array() {
		return array(
			'amount'   => $this->amount,
			'value'    => $this->get_value(),
			'currency' => $this->currency,
		);
	}

	/**
	 * Convert the object to its JSON representation.
	 *
	 * @param int $options Options.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function to_json( $options = 0 ) {
		return wp_json_encode( $this->to_array(), $options );
	}

	/**
	 * __toString.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->format();
	}
}

