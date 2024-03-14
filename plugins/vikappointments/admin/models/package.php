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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments package model.
 *
 * @since 1.7
 */
class VikAppointmentsModelPackage extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		// attempt to save the relation
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		if (isset($data['services']))
		{
			// get package-service model
			$model = JModelVAP::getInstance('packageservice');
			// define relations
			$model->setRelation($id, $data['services']);
		}
		
		return $id;
	}

	/**
	 * Extend duplicate implementation to clone any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids     Either the record ID or a list of records.
	 * @param 	mixed    $src     Specifies some values to be used while duplicating.
	 * @param 	array    $ignore  A list of columns to skip.
	 *
	 * @return 	mixed    The ID of the records on success, false otherwise.
	 */
	public function duplicate($ids, $src = array(), $ignore = array())
	{
		$new_ids = array();

		// do not copy ordering
		$ignore[] = 'ordering';

		$dbo = JFactory::getDbo();

		// get package translation model
		$langModel = JModelVAP::getInstance('langpackage');

		// check whether we should use the services provided by the caller
		$has_services = isset($src['services']);

		if (!isset($src['published']))
		{
			// if not specified, auto-unpublished cloned packages
			$src['published'] = 0;
		}

		foreach ($ids as $id_package)
		{
			if (!$has_services)
			{
				// load any assigned services
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id_service'))
					->from($dbo->qn('#__vikappointments_package_service'))
					->where($dbo->qn('id_package') . ' = ' . (int) $id_package);

				$dbo->setQuery($q);
				
				// include assigned services to duplicate them too
				$src['services'] = $dbo->loadColumn();
			}

			// start by duplicating the whole record
			$new_id = parent::duplicate($id_package, $src, $ignore);

			if ($new_id)
			{
				$new_id = array_shift($new_id);

				// register copied
				$new_ids[] = $new_id;
			
				// load any assigned translation
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id'))
					->from($dbo->qn('#__vikappointments_lang_package'))
					->where($dbo->qn('id_package') . ' = ' . (int) $id_package);

				$dbo->setQuery($q);

				if ($duplicate = $dbo->loadColumn())
				{
					$lang_data = array();
					$lang_data['id_package'] = $new_id;

					// duplicate languages by using the new package ID
					$langModel->duplicate($duplicate, $lang_data);
				}
			}
		}

		return $new_ids;
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any assigned translation
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_lang_package'))
			->where($dbo->qn('id_package') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($lang_ids = $dbo->loadColumn())
		{
			// get translation model
			$model = JModelVAP::getInstance('langpackage');
			// delete assigned translations
			$model->delete($lang_ids);
		}

		// DO NOT remove package-service relations.
		// The associations are required for the orders already stored, otherwise the
		// customers wouldn't be able to select the proper services in the front-end.

		return true;
	}

	/**
	 * Returns all the services supported by the given package.
	 *
	 * @param 	integer  $id      The package ID.
	 * @param 	boolean  $strict  True to validate the user capabilities.
	 *                            When empty, it will depend on the current
	 *                            application client.
	 *
	 * @return 	mixed    An array of services (when empty, all the array are supported).
	 *                   False will be returned in case no services are supported.
	 */
	public function getServices($id, $strict = null)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('id_service'))
			->from($dbo->qn('#__vikappointments_package_service'))
			->where($dbo->qn('id_package') . ' = ' . (int) $id);

		$dbo->setQuery($q);
		$services = $dbo->loadColumn();

		if (!$services)
		{
			// all the services are supported
			return array();
		}

		if (is_null($strict))
		{
			// lean on the current application client
			$strict = JFactory::getApplication()->isClient('site');
		}

		/**
		 * Retrieve only the services that belong to the view
		 * access level of the current user.
		 *
		 * @since 1.6
		 */
		$levels = JFactory::getUser()->getAuthorisedViewLevels();

		$dispatcher = VAPFactory::getEventDispatcher();

		// get service model
		$model = JModelVAP::getInstance('service');
		// cache services details
		static $lookup = array();

		$now = JFactory::getDate()->toSql();

		$list = array();

		// iterate services
		foreach ($services as $id_service)
		{
			// look for a cached service first
			if (!array_key_exists($id_service, $lookup))
			{
				// load service details only once
				$lookup[$id_service] = $model->getItem($id_service);
			}

			if (!$lookup[$id_service])
			{
				// service not found...
				continue;
			}

			$service = $lookup[$id_service];

			// make sure the access levels of the current user match
			if (($levels && !in_array($service->level, $levels)) || !$service->published)
			{
				// the current user doesn't own enough capabilities
				continue;
			}

			// validate end publishing of the service
			if (!VAPDateHelper::isNull($service->end_publishing) && $service->end_publishing < $now)
			{
				// the service is expired
				continue;
			}

			/**
			 * This event can be used to apply additional conditions while checking whether a
			 * service can be redeemed by the specified package. When this event is triggered,
			 * the system already validated the standard conditions and the service is going
			 * to be included within the list of available services.
			 *
			 * @param 	integer  $id_package  The package identifier.
			 * @param 	object 	 $service     The service to check.
			 *
			 * @return 	boolean  Return false to flag the service as NOT available.
			 *
			 * @since 	1.7
			 */
			if ($dispatcher->false('onCheckServicePackageAvailability', array($id, $service)))
			{
				// the service is not available for this package
				continue;
			}

			// register service details
			$list[] = $service;
		}

		if (!$list)
		{
			// no supported services
			return false;
		}

		// sort services by using their ordering
		usort($list, function($a, $b)
		{
			return $a->ordering - $b->ordering;
		});

		return $list;
	}
}
