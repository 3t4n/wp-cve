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
 * VikAppointments custom field table.
 *
 * @since 1.7
 */
class VAPTableCustomf extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_custfields', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'group';
		$this->_requiredFields[] = 'name';
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

		if (!$src)
		{
			// table manually binded, do not go ahead
			return true;
		}

		// fetch ordering for new custom fields
		if ($src['id'] == 0)
		{
			$src['ordering'] = $this->getNextOrder('`group` = ' . (int) @$src['group']);
		}

		if (isset($src['formname']))
		{
			if (!$src['formname'])
			{
				// form name not provided, take it from the field name
				$src['formname'] = !empty($src['name']) ? $src['name'] : '';
			}

			// strip all the unsupported characters
			$src['formname'] = strtolower(preg_replace("/\b[\d]+|[^a-zA-Z0-9_]+/", '_', $src['formname']));
			// get rid off duplicate underscores
			$src['formname'] = preg_replace("/_{2,}/", '_', $src['formname']);
			// make sure the length of the form name doesn't exceed the limit
			$src['formname'] = substr($src['formname'], 0, 32);

			if (isset($src['group']) && $src['group'] != 1)
			{
				// unset form name for "customers" group
				$src['formname'] = null;
			}
		}

		if (isset($src['choose']) && !is_string($src['choose']))
		{
			// stringify configuration
			$src['choose'] = json_encode($src['choose']);
		}

		if (!empty($src['type']) && $src['type'] == 'separator')
		{
			// do not use rule for separator
			$src['rule'] = 0;
			// separators are always optional
			$src['required'] = 0;

			if (!empty($src['choose']))
			{
				// make class suffix safe
				$src['choose'] = preg_replace("/[^a-zA-Z0-9_\-\s]+/", '', $src['choose']);
			}
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

		// validate form name only in case of custom field for the employees
		if ($this->group == 1 && empty($this->formname) && empty($this->id))
		{
			// the form name is mandatory
			$this->setError(JText::sprintf('VAP_INVALID_REQ_FIELD', JText::translate('VAPMANAGECUSTOMF13')));
			return false;
		}

		return true;
	}
}
