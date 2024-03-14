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
 * VikAppointments subscription table.
 *
 * @since 1.7
 */
class VAPTableSubscription extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_subscription', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'group';
		$this->_requiredFields[] = 'name';
		$this->_requiredFields[] = 'amount';
		$this->_requiredFields[] = 'type';
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
		if (empty($src['id']))
		{
			if (!isset($src['group']))
			{
				$src['group'] = 0;
			}

			$src['ordering'] = $this->getNextOrder('`group` = ' . (int) $src['group']);
		}

		if (isset($src['price']))
		{
			// make sure the price is not negative
			$src['price'] = max(array(0, (float) $src['price']));
		}

		if (isset($src['amount']))
		{
			// make sure the amount is not lower than 1
			$src['amount'] = max(array(1, (int) $src['amount']));
		}

		if (isset($src['type']))
		{
			// type must be in the range [1,5]
			$src['type'] = max(array(1, (int) $src['type']));
			$src['type'] = min(array(5, (int) $src['type']));

			if ($src['type'] == 5)
			{
				// lifetime selected, force amount to 1
				$src['amount'] = 1;
			}
		}

		if (isset($src['services']) && is_array($src['services']))
		{
			// stringify services list (for customers group only)
			$src['services'] = implode(',', $src['services']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
