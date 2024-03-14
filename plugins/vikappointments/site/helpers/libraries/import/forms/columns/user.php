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
 * Populate the options array with the existing CMS users,
 * in order to support the correct placeholders while
 * importing and exporting the records.
 *
 * @since 1.7
 */
class ImportColumnUser extends ImportColumn
{
	/**
	 * Users cache.
	 *
	 * @var array
	 */
	private static $users = null;

	/**
	 * Binds the internal properties with the given array/object.
	 *
	 * @param 	mixed  $data  Either an array or an object.
	 *
	 * @return 	void
	 */
	protected function setup($data)
	{
		// use parent to set up data
		parent::setup($data);

		foreach (static::getUsers() as $user)
		{
			// register user as option
			$this->options[$user->id] = $user->username;
		}
	}

	/**
	 * Loads a list of available users.
	 *
	 * @return 	array
	 */
	protected static function getUsers()
	{
		// load users only once
		if (is_null(static::$users))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'username')))
				->from($dbo->qn('#__users'));

			$dbo->setQuery($q);
			
			// cache users found
			static::$users = $dbo->loadObjectList();
		}

		return static::$users;
	}
}
