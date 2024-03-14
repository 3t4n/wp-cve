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
 * Implement the wizard step used to configure the
 * services and the relations with the employees.
 *
 * @since 1.7.1
 */
class VAPWizardStepServices extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENUSERVICES');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_SERVICES_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-flask"></i>';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	public function getGroup()
	{
		// belongs to APPOINTMENTS group
		return JText::translate('VAPMENUTITLEHEADER2');
	}

	/**
	 * Returns the completion progress in percentage.
	 *
	 * @return 	integer  The percentage progress (always rounded).
	 */
	public function getProgress()
	{
		$progress = 0;

		// get list of created services
		$services = $this->getServices();

		if ($services)
		{
			// service created, increase progress
			$progress = 50;

			// search for a service with assigned employees
			$services = array_filter($services, function($s)
			{
				return $s->id_employee;
			});

			if ($services)
			{
				// assigned to employees, increase progress
				$progress = 100;
			}
		}

		return $progress;
	}

	/**
	 * Checks whether the step has been completed.
	 *
	 * @return 	boolean  True if completed, false otherwise.
	 */
	public function isCompleted()
	{
		// look for 100% completion progress
		return $this->getProgress() == 100;
	}

	/**
	 * Returns the button used to process the step.
	 *
	 * @return 	string  The HTML of the button.
	 */
	public function getExecuteButton()
	{
		// get services list
		$services = $this->getServices();

		$count = count($services);

		if ($count == 1)
		{
			// point to the controller for editing an existing service
			return '<a href="index.php?option=com_vikappointments&task=service.edit&cid[]=' . $services[0]->id . '" class="btn btn-success">' . JText::translate('VAPEDIT') . '</a>';
		}

		if ($count > 1)
		{
			// reach the services list to start pick a record to edit
			return '<a href="index.php?option=com_vikappointments&view=services" class="btn btn-success">' . JText::translate('VAPEDIT') . '</a>';
		}

		// point to the controller for creating a new service
		return '<a href="index.php?option=com_vikappointments&task=service.add" class="btn btn-success">' . JText::translate('VAPNEW') . '</a>';
	}

	/**
	 * Returns a list of created services.
	 *
	 * @return 	array  A list of services.
	 */
	public function getServices()
	{
		static $services = null;

		// get services only once
		if (is_null($services))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('s.id', 's.name')))
				->select($dbo->qn('a.id_employee'))
				->from($dbo->qn('#__vikappointments_service', 's'))
				->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('a.id_service') . ' = ' . $dbo->qn('s.id'))
				->group($dbo->qn('s.id'))
				->order($dbo->qn('s.id') . ' ASC');

			$dbo->setQuery($q);
			$services = $dbo->loadObjectList();
		}

		return $services;
	}
}
