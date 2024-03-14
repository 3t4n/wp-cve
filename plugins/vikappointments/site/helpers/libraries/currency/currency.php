<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Currency class handler.
 *
 * @since 1.7
 */
class VAPCurrency
{
	/**
	 * The currency code (see ISO 4217).
	 *
	 * @var string
	 */
	private $code;

	/**
	 * The currency symbol.
	 *
	 * @var string
	 */
	private $symbol;

	/**
	 * The position of the currency symbol.
	 *
	 * @var integer
	 */
	private $position;

	/**
	 * The type of separator to use (comma or period).
	 *
	 * @var integer
	 */
	private $separator;

	/**
	 * The character to use as decimal separator.
	 *
	 * @var   string
	 * @since 1.7
	 */
	private $decimalMark;

	/**
	 * The character to use as thousands separator.
	 *
	 * @var   string
	 * @since 1.7
	 */
	private $thousandsMark;

	/**
	 * The number of decimal digits to use.
	 *
	 * @var integer
	 */
	private $decimalDigits;

	/**
	 * True to include a space between the symbol and the amount.
	 *
	 * @var boolean
	 */
	private $space;

	/**
	 * Class constructor.
	 *
	 * @param 	string 	 $code
	 * @param 	string 	 $symbol
	 * @param 	integer  $position
	 * @param 	mixed    $separator
	 * @param 	integer  $decimalDigits
	 */
	public function __construct($code, $symbol, $position = self::BEFORE_POSITION, $separator = self::PERIOD_SEPARATOR, $decimalDigits = 2, $space = true)
	{
		$this->setCode($code)
			->setSymbol($symbol)
			->setPosition($position)
			->setSeparator($separator)
			->setDecimalDigits($decimalDigits)
			->setSpace($space);
	}

	/**
	 * Get the currency code.
	 *
	 * @return 	string
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * Set the currency code (see ISO 4217).
	 *
	 * @param 	string 	$code 	The code.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setCode($code)
	{
		$this->code = strtoupper(substr($code, 0, 3));

		return $this;
	}

	/**
	 * Get the currency symbol.
	 *
	 * @return 	string
	 */
	public function getSymbol()
	{
		return $this->symbol;
	}

	/**
	 * Set the currency symbol.
	 *
	 * @param 	string 	$symbol 	The symbol.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setSymbol($symbol)
	{
		$this->symbol = $symbol;

		return $this;
	}

	/**
	 * Check if the symbol should be displayed before the amount.
	 *
	 * @return 	boolean
	 */
	public function isSymbolBefore($pos = null)
	{
		$pos = $pos ? $pos : $this->position;

		return abs($pos) == self::BEFORE_POSITION;
	}

	/**
	 * Check if the symbol should be displayed after the amount.
	 *
	 * @return 	boolean
	 */
	public function isSymbolAfter($pos = null)
	{
		return $this->isSymbolBefore($pos) === false;
	}

	/**
	 * Get the position of the currency symbol.
	 *
	 * @return 	integer
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * Set the position of the currency symbol.
	 *
	 * @param 	integer 	$position 	The position.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setPosition($position)
	{
		$this->position = (int) $position;

		return $this;
	}

	/**
	 * Get the decimal mark to use.
	 *
	 * @return 	string
	 */
	public function getDecimalMark()
	{
		// check whether a custom mark was specified
		if ($this->decimalMark && preg_match("/[.,\s]/", $this->decimalMark))
		{
			return $this->decimalMark;
		}

		return $this->separator == self::COMMA_SEPARATOR ? ',' : '.';
	}

	/**
	 * Get the thousands mark to use.
	 *
	 * @return 	string
	 */
	public function getThousandsMark()
	{
		$mark = $this->separator == self::COMMA_SEPARATOR ? '.' : ',';

		// check whether a custom mark was specified
		if (!is_null($this->thousandsMark) && (empty($this->thousandsMark) || preg_match("/[.,\s]/", $this->thousandsMark)))
		{
			// use given separator
			$mark = $this->thousandsMark;
		}

		$decimal = $this->getDecimalMark();

		// make sure the separators are not equal
		if ($this->thousandsMark == $decimal)
		{
			// use comma in case the decimal mark uses a dot and viceversa
			$mark = $decimal == ',' ? '.' : ',';
		}

		return $mark;
	}

