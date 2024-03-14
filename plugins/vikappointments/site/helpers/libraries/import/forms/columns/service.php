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
 * Populate the options array with the existing services,
 * in order to support the correct placeholders while
 * importing and exporting the records.
 *
 * @since 1.7
 */
class ImportColumnService extends ImportColumn
{
	/**
	 * Services cache.
	 *
	 * @var array
	 */
	private static $services = null;

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
		
		foreach (static::getServices() as $service)
		{
			// register service as option
			$this->options[$service->id] = $service->name;
		}
	}

	/**
	 * Loads a list of available services.
	 *
	 * @return 	array
	 */
	protected static function getServices()
	{
		// load services only once
		if (is_null(static::$services))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'name')))
				->from($dbo->qn('#__vikappointments_service'))
				->order($dbo->qn('ordering') . ' ASC');

			$dbo->setQuery($q);
			
			// cache services found
			static::$services = $dbo->loadObjectList();
		}

		return static::$services;
	}
}
