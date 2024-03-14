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
 * options and the relations with the services.
 *
 * @since 1.7.1
 */
class VAPWizardStepOptions extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENUOPTIONS');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_OPTIONS_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-tags"></i>';
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

		// get list of created options
		$options = $this->getExtraOptions();

		if ($options)
		{
			// option created, increase progress
			$progress = 50;

			// search for an option with assigned services
			$options = array_filter($options, function($s)
			{
				return $s->id_service;
			});

			if ($options)
			{
				// assigned to services, increase progress
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
		// get options list
		if ($this->getOptions())
		{
			// load services step from dependencies
			$step = $this->getDependency('services');

			if (!$services)
			{
				return '';
			}

			// fetch available services
			$services = $step->getServices();

			// edit the first service of the list and auto-focus the assignments list
			return '<a href="index.php?option=com_vikappointments&task=service.edit&cid[]=' . $services[0]->id . '#service_assoc" class="btn btn-success">' . JText::translate('VAPEDIT') . '</a>';
		}

		// point to the controller for creating a new option
		return '<a href="index.php?option=com_vikappointments&task=option.add" class="btn btn-success">' . JText::translate('VAPNEW') . '</a>';
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
	 * Returns a list of created options.
	 *
	 * @return 	array  A list of options.
	 */
	public function getExtraOptions()
	{
		static $options = null;

		// get options only once
		if (is_null($options))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('o.id', 'o.name')))
				->select($dbo->qn('a.id_service'))
				->from($dbo->qn('#__vikappointments_option', 'o'))
				->leftjoin($dbo->qn('#__vikappointments_ser_opt_assoc', 'a') . ' ON ' . $dbo->qn('a.id_option') . ' = ' . $dbo->qn('o.id'))
				->group($dbo->qn('o.id'))
				->order($dbo->qn('o.id') . ' ASC');

			$dbo->setQuery($q);
			$options = $dbo->loadObjectList();
		}

		return $options;
	}
}
