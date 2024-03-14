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
 * VikAppointments employee area working days management model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpeditwdays extends JModelVAP
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
		$auth = VAPEmployeeAuth::getInstance();

		// set user state for being recovered again
		JFactory::getApplication()->setUserState('vap.emparea.worktime.data', $data);

		// extend fields validation
		$required = array();

		if ($data['type'] == 1)
		{
			$required['day'] = JText::translate('VAPMANAGEWD3');
		}
		else
		{
			$required['date'] = JText::translate('VAPMANAGEWD3');
		}

		foreach ($required as $key => $fieldName)
		{
			if (!isset($data[$key]) || strlen($data[$key]) == 0)
			{
				// register error message
				$this->setError(JText::sprintf('VAP_MISSING_REQ_FIELD', $fieldName));

				return false;
			}
		}

		// validation
		if ($data['day'] < 0 || $data['day'] > 6)
		{
			$data['day'] = 0;
		}

		if ($data['fromts'] >= $data['endts'] || $data['fromts'] < 0 || $data['endts'] > 1440 || $data['fromts'] % 5 || $data['endts'] % 5)
		{
			// invalid times, use default ones
			$data['fromts'] = 540;
			$data['endts']  = 720;
		}

		// force employee ID
		$data['id_employee'] = $auth->id;

		if (!empty($data['id_service']))
		{
			// always unset parent, because we don't want to
			// keep a relation with the original working day
			$data['parent'] = -1;
		}

		$model = JModelVAP::getInstance('worktime');

		// delegate save to worktime model
		$result = $model->save($data);

		if (!$result)
		{
			// obtain error from model
			$error = $model->getError();

			if ($error)
			{
				// propagate error
				$this->setError($error);
			}

			return false;
		}

		$saved = $model->getData();

		if (!VAPDateHelper::isNull($saved['tsdate']) && !VAPDateHelper::isNull($data['date_to']))
		{
			// create date seek
			$seek = JFactory::getDate($saved['tsdate']);

			// convert the ending date to a standard format
			$ts  = VikAppointments::createTimestamp($data['date_to'], 0, 0);
			$end = JFactory::getDate(date('Y-m-d', $ts))->format('Y-m-d');

			// iterate until with reach the ending date
			while ($seek->format('Y-m-d') < $end)
			{
				// increase by one day
				$seek->modify('+1 day');

				// update bind data
				$data['id']   = 0;
				$data['date'] = $seek->format('Y-m-d');

				// invoke model to save the working day
				$model->save($data);
			}
		}

		return $result;
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
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee() || !$auth->manageWorkDays())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		$new_ids = array();

		// delegate duplicate to worktime model
		$model = JModelVAP::getInstance('worktime');

		foreach ($ids as $id_worktime)
		{
			// load details of working day
			$item = $model->getItem(['id' => $id_worktime, 'id_employee' => $auth->id]);

			if (!$item)
			{
				// record not found
				continue;
			}

			$bindSrc = $src;

			if ($item->ts == -1)
			{
				// go to next week day
				$bindSrc['day'] = ($item->day + 1) % 7;
			}
			else
			{
				// go to next day of the year
				$dt = JFactory::getDate($item->tsdate);
				$dt->modify('+1 day');

				$bindSrc['ts'] = $dt->getTimestamp();
			}

			// duplicate record
			$new_id = $model->duplicate($ids, $bindSrc, $ignore);

			if ($new_id)
			{
				$new_ids[] = $new_id;
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
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// get rid of those records that do not belong to this employee
		$ids = array_values(array_filter($ids, function($id) use ($auth)
		{
			// make sure the employee can manage this record
			return $auth->manageWorkDays($id);
		}));

		if (!$ids)
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// delegate delete to worktime model
		return JModelVAP::getInstance('worktime')->delete($ids);
	}

	/**
	 * Restores the working days for the given service and
	 * employee relation.
	 *
	 * @param 	integer  $id_service  The service ID.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function restore($id_service)
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->manageWorkDays() || !$auth->manageServices($id_service, $readOnly = true))
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// delegate restore to worktime model
		return JModelVAP::getInstance('worktime')->restore($id_service, $auth->id);
	}
}
