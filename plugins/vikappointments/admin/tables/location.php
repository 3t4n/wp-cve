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
 * VikAppointments location table.
 *
 * @since 1.7
 */
class VAPTableLocation extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_employee_location', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'name';
		$this->_requiredFields[] = 'address';
		$this->_requiredFields[] = 'id_country';
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

		if (isset($src['latitude']) && strlen($src['latitude']))
		{
			// latitude must be in the range of [-90, 90]
			if ($src['latitude'] < -90 || $src['latitude'] > 90)
			{
				// invalid latitude, unset it
				$src['latitude'] = '';
			}
		}

		if (isset($src['longitude']) && strlen($src['longitude']))
		{
			// longitude must be in the range of [-180, 180]
			if ($src['longitude'] < -180 || $src['longitude'] > 180)
			{
				// invalid longitude, unset it
				$src['longitude'] = '';
			}
		}

		if ((isset($src['latitude']) && strlen($src['latitude']) == 0)
			|| (isset($src['longitude']) && strlen($src['longitude']) == 0))
		{
			// unset both lat and lng in case at least one of them is invalid
			$src['latitude'] = $src['longitude'] = null;

			// force update of NULL columns
			$this->_updateNulls = true;
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
