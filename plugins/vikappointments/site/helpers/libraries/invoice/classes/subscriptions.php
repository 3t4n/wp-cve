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
 * Class used to generate the invoices of the customers subscriptions.
 * Since the invoices of the subscriptions are almost equals to the invoices
 * of the appointments, this class will extend the methods declared by
 * VAPInvoiceAppointments.
 *
 * @since 	1.7
 */
class VAPInvoiceSubscriptions extends VAPInvoiceAppointments
{
	/**
	 * @override
	 * Returns the destination absolute path of the invoices folder.
	 *
	 * @return 	string 	The invoice folder path.
	 */
	public function getInvoiceFolderPath()
	{
		return parent::getInvoiceFolderPath() . DIRECTORY_SEPARATOR . 'subscriptions';
	}

	/**
	 * @override
	 * Returns the destination URI of the invoices folder.
	 *
	 * @return 	string 	The invoice folder URI.
	 */
	public function getInvoiceFolderURI()
	{
		return parent::getInvoiceFolderURI() . 'subscriptions/';
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
		$layout = new JLayoutFile('templates.invoice.subscription', null, [
			'component' => 'com_vikappointments',
			'client'    => 'site',
		]);

		return $layout->render($data);
	}
}
