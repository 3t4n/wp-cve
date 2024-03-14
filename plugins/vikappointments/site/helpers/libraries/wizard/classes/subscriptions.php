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
 * Implement the wizard step used to configure
 * the subscriptions for the customers.
 *
 * @since 1.7.1
 */
class VAPWizardStepSubscriptions extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENUSUBSCRIPTIONS');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_SUBSCR_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-ticket-alt"></i>';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	public function getGroup()
	{
		// belongs to SUBSCRIPTIONS group
		return JText::translate('VAPMENUSUBSCRIPTIONS');
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
	 * Checks whether the step has been ignored.
	 *
	 * @return 	boolean  True if ignored, false otherwise.
	 */
	public function isIgnored()
	{
		// get system subscriptions dependency
		$system = $this->getDependency('syssubscr');

		// make sure the subscriptions section is enabled
		if ($system && $system->isCompleted() && $system->isEnabled() === false)
		{
			// subscriptions disabled, auto-ignore this step
			return true;
		}

		// otherwise lean on parent method
		return parent::isIgnored();
	}

	/**
	 * Checks whether the step has been completed.
	 *
	 * @return 	boolean  True if completed, false otherwise.
	 */
	public function isCompleted()
	{
		// check whether at least a subscription has been created
		return (bool) $this->getSubscriptions();
	}

	/**
	 * Returns the button used to process the step.
	 *
	 * @return 	string  The HTML of the button.
	 */
	public function getExecuteButton()
	{
		// point to the (customers) subscriptions view
		return '<a href="index.php?option=com_vikappointments&view=subscriptions&group=0" class="btn btn-success">' . JText::translate('VAPNEW') . '</a>';
	}

	/**
	 * Returns a list of created subscriptions.
	 *
	 * @return 	array  A list of subscriptions.
	 */
	public function getSubscriptions()
	{
		static $subscr = null;

		// get subscriptions only once
		if (is_null($subscr))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'name')))
				->from($dbo->qn('#__vikappointments_subscription'))
				->where($dbo->qn('group') . ' = 0')
				->where($dbo->qn('published') . ' = 1')
				->order($dbo->qn('id') . ' ASC');

			$dbo->setQuery($q);
			$subscr = $dbo->loadObjectList();
		}

		return $subscr;
	}
}
