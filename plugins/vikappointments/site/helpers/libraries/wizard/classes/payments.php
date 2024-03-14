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
 * payment gateways.
 *
 * @since 1.7.1
 */
class VAPWizardStepPayments extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENUPAYMENTS');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_PAYMENTS_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-credit-card"></i>';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	public function getGroup()
	{
		// belongs to GLOBAL group
		return JText::translate('VAPMENUTITLEHEADER3');
	}

	/**
	 * Checks whether the step has been completed.
	 *
	 * @return 	boolean  True if completed, false otherwise.
	 */
	public function isCompleted()
	{
		// the step is completed after publishing at least a payment
		foreach ($this->getPayments() as $payment)
		{
			if ($payment->published)
			{
				// payment published
				return true;
			}
		}

		// no published payments
		return false;
	}

	/**
	 * Returns the button used to process the step.
	 *
	 * @return 	string  The HTML of the button.
	 */
	public function getExecuteButton()
	{
		// get payments list
		$payments = $this->getPayments();

		if ($payments)
		{
			// point to the controller for editing an existing payment
			return '<a href="index.php?option=com_vikappointments&view=payments" class="btn btn-success">' . JText::translate('VAPEDIT') . '</a>';
		}

		// point to the controller for creating a new payment
		return '<a href="index.php?option=com_vikappointments&task=payment.add" class="btn btn-success">' . JText::translate('VAPNEW') . '</a>';
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
	 * Returns a list of created payments.
	 *
	 * @return 	array  A list of payments.
	 */
	public function getPayments()
	{
		static $payments = null;

		// get payments only once
		if (is_null($payments))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'name', 'file', 'published')))
				->from($dbo->qn('#__vikappointments_gpayments'))
				->order($dbo->qn('ordering') . ' ASC');

			$dbo->setQuery($q);
			$payments = $dbo->loadObjectList();
		}

		return $payments;
	}
}
