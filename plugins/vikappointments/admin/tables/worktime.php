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
 * VikAppointments employee working time table.
 *
 * @since 1.7
 */
class VAPTableWorktime extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_emp_worktime', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'id_employee';
	}

	/**
	 * Method to bind an associative array or object to the Table instance. This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array|object  $src     An associative array or object to bind to the Table instance.
	 * @param   array|string  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function bind($src, $ignore = array())
	{
		$src = (array) $src;

		if (!isset($src['ts']) && !empty($src['date']))
		{
			$src['ts'] = $src['date'];
		}

		if (isset($src['ts']))
		{
			// check whether we have a date string
			if (!preg_match("/^[0-9]+$/", $src['ts']))
			{
				// convert it into a timestamp
				$src['ts'] = VikAppointments::createTimestamp($src['ts'], 0, 0);
			}

			if ($src['ts'] != -1)
			{
				// we have a timestamp, recalculate correct day of the week
				$src['day'] = (int) date('w', $src['ts']);
				// create date string to be stored within the DB
				$src['tsdate'] = JDate::getInstance(date('Y-m-d', $src['ts']))->toSql();
			}
			else
			{
				// use a null date otherwise
				$src['tsdate'] = JFactory::getDbo()->getNullDate();
			}
		}

		if (!isset($src['fromts']) && isset($src['from']))
		{
			// initial time is located in a different attribute
			$src['fromts'] = $src['from'];
		}

		if (!isset($src['endts']) && isset($src['to']))
		{
			// initial time is located in a different attribute
			$src['endts'] = $src['to'];
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
