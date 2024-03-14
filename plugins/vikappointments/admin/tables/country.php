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
 * VikAppointments country table.
 *
 * @since 1.7
 */
class VAPTableCountry extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_countries', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'country_name';
		$this->_requiredFields[] = 'country_2_code';
		$this->_requiredFields[] = 'country_3_code';
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

		if (isset($src['country_2_code']))
		{
			// use only uppercase letters
			$src['country_2_code'] = strtoupper($src['country_2_code']);
		}

		if (isset($src['country_3_code']))
		{
			// use only uppercase letters
			$src['country_3_code'] = strtoupper($src['country_3_code']);
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

		// in case of country codes, make sure we are not going to create duplicated
		if (!empty($this->country_2_code) || !empty($this->country_3_code))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true);

			if ($this->country_2_code)
			{
				$where[] = $dbo->qn('country_2_code') . ' = ' . $dbo->q($this->country_2_code);
			}

			if ($this->country_3_code)
			{
				$where[] = $dbo->qn('country_3_code') . ' = ' . $dbo->q($this->country_3_code);
			}

			$q->select(1);
			$q->from($dbo->qn($this->getTableName()));
			$q->where($dbo->qn('id') . ' <> ' . $this->id);
			$q->andWhere($where, 'OR');
			
			$dbo->setQuery($q, 0, 1);
			$dbo->execute();
			
			if ($dbo->getNumRows() > 0)
			{
				// a country already exists with the given code(s)
				$this->setError(JText::translate('VAPCOUNTRYUNIQUEERROR'));

				return false;
			}
		}

		return true;
	}
}
