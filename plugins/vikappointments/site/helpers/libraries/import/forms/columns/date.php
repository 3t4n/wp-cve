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
 * Formats the given date/timestamp into a globally
 * recognized format (military).
 *
 * @since 1.7
 */
class ImportColumnDate extends ImportColumn
{
	/**
	 * Helper method used to manipulate the given value before
	 * binding the object to save.
	 *
	 * @param 	mixed   $value  The default import value.
	 *
	 * @return 	string  The value to bind
	 */
	public function onImport($value)
	{
		if (VAPDateHelper::isNull($value))
		{
			// invalid date
			return null;
		}

		// check whether the given value is not a timestamp
		if (!is_numeric($value))
		{
			// look for a "Z" at the end, which stands for UTC date
			if (preg_match("/Z$/", $value))
			{
				// remove T and Z from date
				$value = trim(preg_replace("/[TZ]/", ' ', $value));
			}
			else
			{
				try
				{
					// no trailing "Z", assume the date was set
					// into the local timezone of the user
					$value = new JDate($value, JFactory::getUser()->getTimezone());
					// now re-format in UTC
					$value = $value->toSql();
				}
				catch (Exception $e)
				{
					// invalid date...
					return null;
				}
			}
		}

		return $value;
	}

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
		$date = parent::format($value);

		if ($value != $date)
		{
			// value has been manipulated by the parent,
			// we don't need to go ahead
			return $date;
		}

		if (VAPDateHelper::isNull($date))
		{
			// return an empty string in case of null date
			return '';
		}

		if (is_numeric($date))
		{
			// format timestamp into military one
			$date = date('Y-m-d H:i:s', $date);
		}
		else
		{
			$date = JHtml::fetch('date', $date, 'Y-m-d H:i:s');
		}

		return $date;
	}
}
