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

VAPLoader::import('libraries.mvc.model');
VAPLoader::import('libraries.order.factory');
VAPLoader::import('libraries.invoice.factory');

/**
 * VikAppointments invoice model.
 *
 * @since 1.7
 */
class VikAppointmentsModelInvoice extends JModelVAP
{
	/**
	 * Generates the invoices in mass.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	array  An array of imported rows on success.
	 */
	public function saveMass($data)
	{
		$dbo = JFactory::getDbo();

		$generated = 0;
		$notified  = 0;

		// retrieve all reservation/orders
		$q = $dbo->getQuery(true);
		
		// select only the ID
		$q->select($dbo->qn('id'));

		// load the correct table
		if ($data['group'] == 'packages')
		{
			$q->from($dbo->qn('#__vikappointments_package_order'));

			// get any approved codes
			$approved = JHtml::fetch('vaphtml.status.find', 'code', array('packages' => 1, 'approved' => 1)); 
		}
		else if ($data['group'] == 'employees')
		{
			$q->from($dbo->qn('#__vikappointments_subscr_order'));
			$q->where($dbo->qn('id_employee') . ' > 0');

			// get any approved codes
			$approved = JHtml::fetch('vaphtml.status.find', 'code', array('subscriptions' => 1, 'approved' => 1));
		}
		else if ($data['group'] == 'subscriptions')
		{
			$q->from($dbo->qn('#__vikappointments_subscr_order'));
			$q->where($dbo->qn('id_user') . ' > 0');

			// get any approved codes
			$approved = JHtml::fetch('vaphtml.status.find', 'code', array('subscriptions' => 1, 'approved' => 1));
		}
		else
		{
			$q->from($dbo->qn('#__vikappointments_reservation'));

			// get any approved codes
			$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1)); 
		}

		if (!empty($data['cid']))
		{
			// get specified orders
			$q->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $data['cid'])) . ')');
		}
		else
		{
			// create range of dates
			$start = new JDate("{$data['year']}-{$data['month']}-1 00:00:00");

			$end = clone $start;
			$end->modify('+1 month');

			// get orders with creation date in the specified month
			$q->where($dbo->qn('createdon') . ' >= ' . $dbo->q($start->toSql()));
			$q->where($dbo->qn('createdon') . ' < ' . $dbo->q($end->toSql()));
		}

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		// order by ascending date
		$q->order($dbo->qn('createdon') . ' ASC');

		$dbo->setQuery($q);
		
		// generate invoices one by one
		foreach ($dbo->loadColumn() as $order_id)
		{
			// reset ID
			$data['id'] = 0;
			// specify order ID
			$data['id_order'] = $order_id;

			if ($this->save($data))
			{
				// update generated count on success
				$generated++;

				if ($this->isNotified())
				{
					// increase notified count in case the invoice was sent to the customer
					$notified++;
				}
			}
		}

		return array(
			'generated' => $generated,
			'notified'  => $notified,
		);
	}

	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$dbo = JFactory::getDbo();

		$data = (array) $data;

		if (empty($data['id']))
		{
			if (empty($data['id_order']) || !isset($data['group']))
			{
				// ID order is mandatory when creating an invoice
				$this->setError('Missing Order ID');

				return false;
			}

			// check if there is already an invoice for the given order
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id'))
				->from($dbo->qn('#__vikappointments_invoice'))
				->where($dbo->qn('id_order') . ' = ' . (int) $data['id_order'])
				->where($dbo->qn('group') . ' = ' . $dbo->q($data['group']));

			$dbo->setQuery($q, 0, 1);
			
			// if order exists, switch to UPDATE
			$data['id'] = (int) $dbo->loadResult();
		}

		if (!empty($data['id']) && empty($data['id_order']))
		{
			// retrieve order ID of the stored invoice
			$q = $dbo->getQuery(true)
				->select($dbo->qn('id_order'))
				->from($dbo->qn('#__vikappointments_invoice'))
				->where($dbo->qn('id') . ' = ' . (int) $data['id']);

			$dbo->setQuery($q, 0, 1);
			$data['id_order'] = (int) $dbo->loadResult();

			if (!$data['id_order'])
			{
				// invoice not found, abort
				$this->setError(sprintf('Invoice [%d] not found', $data['id']));

				return false;
			}
		}

		if ($data['id'] && empty($data['overwrite']))
		{
			// do not overwrite existing record (error not needed)
			return false;
		}

		// generate invoice and obtain resulting data
		$invoice = $this->generateInvoice($data);

		if (!$invoice)
		{
			return false;
		}

		// inject found data into the array to bind
		$data = array_merge($data, $invoice);

		// attempt to save the invoice
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred, do not go ahead
			return false;
		}

		// get generator instance
		$generator = $this->createGenerator($data);

		$this->_notified = false;

		if (!isset($data['notify']))
		{
			// rely on the global configuration
			$data['notify'] = (bool) $generator->getParams()->sendinvoice;
		}

		if ($data['notify'])
		{
			// send e-mail notification
			$this->_notified = $generator->send($data['path']);
		}

		return $id;
	}

	/**
	 * Returns whether the customer has been notified or not.
	 *
	 * @return 	boolean
	 */
	public function isNotified()
	{
		return !empty($this->_notified);
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		$dbo = JFactory::getDbo();

		// get all invoice files
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('file', 'group')))
			->from($dbo->qn('#__vikappointments_invoice'))
			->where($dbo->qn('id') . ' IN (' . implode(',', $ids) . ')');

		$dbo->setQuery($q);
		$files = $dbo->loadObjectList();

		if (!$files)
		{
			// nothing to delete
			return false;
		}

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		// delete invoices from file system
		foreach ($files as $inv)
		{
			// create invoice object to obtain the right destination folder
			$invoice = VAPInvoiceFactory::getInvoice(null, $inv->group);
			$path = $invoice->getInvoiceFolderPath() . DIRECTORY_SEPARATOR . $inv->file;

			// delete file only if exists
			if (is_file($path))
			{
				unlink($path);
			}
		}

		return true;
	}

	/**
	 * Helper method used to generate an invoice.
	 *
	 * @param 	mixed 	$data  Either an array or an object of data helpful
	 *                         for the creation of the invoice.
	 *
	 * @return 	mixed   The invoice path on success, false otherwise.
	 */
	public function generateInvoice($data)
	{
		$data = (array) $data;

		try
		{
			// load order details according to the specified group
			if ($data['group'] == 'packages')
			{
				$order = VAPOrderFactory::getPackages($data['id_order']);
			}
			else if ($data['group'] == 'employees')
			{
				$order = VAPOrderFactory::getEmployeeSubscription($data['id_order']);
			}
			else if ($data['group'] == 'subscriptions')
			{
				$order = VAPOrderFactory::getCustomerSubscription($data['id_order']);
			}
			else
			{
				// fallback to default appointments
				$order = VAPOrderFactory::getAppointments($data['id_order']);
			}
		}
		catch (Exception $e)
		{
			// probably order not found
			$this->setError($e);

			return false;
		}

		// get invoices generator
		$generator = $this->createGenerator($data);

		try
		{
			// load invoice data
			$invoice = VAPInvoiceFactory::getInvoice($order, $data['group']);
		}
		catch (Exception $e)
		{
			// an error occurred, register it and abort
			$this->setError($e);

			return false;
		}
		
		// attach invoice to generator
		$generator->setInvoice($invoice);

		if (isset($data['increase']))
		{
			// increase only if specified
			$increaseNumber = (bool) $data['increase'];
		}
		else
		{
			// increase invoice number only on insert
			$increaseNumber = empty($data['id']);
		}

		try
		{
			// try to generate the invoice and return the resulting path
			$path = $generator->generate($increaseNumber);
		}
		catch (Exception $e)
		{
			// an error occurred, register it and abort
			$this->setError($e);

			return false;
		}

		return $path;
	}

	/**
	 * Creates the invoices generator by passing the specified
	 * parameters and constraints. Notice that the given data
	 * will be injected within the generator only once.
	 *
	 * In order to force the parameters, it will be needed to
	 * manually chain setParams() after getting the generator
	 * instance.
	 *
	 * @param 	mixed 	$data  Either an object or an array.
	 *
	 * @return 	VAPInvoiceGenerator
	 */
	public function createGenerator($data)
	{	
		if (!isset($this->_invoiceGenerator))
		{
			$data = (array) $data;

			// create invoice generator only once
			$this->_invoiceGenerator = VAPInvoiceFactory::getGenerator();

			if (!empty($data['params']))
			{
				// inject passed parameters
				$this->_invoiceGenerator->setParams($data['params']);
			}

			if (!empty($data['constraints']))
			{
				// inject passed constraints
				$this->_invoiceGenerator->setConstraints($data['constraints']);
			}
		}

		return $this->_invoiceGenerator;
	}

	/**
	 * Method to download one or more invoices.
	 *
	 * @param   mixed  $ids  Either the record ID or a list of records.
	 *
	 * @return  mixed  The path of the file to download (either a PDF or a ZIP).
	 * 				   Returns false in case of errors.
	 */
	public function download($ids)
	{
		$ids = (array) $ids;

		$dbo = JFactory::getDbo();

		if (!$ids)
		{
			// nothing to search
			return false;
		}

		// get all invoice files
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('file', 'group')))
			->from($dbo->qn('#__vikappointments_invoice'))
			->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $ids)) . ')');

		$dbo->setQuery($q);
		$files = $dbo->loadObjectList();

		if (!$files)
		{
			// abort, nothing else to do here
			return false;
		}

		if (count($files) == 1)
		{
			// create invoice object to obtain the right destination folder
			$invoice = VAPInvoiceFactory::getInvoice(null, $files[0]->group);
			$path = $invoice->getInvoiceFolderPath() . DIRECTORY_SEPARATOR . $files[0]->file;

			if (!is_file($path))
			{
				// file not found, raise error
				$this->setError(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
				return false;
			}

			// only one record, return the base path of the file to download
			return $path;
		}

		// create a package to download multiple files at once
		if (!class_exists('ZipArchive'))
		{
			// ZipArchive class is mandatory to create a package
			$this->setError('The ZipArchive class is not installed on your server.');
			return false;
		}

		$name = JHtml::fetch('date', 'now', 'Y-m-d H_i_s');
		$zipname = VAPINVOICE . DIRECTORY_SEPARATOR . 'invoices-' . $name . '.zip';
		
		// init package
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);

		// add files to the package
		foreach ($files as $inv)
		{
			// create invoice object to obtain the right destination folder
			$invoice = VAPInvoiceFactory::getInvoice(null, $inv->group);
			$path = $invoice->getInvoiceFolderPath() . DIRECTORY_SEPARATOR . $inv->file;
			
			// make sure the file exists before adding it
			if (is_file($path))
			{
				$zip->addFile($path, basename($path));
			}
		}

		// compress the package
		$zip->close();

		// return the path of the archive
		return $zipname;
	}

	/**
	 * Returns the invoice details of the given order.
	 *
	 * @param 	integer  $id     The order ID.
	 * @param 	string 	 $group  The group to which the order belongs.
	 *
	 * @return 	mixed    The invoice details on success, false otherwise.
	 */
	public function getInvoice($id, $group = null)
	{
		if (!$group)
		{
			// group not specified, use the most common one
			$group = 'appointments';
		}

		// prepare conditions
		$where = array(
			'id_order' => (int) $id,
			'group'    => $group,
		);

		// load invoice
		$invoice = $this->getItem($where);

		if (!$invoice)
		{
			// invoice not found
			return false;
		}

		// create invoice instance
		$instance = VAPInvoiceFactory::getInvoice(null, $group);

		// set invoice path and URI
		$invoice->path = $instance->getInvoiceFolderPath() . DIRECTORY_SEPARATOR . $invoice->file;
		$invoice->uri  = $instance->getInvoiceFolderURI() . $invoice->file;

		if (!is_file($invoice->path))
		{
			// the invoice was created but the file is missing...
			return false;
		}

		return $invoice;
	}

	/**
	 * Returns the invoice details of the given order.
	 * Helper method to retrieve those invoices that have
	 * been generated before the 1.7 version.
	 *
	 * @param 	integer  $id     The order ID.
	 * @param 	string   $sid    The order serial ID.
	 * @param 	string 	 $group  The group to which the order belongs.
	 *
	 * @return 	mixed    The invoice details on success, false otherwise.
	 */
	public function getInvoiceBC($id, $sid, $group = null)
	{
		if (!$group)
		{
			// group not specified, use the most common one
			$group = 'appointments';
		}

		// create invoice instance
		$instance = VAPInvoiceFactory::getInvoice(null, $group);

		// build file name
		$file = $id . '-' . $sid . '.pdf';

		$invoice = new stdClass;

		// set invoice path and URI
		$invoice->path = $instance->getInvoiceFolderPath() . DIRECTORY_SEPARATOR . $file;
		$invoice->uri  = $instance->getInvoiceFolderURI() . $file;

		if (!is_file($invoice->path))
		{
			// missing file
			return false;
		}

		return $invoice;
	}
}
