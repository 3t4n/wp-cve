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
 * Implement the wizard step used to assign the locations to the
 * related working days.
 *
 * @since 1.7.1
 */
class VAPWizardStepLocwdays extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAP_WIZARD_STEP_LOCWDAYS');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_LOCWDAYS_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-calendar-check"></i>';
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
	 * Checks whether the step has been completed.
	 *
	 * @return 	boolean  True if completed, false otherwise.
	 */
	public function isCompleted()
	{
		// check whether at least an assignment was created
		return (bool) $this->getAssignments();
	}

	/**
	 * Returns the button used to process the step.
	 *
	 * @return 	string  The HTML of the button.
	 */
	public function getExecuteButton()
	{
		// load employees step from dependencies
		$step = $this->getDependency('employees');

		if (!$step)
		{
			return '';
		}

		// fetch the available employees
		$employees = $step->getEmployees();

		// point to the view used to assign the locations to the working days
		return '<a href="index.php?option=com_vikappointments&view=locations&id_employee=' . $employees[0]->id . '" class="btn btn-success">' . JText::translate('VAPEDIT') . '</a>';
	}

	/**
	 * Checks whether the specified step can be skipped.
	 * By default, all the steps are mandatory.
	 * 
	 * @return 	boolean  True if skippable, false otherwise.
	 */
	public function canIgnore()
	{
		return true;
	}

	/**
	 * Returns a list of created assignments.
	 *
	 * @return 	array  A list of assignments.
	 */
	public function getAssignments()
	{
		static $assoc = null;

		// get assigments only once
		if (is_null($assoc))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('w.*')
				->select($dbo->qn('l.name'))
				->from($dbo->qn('#__vikappointments_emp_worktime', 'w'))
				->leftjoin($dbo->qn('#__vikappointments_employee_location', 'l') . ' ON ' . $dbo->qn('w.id_location') . ' = ' . $dbo->qn('l.id'))
				->where($dbo->qn('l.id') . ' > 0')
				->where($dbo->qn('w.id_service') . ' <= 0')
				->order($dbo->qn('w.id') . ' ASC');

			$dbo->setQuery($q);
			$assoc = $dbo->loadObjectList();
		}

		return $assoc;
	}
}
