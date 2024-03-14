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
 * Formats the given float into a formatted currency.
 *
 * @since 1.7
 */
class ImportColumnCurrency extends ImportColumn
{
	/**
	 * Helper method used to format the values under this column.
	 *
	 * @param 	mixed   $value  The default column value.
	 *
	 * @return 	string  The formatted value
	 */
	public function format($value)
	{
		// format with parent first
		$amount = parent::format($value);

		if ($value != $amount)
		{
			// value has been manipulated by the parent,
			// we don't need to go ahead
			return $amount;
		}

		// format as currency
		$amount = VAPFactory::getCurrency()->format($amount);

		return $amount;
	}
}
