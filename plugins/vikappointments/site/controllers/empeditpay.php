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

VAPLoader::import('libraries.employee.area.controller');

/**
 * Employee area edit payment controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpeditpay extends VAPEmployeeAreaController
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function add()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.payment.data', array());

		// check user permissions
		if (!$auth->managePayments())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditpay');

		return true;
	}

	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.payment.data', array());

		$cid = $app->input->getUint('cid', array(0));

		// check user permissions
		if (!$auth->managePayments($cid[0]))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditpay&cid[]=' . $cid[0]);

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 *
	 * @since   1.7
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			$this->cancel();
		}
	}

	/**
	 * Save employee payments.
	 *
	 * @return 	void
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$auth  = VAPEmployeeAuth::getInstance();

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		$id_payment = $input->getUint('id', 0);

		// check user permissions
		if (!$auth->managePayments($id_payment))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get args
		$args = array();
		$args['name']         = $input->getString('name');
		$args['file']         = $input->getString('file');
		$args['published']    = $input->getUint('published', 0);
		$args['trust']        = $input->getUint('trust', 0);
		$args['charge']       = $input->getFloat('charge', 0);
		$args['setconfirmed'] = $input->getUint('setconfirmed', 0);
		$args['selfconfirm']  = $input->getUint('selfconfirm', 0);
		$args['icon']         = $input->getString('icon', '');
		$args['prenote']      = JComponentHelper::filterText($input->getRaw('prenote'));
		$args['note']         = JComponentHelper::filterText($input->getRaw('note'));
		$args['id']           = $id_payment;

		if ($args['selfconfirm'])
		{
			// always unset auto-confirmation in case of self-confirmation
			// in order to avoid backward compatibility issues
			$args['setconfirmed'] = 0;
		}

		// get payment model
		$model = $this->getModel();

		// try to save arguments
		$id = $model->save($args);

		if (!$id)
		{
			// get string error
			$error = $model->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=empeditpay';

			if ($id_payment)
			{
				$url .= '&cid[]=' . $id_payment;
			}

			// redirect to edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=empeditpay.edit&cid[]=' . $id);

		return true;
	}

	/**
	 * Deletes a list of selected payments.
	 *
	 * @return 	void
	 */
	public function delete()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'uint');

		if ($id = $app->input->getUint('id'))
		{
			$cid[] = $id;
		}

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		try
		{
			// delete selected records
			if ($this->getModel()->delete($cid))
			{
				$app->enqueueMessage(JText::translate('VAPEMPPAYREMOVED1'));	
			}
		}
		catch (Exception $e)
		{
			// an error occurred
			$app->enqueueMessage($e->getMessage(), 'error');
			$this->cancel();

			return false;
		}

		// back to main list (reset list limit)
		$this->cancel(['listlimit' => 0]);

		return true;
	}

	/**
	 * Publishes the payment.
	 *
	 * @return 	void
	 */
	public function publish()
	{
		$app = JFactory::getApplication();

		$cid  = $app->input->get('cid', array(), 'uint');
		$task = $app->input->get('task', null);

		$state = $task == 'unpublish' ? 0 : 1;

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		// publish selected records
		$this->getModel()->publish($cid, $state);

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @param 	array  $query  An array of query arguments.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function cancel(array $query = array())
	{
		$url = 'index.php?option=com_vikappointments&view=emppaylist';

		if ($query)
		{
			$url .= '&' . http_build_query($query);
		}

		$this->setRedirect($url);
	}

	/**
	 * AJAX end-point used to retrieve the configuration
	 * of the selected driver.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function driverfields()
	{
		$input = JFactory::getApplication()->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		$driver = $input->getString('driver');
		$id     = $input->getUint('id', 0);

		$auth = VAPEmployeeAuth::getInstance();
		
		// check user permissions
		if (!$auth->managePayments($id))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}
		
		// access payment config through platform handler
		$form = VAPApplication::getInstance()->getPaymentConfig($driver);
		
		$params = array();

		if ($id)
		{
			// load payment details
			$payment = $this->getModel('payment')->getItem($id);

			if ($payment)
			{
				// use found parameters
				$params = $payment->params;
			}
		}
		
		// build display data
		$data = array(
			'fields' => $form,
			'params' => $params,
			'prefix' => 'gp_',
		);

		// render payment form (use back-end layout)
		$html = JLayoutHelper::render('form.fields', $data, VAPADMIN . DIRECTORY_SEPARATOR . 'layouts');
		
		// send HTML form to caller
		$this->sendJSON(json_encode($html));
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 */
	public function saveOrderAjax()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// get filters set in request
		$filters = $app->input->get('filters', array(), 'array');

		// register employee ID within the filters array to apply
		// the rearrangement of the records only to those payments
		// that are currently assigned to this employee
		$filters['id_employee'] = $auth->id;

		// inject updated filters within the request
		$app->input->set('filters', $filters);

		// invoke parent to commit the new ordering
		parent::saveOrderAjax();
	}
}
