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
 * VikAppointments employee area locations-working days assignment model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmplocwdays extends JModelVAP
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

		// extract locations lookup
		$locations = isset($data['locations']) ? (array) $data['locations'] : array();

		// get working times model
		$model = JModelVAP::getInstance('worktime');

		$aclLookup = array();

		$result = false;

		foreach ($locations as $id_worktime => $id_location)
		{
			if (!isset($aclLookup[$id_location]))
			{
				// check whether the location can be used by the employee
				$aclLookup[$id_location] = $auth->manageLocations($id_location, $readOnly = true);
			}

			if (!$aclLookup[$id_location])
			{
				// cannot use the specified location
				$this->setError(JText::translate('JERROR_ALERTNOAUTHOR'));
				continue;
			}

			// also make sure that the employee is the owner of the working day
			if (!$auth->manageWorkDays($id_worktime))
			{
				// not the owner
				$this->setError(JText::translate('JERROR_ALERTNOAUTHOR'));
				continue;
			}

			// create relation array
			$assoc = array(
				'id'          => (int) $id_worktime,
				'id_location' => (int) $id_location,
				// the employee ID is needed to trigger the update for
				// all the children working days
				'id_employee' => $auth->id,
			);

			// delegate model to perform saving process
			if ($model->save($assoc))
			{
				$result = true;
			}
			else
			{
				if ($error = $model->getError())
				{
					// propagate registered error message
					$this->setError($error);
				}
			}
		}

		return $result;
	}
}
