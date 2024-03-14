<?php

/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */

namespace CashBill\Payments\Model;

use CashBill\Payments\Interfaces\Data;
use CashBill\Payments\Services\CashBillTransactionException;

class Amount implements Data
{
	/**
	 *
	 * @var string $value
	 * @var string $currencyCode
	 */
	private $value, $currencyCode;

	/**
	 *
	 * @param float $value        	
	 * @param string $currencyCode        	
	 */
	function __construct($value, $currencyCode)
	{

		if (!is_numeric($value)) {
			throw new CashBillTransactionException("Amount value not numeric");
		}

		if (strlen($currencyCode) !== 3) {
			throw new CashBillTransactionException("Currency code not valid");
		}

		$this->value = number_format($value, 2, ".", "");
		$this->currencyCode = $currencyCode;
	}
	public static function fromArray($array)
	{
		return new Amount($array['value'], $array['currencyCode']);
	}

	/**
	 *
	 * @see CashBillData::toArray()
	 */
	public function toArray()
	{
		$array = array();
		foreach ($this as $key => $value) {
			$array["amount." . $key] = $value;
		}
		return $array;
	}
}
