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
 * Populate the options array with the existing option groups,
 * in order to support the correct placeholders while
 * importing and exporting the records.
 *
 * @since 1.7.3
 */
class ImportColumnOptiongroup extends ImportColumn
{
	/**
	 * Groups cache.
	 *
	 * @var array
	 */
	private static $groups = null;

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

		foreach (static::getGroups() as $group)
		{
			// register group as option
			$this->options[$group->value] = $group->text;
		}
	}

	/**
	 * Loads a list of available groups.
	 *
	 * @return 	array
	 */
	protected static function getGroups()
	{
		// load groups only once
		if (is_null(static::$groups))
		{
			static::$groups = JHtml::fetch('vaphtml.admin.optiongroups', $blank = false);
		}

		return static::$groups;
	}
}
