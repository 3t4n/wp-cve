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
 * Class used to generate the invoices of the employees subscriptions.
 * Since the invoices of the subscriptions are almost equals to the invoices
 * of the appointments, this class will extend the methods declared by
 * VAPInvoiceAppointments.
 *
 * @since 	1.6
 */
class VAPInvoiceEmployees extends VAPInvoiceAppointments
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	The order details.
	 */
	public function __construct($order)
	{
		$this->order = $order;

		if (!empty($this->order->billing))
		{
			// replicate billing details by using the customer notation
			$this->order->billing->billing_address = $this->order->billing->address;
			$this->order->billing->billing_state   = $this->order->billing->state;
			$this->order->billing->billing_city    = $this->order->billing->city;
			$this->order->billing->billing_zip     = $this->order->billing->zip;
		}
	}

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
		return parent::getInvoiceFolderPath() . DIRECTORY_SEPARATOR . 'employees';
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
		return parent::getInvoiceFolderURI() . 'employees/';
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
			'order' => $this->order,
		);

		// create layout file (always read from the site section of VikAppointments)
		$layout = new JLayoutFile('templates.invoice.employee', null, [
			'component' => 'com_vikappointments',
			'client'    => 'site',
		]);

		return $layout->render($data);
	}

	/**
	 * @override
	 * Returns the e-mail address of the employee that should
	 * receive the invoice via mail.
	 *
	 * @return 	string 	The employee e-mail.
	 */
	public function getRecipient()
	{
		return $this->order->employee->email;
	}
}
