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
 * VikAppointments package order item table.
 *
 * @since 1.7
 */
class VAPTablePackorderitem extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_package_order_item', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'id_order';
		$this->_requiredFields[] = 'id_package';
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

		if (isset($src['price']))
		{
			// price cannot be lower than 0
			$src['price'] = max(array(0, (float) $src['price']));
		}

		if (isset($src['quantity']))
		{
			// quantity cannot be lower than 1
			$src['quantity'] = max(array(1, (float) $src['quantity']));
		}

		if (isset($src['num_app']))
		{
			// number of appointments cannot be lower than 1
			$src['num_app'] = max(array(1, (float) $src['num_app']));
		}

		if (isset($src['used_app']))
		{
			// number of used appointments cannot be lower than 0
			$src['used_app'] = max(array(0, (float) $src['used_app']));

			if (isset($src['num_app']))
			{
				// used app. cannot exceed the threshold (num app.)
				$src['used_app'] = min(array($src['used_app'], $src['num_app']));
			}
		}

		// JSON encode the tax breakdown, when specified as array
		if (isset($src['tax_breakdown']) && !is_string($src['tax_breakdown']))
		{
			$src['tax_breakdown'] = json_encode($src['tax_breakdown']);
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
