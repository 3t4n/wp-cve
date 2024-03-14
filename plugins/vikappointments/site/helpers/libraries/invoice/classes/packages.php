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

VAPLoader::import('libraries.invoice.classes.appointments');

/**
 * Class used to generate the invoices of the packages.
 * Since the invoices of the packages are almost equals to
 * the invoices of the appointments, this class will extend
 * the methods declared by VAPInvoiceAppointments.
 *
 * @since 	1.6
 */
class VAPInvoicePackages extends VAPInvoiceAppointments
{
	/**
	 * @override
	 * Returns the destination absolute path of the invoices folder.
	 *
	 * @return 	string 	The invoice folder path.
	 *
	 * @since 	1.7
	 */
	public function getInvoiceFolderPath()
	{
		return parent::getInvoiceFolderPath() . DIRECTORY_SEPARATOR . 'packages';
	}

	/**
	 * @override
	 * Returns the destination URI of the invoices folder.
	 *
	 * @return 	string 	The invoice folder URI.
	 *
	 * @since 	1.7
	 */
	public function getInvoiceFolderURI()
	{
		return parent::getInvoiceFolderURI() . 'packages/';
	}

	/**
	 * @override
	 * Returns the page template that will be used to 
	 * generate the invoice.
	 *
	 * @return 	string 	The base HTML.
	 */
	protected function getPageTemplate()
	{
		$data = array(
			'order'     => $this->order,
			'breakdown' => $this->getBreakdown(),
			'usetaxbd'  => VAPFactory::getConfig()->getBool('usetaxbd', false),
		);

		// create layout file (always read from the site section of VikAppointments)
		$layout = new JLayoutFile('templates.invoice.package', null, [
			'component' => 'com_vikappointments',
			'client'    => 'site',
		]);

		return $layout->render($data);
	}

	/**
	 * Extracts an overall breakdown from the order.
	 *
	 * @return 	array
	 *
	 * @since 	1.7
	 */
	protected function getBreakdown()
	{
		$arr = array();

		// group all breakdowns at the same level
		foreach ($this->order->packages as $item)
		{
			if ($item->totals->breakdown)
			{
				$arr = array_merge($arr, $item->totals->breakdown);
			}
		}

		$breakdown = array();

		// iterate breakdowns
		foreach ($arr as $bd)
		{
			if (!isset($breakdown[$bd->name]))
			{
				$breakdown[$bd->name] = 0;
			}

			$breakdown[$bd->name] += $bd->tax;
		}

		// check if we have a tax for the payment
		if ($this->order->totals->payTax > 0)
		{
			// manually register payment tax within the breakdown
			$breakdown[JText::translate('VAPINVPAYTAX')] = $this->order->totals->payTax;
		}

		return $breakdown;
	}
}
