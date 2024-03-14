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
 * employees and the working days.
 *
 * @since 1.7.1
 */
class VAPWizardStepEmployees extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENUEMPLOYEES');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_EMPLOYEES_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-user-tie"></i>';
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

		// get list of created employees
		$employees = $this->getEmployees();

		if ($employees)
		{
			// employee created, increase progress
			$progress = 50;

			// search for an employee with assigned working times
			$employees = array_filter($employees, function($e)
			{
				return $e->id_worktime;
			});

			if ($employees)
			{
				// working times created, increase progress
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
		// get employees list
		$employees = $this->getEmployees();

		$count = count($employees);

		if ($count == 1)
		{
			// point to the controller for editing an existing employee
			return '<a href="index.php?option=com_vikappointments&task=employee.edit&cid[]=' . $employees[0]->id . '" class="btn btn-success">' . JText::translate('VAPEDIT') . '</a>';
		}

		if ($count > 1)
		{
			// reach the employees list to start pick a record to edit
			return '<a href="index.php?option=com_vikappointments&view=employees" class="btn btn-success">' . JText::translate('VAPEDIT') . '</a>';
		}

		// point to the controller for creating a new employee
		return '<a href="index.php?option=com_vikappointments&task=employee.add" class="btn btn-success">' . JText::translate('VAPNEW') . '</a>';
	}

	/**
	 * Returns a list of created employees.
	 *
	 * @return 	array  A list of employees.
	 */
	public function getEmployees()
	{
		static $employees = null;

		// get employees only once
		if (is_null($employees))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('e.id', 'e.nickname')))
				->select($dbo->qn('w.id', 'id_worktime'))
				->from($dbo->qn('#__vikappointments_employee', 'e'))
				->leftjoin($dbo->qn('#__vikappointments_emp_worktime', 'w') . ' ON ' . $dbo->qn('w.id_employee') . ' = ' . $dbo->qn('e.id'))
				->group($dbo->qn('e.id'))
				->order($dbo->qn('e.id') . ' ASC');

			$dbo->setQuery($q);
			$employees = $dbo->loadObjectList();
		}

		return $employees;
	}
}
