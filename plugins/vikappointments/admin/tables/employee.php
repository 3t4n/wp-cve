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
 * VikAppointments employee table.
 *
 * @since 1.7
 */
class VAPTableEmployee extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_employee', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'firstname';
		$this->_requiredFields[] = 'lastname';
		$this->_requiredFields[] = 'nickname';
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

		if (empty($src['id']))
		{
			if (empty($src['synckey']))
			{
				// generate sync key
				$src['synckey'] = VikAppointments::generateSerialCode(12, 'employee-synckey');
			}

			$src['id'] = 0;
		}

		// generate alias in case it is empty when creating or updating
		if (empty($src['alias']) && (empty($src['id']) || isset($src['alias'])))
		{
			// generate unique alias starting from nominative
			$src['alias'] = $src['nickname'];
		}
		
		// check if we are going to update an empty alias
		if (isset($src['alias']) && strlen($src['alias']) == 0)
		{
			// avoid to update an empty alias by using a unique ID
			$src['alias'] = uniqid();
		}

		if (!empty($src['alias']))
		{
			VAPLoader::import('libraries.sef.helper');
			// make sure the alias is unique
			$src['alias'] = VAPSefHelper::getUniqueAlias($src['alias'], 'employee', $src['id']);
		}

		if (isset($src['active_to']) && $src['active_to'] != 1)
		{
			// use null date in case the activation is set to LIFETIME or PENDING
			$src['active_to_date'] = JFactory::getDbo()->getNullDate();
		}

		if (isset($src['synckey']) && !$src['synckey'])
		{
			// avoid updating an empty key
			unset($src['synckey']);
		}

		// iterate custom fields
		foreach ($src as $k => $v)
		{
			// make sure the property starts with "field_" and it is not scalar
			if (preg_match("/^field_/", $k) && $v && !is_scalar($v))
			{
				// JSON encode the property value for correct saving
				$src[$k] = json_encode($v);
			}
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
