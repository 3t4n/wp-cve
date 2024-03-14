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
 * VikAppointments status code table.
 *
 * @since 1.7
 */
class VAPTableStatuscode extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_status_code', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'name';
		$this->_requiredFields[] = 'code';
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
			$src['ordering'] = $this->getNextOrder();

			if (empty($src['color']))
			{
				// use empty color
				$src['color'] = '';
			}
		}

		if (isset($src['code']))
		{
			// accept only upper-case letters and numbers
			$src['code'] = preg_replace("/[^A-Z0-9]+/", '', strtoupper($src['code']));
		}

		// validate color, if specified
		if (isset($src['color']) && !preg_match("/^#?[0-9a-f]{6,8}$/i", $src['color']))
		{
			// invalid color
			$src['color'] = '';
		}
		else if (isset($src['color']))
		{
			// always trim initial "#"
			$src['color'] = ltrim($src['color'], '#');
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

		// in case of code, make sure we are not going to create duplicate
		if (!empty($this->code))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true);

			$q->select(1);
			$q->from($dbo->qn($this->getTableName()));
			$q->where($dbo->qn('id') . ' <> ' . (int) $this->id);
			$q->where($dbo->qn('code') . ' = ' . $dbo->q($this->code));
			
			$dbo->setQuery($q, 0, 1);
			$dbo->execute();
			
			if ($dbo->getNumRows())
			{
				// a state already exists with the given code(s)
				$this->setError(JText::translate('VAPSTATUSCODEUNIQUEERR'));

				return false;
			}
		}

		return true;
	}
}
