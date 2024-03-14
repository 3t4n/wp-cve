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
 * Before the 1.7 version, the tax ratio was specified within the configuration of the invoices.
 * In order to keep the same behavior, we should auto-create a new tax rule by using the same amount
 * specified within the pdfparams configuration setting. We should use the VAT type of rule because
 * the taxes were always inclusive.
 *
 * @since 1.7
 */
class VAPUpdateRuleDefineDefaultTax1_7 extends VAPUpdateRule
{
	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	protected function run($parent)
	{
		return $this->create();
	}

	/**
	 * Defines a default tax rule.
	 *
	 * @return 	void
	 */
	private function create()
	{
		$config = VAPFactory::getConfig();

		// load the setting storing the invoices tax
		$params = $config->getArray('pdfparams', array());

		if (empty($params['taxes']))
		{
			// taxes never defined, do not need to create a rule
			return true;
		}

		$dbo = JFactory::getDbo();

		// since this job might fail, we need to check whether the
		// record has been manually created
		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_tax'));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			// tax rule manually created, do not need to go ahead
			return true;
		}

		$taxModel = JModelVAP::getInstance('tax');

		// register default taxes
		$id_tax = $taxModel->save(array(
			'name'        => sprintf('VAT %s%%', $params['taxes']),
			'description' => 'Automatically created while updating to the latest version',
		));

		if (!$id_tax)
		{
			// get latest registered error
			$error = $taxModel->getError($index = null, $string = true);

			// register error message
			JFactory::getApplication()->enqueueMessage($error ? $error : 'Unable to define the tax rule. Create it manually from <a href="index.php?option=com_vikappointments&task=tax.add">HERE</a>.');

			return false;
		}

		$taxRuleModel = JModelVAP::getInstance('taxrule');

		// register tax rule
		$taxRuleModel->save(array(
			'id_tax'   => $id_tax,
			'name'     => 'VAT',
			'operator' => 'vat',
			'amount'   => abs((float) $params['taxes']),
			'apply'    => 1,
		));

		// register created tax as default one
		$config->set('deftax', $id_tax);

		return true;
	}
}