	/**
	 * Set the type of separator to use (comma or period).
	 *
	 * @param 	mixed 	$separator 	The separator.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setSeparator($separator)
	{
		if (is_array($separator) && count($separator) == 2)
		{
			// set custom separators
			$this->decimalMark   = substr($separator[0], 0, 1);
			$this->thousandsMark = substr($separator[1], 0, 1);
		}
		else
		{
			if (is_string($separator))
			{
				// get separator code
				$separator = $separator == ',' ? self::COMMA_SEPARATOR : self::PERIOD_SEPARATOR;
			}

			$this->separator = (int) $separator;
		}

		return $this;
	}

	/**
	 * Get the number of decimal digits.
	 *
	 * @return 	integer
	 */
	public function getDecimalDigits()
	{
		return $this->decimalDigits;
	}

	/**
	 * Set the number of decimal digits to use.
	 *
	 * @param 	integer 	$decimalDigits 	The decimal digits.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setDecimalDigits($decimalDigits)
	{
		$this->decimalDigits = (int) $decimalDigits;

		return $this;
	}

	/**
	 * Check whether the currency should use a space between the
	 * currency symbol and the amount.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function isSpace()
	{
		return $this->space;
	}

	/**
	 * Sets/unsets the space between the currency symbol and the amount.
	 *
	 * @param 	boolean  $space  True to include the space.
	 *
	 * @return 	self 	 This object to support chaining.
	 *
	 * @since 	1.7
	 */
	public function setSpace($space)
	{
		$this->space = (bool) $space;

		return $this;
	}

	/**
	 * Convert the specified amount in a price string.
	 *
	 * @param 	float 	$amount  The amount to format.
	 *
	 * @return 	string 	The formatted price.
	 */
	public function format($amount = 0.0, array $options = array())
	{
		$options['dec_digits'] 		= isset($options['dec_digits']) 	? $options['dec_digits'] 		: $this->getDecimalDigits();
		$options['decimal_mark'] 	= isset($options['decimal_mark']) 	? $options['decimal_mark'] 		: $this->getDecimalMark();
		$options['thousands_mark'] 	= isset($options['thousands_mark']) ? $options['thousands_mark'] 	: $this->getThousandsMark();
		$options['symbol']			= isset($options['symbol']) 		? $options['symbol'] 			: $this->getSymbol();
		$options['position']		= isset($options['position']) 		? $options['position'] 			: $this->getPosition();
		$options['space'] 			= isset($options['space']) 			? $options['space'] 			: $this->isSpace();
		$options['no_decimal'] 		= isset($options['no_decimal']) 	? $options['no_decimal'] 		: false;

		if ($options['no_decimal'] && (int) $amount == $amount)
		{
			$options['dec_digits'] = 0;
		}

		$amount = number_format(
			$amount,
			$options['dec_digits'],
			$options['decimal_mark'],
			$options['thousands_mark']
		);

		if ($this->isSymbolBefore($options['position']))
		{
			return $options['symbol'] . ($options['space'] ? ' ' : '') . $amount;
		}

		return $amount . ($options['space'] ? ' ' : '') . $options['symbol'];
	}

	/**
	 * Constant to display the currency symbol after the price.
	 *
	 * @var integer
	 */
	const AFTER_POSITION = 1;

	/**
	 * Constant to display the currency symbol before the price.
	 *
	 * @var integer
	 */
	const BEFORE_POSITION = 2;

	/**
	 * Constant to use the comma (,) as decimal separator.
	 *
	 * @var integer
	 */
	const COMMA_SEPARATOR = 1;

	/**
	 * Constant to use the period (.) as decimal separator.
	 *
	 * @var integer
	 */
	const PERIOD_SEPARATOR = 2;
}
