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

/**
 * VikAppointments employee area payment management model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpeditpay extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		$auth = VAPEmployeeAuth::getInstance();

		// get payment model
		$paymentModel = JModelVAP::getInstance('payment');

		if (isset($data['icon']))
		{
			// set font icon type in case an icon was selected
			$data['icontype'] = $data['icon'] ? 1 : 0;
		}

		// force author and owner
		$data['createdby']   = JFactory::getUser()->id;
		$data['id_employee'] = $auth->id;

		// Check whether the file has been specified or not, which would mean that
		// we are trying to edit the payment of an employee without being the author.
		// This way we can prevent the update of the driver and its parameters.
		if (!empty($data['file']))
		{
			$input = JFactory::getApplication()->input;

			try
			{
				// get payment configuration
				$config = VAPApplication::getInstance()->getPaymentConfig($data['file']);

				$data['params'] = array();

				// load configuration from request
				foreach ($config as $k => $p)
				{
					$data['params'][$k] = $input->get('gp_' . $k, '', 'string');
				}
			}
			catch (Exception $e)
			{
				// unset file to raise error before saving the payment
				$data['file'] = false;
			}
		}

		JFactory::getApplication()->setUserState('vap.emparea.payment.data', $data);

		// delegate save to payment model
		$id = $paymentModel->save($data);

		if (!$id)
		{
			// obtain error from model
			$error = $paymentModel->getError();

			if ($error)
			{
				// propagate error
				$this->setError($error);
			}

			return false;
		}

		return $id;
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
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		$result = false;

		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// get rid of those records that do not belong to this employee
		$ids = array_values(array_filter($ids, function($id) use ($auth)
		{
			// make sure the employee can manage this record
			return $auth->managePayments($id);
		}));

		if (!$ids)
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}

		// delegate delete to payment model
		return JModelVAP::getInstance('payment')->delete($ids);
	}

	/**
	 * Basic publish/unpublish implementation.
	 *
	 * @param   mixed    $ids    Either the record ID or a list of records.
	 * @param 	integer  $state  The publishing status.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function publish($ids, $state = 1, $alias = null)
	{
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
		}
		
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// get rid of those records that do not belong to this employee
		$ids = array_values(array_filter($ids, function($id) use ($auth)
		{
			// make sure the employee can manage this record
			return $auth->managePayments($id);
		}));

		if (!$ids)
		{
			// there's noting to publish/unpublish
			return false;
		}

		// delegate publish to payment model
		return JModelVAP::getInstance('payment')->publish($ids, $state, $alias);
	}

	/**
	 * Method to get a table object.
	 *
	 * @param   string  $name     The table name.
	 * @param   string  $prefix   The class prefix.
	 * @param   array   $options  Configuration array for table.
	 *
	 * @return  JTable  A table object.
	 *
	 * @throws  Exception
	 */
	public function getTable($name = 'payment', $prefix = '', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
}
