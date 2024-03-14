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
 * VikAppointments city table.
 *
 * @since 1.7
 */
class VAPTableCity extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_cities', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'city_name';
		$this->_requiredFields[] = 'id_state';
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

		if (isset($src['city_2_code']))
		{
			// use only uppercase letters
			$src['city_2_code'] = strtoupper($src['city_2_code']);
		}

		if (isset($src['city_3_code']))
		{
			// use only uppercase letters
			$src['city_3_code'] = strtoupper($src['city_3_code']);
		}

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

	/**
	 * Method to perform sanity checks on the Table instance properties to
	 * ensure they are safe to store in the database.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
	public function check()
	{
		// check integrity using parent
		if (!parent::check())
		{
			return false;
		}

		// in case of city codes, make sure we are not going to create duplicated
		if (!empty($this->city_2_code) || !empty($this->city_3_code))
		{
			if (empty($this->id_state))
			{
				// the state ID is mandatory when changing a unique code
				$this->setError(JText::sprintf('VAP_INVALID_REQ_FIELD', JText::translate('VAPMANAGEEMPLOCATION2')));

				return false;
			}

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true);

			if ($this->city_2_code)
			{
				$where[] = $dbo->qn('city_2_code') . ' = ' . $dbo->q($this->city_2_code);
			}

			if ($this->city_3_code)
			{
				$where[] = $dbo->qn('city_3_code') . ' = ' . $dbo->q($this->city_3_code);
			}

			$q->select(1);
			$q->from($dbo->qn($this->getTableName()));
			$q->where($dbo->qn('id') . ' <> ' . $this->id);
			$q->where($dbo->qn('id_state') . ' = ' . (int) $this->id_state);
			$q->andWhere($where, 'OR');
			
			$dbo->setQuery($q, 0, 1);
			$dbo->execute();
			
			if ($dbo->getNumRows() > 0)
			{
				// a city already exists with the given code(s)
				$this->setError(JText::translate('VAPCITYUNIQUEERROR'));

				return false;
			}
		}

		return true;
	}
}
