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
 * Before the 1.7 version, the settings of the invoices were stored in 2 different values of the config database table:
 * - "pdfparams", for the invoice details (such as the invoice number and the legal info);
 * - "pdfconstraints", for the layout details (such as the orientation and the page format).
 * 
 * From now on, both the settings are merged into a single value called "invoiceobj".
 *
 * @since 1.7
 */
class VAPUpdateRuleInvoiceSettingsAdapter1_7 extends VAPUpdateRule
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
		$this->adapt();

		return true;
	}

	/**
	 * Adapts the JSON settings of the invoices.
	 *
	 * @return 	void
	 */
	private function adapt()
	{
		$config = VAPFactory::getConfig();

		// get PDF parameters
		$params = new JRegistry($config->getArray('pdfparams', array()));
		// get PDF constraints
		$constraints = $config->getArray('pdfconstraints', array());

		$obj = [
			"params" => [
				"number"      => (int) $params->get('invoicenumber', 1),
				"suffix"      => $params->get('invoicesuffix', ''),
				"datetype"    => (int) $params->get('datetype', 1),
				"legalinfo"   => $params->get('legalinfo'),
				"sendinvoice" => (bool) $params->get('sendinvoice'),
				"date"        => null,
			],
			"constraints" => $constraints,
		];

		// commit the changes
		$config->set('invoiceobj', $obj);
	}
}
