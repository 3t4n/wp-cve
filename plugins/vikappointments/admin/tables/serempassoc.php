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
 * VikAppointments service-employee relation table.
 *
 * @since 1.7
 */
class VAPTableSerempassoc extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_ser_emp_assoc', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'id_service';
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

		// fetch ordering for new record
		if (empty($src['id']) && isset($src['id_service']))
		{
			$src['ordering'] = $this->getNextOrder('id_service = ' . (int) $src['id_service']);
		}

		if (!isset($src['rate']) && isset($src['price']))
		{
			// use a different notation for the rate column
			$src['rate'] = $src['price'];
			unset($src['price']);
		}

		if (isset($src['rate']))
		{
			$src['rate'] = abs((float) $src['rate']);
		}

		if (isset($src['duration']))
		{
			$src['duration'] = abs((float) $src['duration']);
		}

		if (isset($src['sleep']))
		{
			$src['sleep'] = abs((float) $src['sleep']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
