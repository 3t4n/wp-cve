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
 * Implement the wizard step used to setup the taxes.
 *
 * @since 1.7.1
 */
class VAPWizardStepTaxes extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENUTAXES');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return JText::translate('VAP_WIZARD_STEP_TAXES_DESC');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-calculator"></i>';
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
		// auto-complete in case of already created taxes
		return (bool) $this->getTaxes();
	}

	/**
	 * Implements the step execution.
	 *
	 * @param 	JRegistry  $data  The request data.
	 *
	 * @return 	boolean
	 */
	protected function doExecute($data)
	{
		$taxModel = JModelVAP::getInstance('tax');

		// recover the specified percentage
		$amount = abs((float) $data->get('amount'));

		// register default taxes
		$id_tax = $taxModel->save(array(
			'name' => sprintf('VAT %s%%', $amount),
		));

		if (!$id_tax)
		{
			// get latest registered error
			$error = $taxModel->getError($index = null, $string = true);

			// throw exception
			throw new Exception($error ? $error : JText::translate('VAP_AJAX_GENERIC_ERROR'), 500);
		}

		$taxRuleModel = JModelVAP::getInstance('taxrule');

		// register tax rule
		$taxRuleModel->save(array(
			'id_tax'   => $id_tax,
			'name'     => 'VAT',
			'operator' => $data->get('type'),
			'amount'   => $amount,
			'apply'    => 1,
		));

		// register the created tax as default one
		VAPFactory::getConfig()->set('deftax', $id_tax);

		return true;
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
	 * Returns a list of created taxes.
	 *
	 * @return 	array  A list of taxes.
	 */
	public function getTaxes()
	{
		static $taxes = null;

		// get taxes only once
		if (is_null($taxes))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'name')))
				->from($dbo->qn('#__vikappointments_tax'))
				->order($dbo->qn('id') . ' ASC');

			$dbo->setQuery($q);
			$taxes = $dbo->loadObjectList();
		}

		return $taxes;
	}
}
