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
 * Starting from the 1.7 version, while generating an invoice, the system registers also a record
 * within the database. We need to automatically create an invoice within the database to make
 * sure the invoices are properly displayed.
 *
 * @since 1.7
 */
class VAPUpdateRuleRegisterInvoicesData1_7 extends VAPUpdateRule
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
		$this->registerAppointments();
		$this->registerPackages();
		$this->registerSubscriptions();

		return true;
	}

	/**
	 * Registers the information of the invoices issued for the appointments.
	 *
	 * @return 	void
	 */
	private function registerAppointments()
	{
		$this->registerInvoiceData(
			VAPINVOICE,
			'appointments'
		);
	}

	/**
	 * Registers the information of the invoices issued for the packages.
	 *
	 * @return 	void
	 */
	private function registerPackages()
	{
		$this->registerInvoiceData(
			VAPINVOICE . DIRECTORY_SEPARATOR . 'packages',
			'packages'
		);
	}

	/**
	 * Registers the information of the invoices issued for the subscriptions.
	 *
	 * @return 	void
	 */
	private function registerSubscriptions()
	{
		$this->registerInvoiceData(
			VAPINVOICE . DIRECTORY_SEPARATOR . 'employees',
			'employees'
		);
	}

	/**
	 * Helper method used to register the invoice information.
	 *
	 * @param 	string  $path   The folder in which the invoices are stored.
	 * @param 	string  $group  The group identifier.
	 *
	 * @return 	void
	 */
	private function registerInvoiceData($path, $group)
	{
		$dbo = JFactory::getDbo();

		// load all the invoices for the appointments
		foreach (glob($path . DIRECTORY_SEPARATOR . '*.pdf') as $path)
		{
			$invoice = new stdClass;

			// register file name
			$invoice->file = basename($path);

			// extract order number from file name
			list($ordnum) = explode('-', $invoice->file);
			$invoice->id_order = (int) $ordnum;

			// create a placeholder because it is not possible to extract the
			// invoice number from the PDF file
			$invoice->inv_number = 'X/Y';

			// fetch file creation date
			$timestamp = filemtime($path);
			$date = JFactory::getDate(date('Y-m-d H:i:s', $timestamp), date_default_timezone_get());

			// define creation date according to the last modified date of the file
			$invoice->createdon = $date->toSql();

			// use the same information for the invoice date
			$invoice->inv_date = $invoice->createdon;

			// define invoice group
			$invoice->group = $group;

			// insert invoice record
			$dbo->insertObject('#__vikappointments_invoice', $invoice, 'id');
		}
	}
}
