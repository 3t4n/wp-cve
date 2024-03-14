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
 * VikAppointments state table.
 *
 * @since 1.7
 */
class VAPTableState extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_states', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'state_name';
		$this->_requiredFields[] = 'state_2_code';
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

		if (isset($src['state_2_code']))
		{
			// use only uppercase letters
			$src['state_2_code'] = strtoupper($src['state_2_code']);
		}

		if (isset($src['state_3_code']))
		{
			// use only uppercase letters
			$src['state_3_code'] = strtoupper($src['state_3_code']);
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

		// in case of state codes, make sure we are not going to create duplicated
		if (!empty($this->state_2_code) || !empty($this->state_3_code))
		{
			if (empty($this->id_country))
			{
				// the country ID is mandatory when changing a unique code
				$this->setError(JText::sprintf('VAP_INVALID_REQ_FIELD', JText::translate('VAPMANAGECUSTOMER5')));

				return false;
			}

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true);

			if ($this->state_2_code)
			{
				$where[] = $dbo->qn('state_2_code') . ' = ' . $dbo->q($this->state_2_code);
			}

			if ($this->state_3_code)
			{
				$where[] = $dbo->qn('state_3_code') . ' = ' . $dbo->q($this->state_3_code);
			}

			$q->select(1);
			$q->from($dbo->qn($this->getTableName()));
			$q->where($dbo->qn('id') . ' <> ' . (int) $this->id);
			$q->where($dbo->qn('id_country') . ' = ' . (int) $this->id_country);
			$q->andWhere($where, 'OR');
			
			$dbo->setQuery($q, 0, 1);
			$dbo->execute();
			
			if ($dbo->getNumRows() > 0)
			{
				// a state already exists with the given code(s)
				$this->setError(JText::translate('VAPSTATEUNIQUEERROR'));

				return false;
			}
		}

		return true;
	}
}
