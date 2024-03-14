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

VAPLoader::import('libraries.mvc.controllers.admin');

/**
 * VikAppointments payment controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerPayment extends VAPControllerAdmin
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 */
	public function add()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$data = array();

		$file = $app->input->getString('file', '');

		if ($file)
		{
			$data['file'] = $file;
		}

		$id_employee = $app->input->getUint('id_employee', 0);

		if ($id_employee > 0)
		{
			$data['id_employee'] = $id_employee;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.payment.data', $data);

		// check user permissions
		if (!$user->authorise('core.create', 'com_vikappointments') || !$user->authorise('core.access.payments', 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=managepayment');

		return true;
	}

	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		// unset user state for being recovered again
		$app->setUserState('vap.payment.data', array());

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.payments', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$cid = $app->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&view=managepayment&cid[]=' . $cid[0]);

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			$this->cancel();
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the creation
	 * page of a new record.
	 *
	 * @return 	void
	 */
	public function savenew()
	{
		if ($this->save())
		{
			$input = JFactory::getApplication()->input;

			$url = 'index.php?option=com_vikappointments&task=payment.add';

			$id_employee = $input->getUint('id_employee', 0);

			if ($id_employee > 0)
			{
				$url .= '&id_employee=' . $id_employee;
			}

			$this->setRedirect($url);
		}
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @return 	boolean
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

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
		
		$args = array();
		$args['name']         = $input->getString('name', '');
		$args['file']         = $input->getString('file', null);
		$args['charge']       = $input->getFloat('charge', 0);
		$args['id_tax']       = $input->getUint('id_tax', 0);
		$args['published']    = $input->getUint('published', 0);
		$args['setconfirmed'] = $input->getUint('setconfirmed', 0);
		$args['selfconfirm']  = $input->getUint('selfconfirm', 0);
		$args['trust']        = $input->getUint('trust', 0);
		$args['position']     = $input->getString('position', '');
		$args['icontype']     = $input->getUint('icontype', 0);
		$args['level']        = $input->getUint('level', 0);
		$args['prenote']      = JComponentHelper::filterText($input->getRaw('prenote', ''));
		$args['note']         = JComponentHelper::filterText($input->getRaw('note', ''));
		$args['id_employee']  = $input->getUint('id_employee', 0);
		$args['id']           = $input->getUint('id', 0);

		switch ($args['icontype'])
		{
			case 1:
				$args['icon'] = $input->getString('font_icon');
				break;
			
			case 2:
				$args['icon'] = $input->getString('upload_icon');
				break;

			default:
				$args['icon'] = '';
		}

		$allowedfor = $input->getUint('allowedfor', 1);
		$args['appointments'] = $args['subscr'] = 0;

		if ($allowedfor == 1 || $allowedfor == 3)
		{
			$args['appointments'] = 1;
		}
		
		if ($allowedfor == 2 || $allowedfor == 3)
		{
			$args['subscr'] = 1;
		}

		if ($args['selfconfirm'])
		{
			// always unset auto-confirmation in case of self-confirmation
			// in order to avoid backward compatibility issues
			$args['setconfirmed'] = 0;
		}

		// Check whether the file has been specified or not, which would mean that
		// we are trying to edit the payment of an employee without being the author.
		// This way we can prevent the update of the driver and its parameters.
		if (!is_null($args['file']))
		{
			try
			{
				// get payment configuration
				$config = VAPApplication::getInstance()->getPaymentConfig($args['file']);

				$args['params'] = array();

				// load configuration from request
				foreach ($config as $k => $p)
				{
					$args['params'][$k] = $input->get('gp_' . $k, '', 'string');
				}
			}
			catch (Exception $e)
			{
				// unset file to raise error before saving the payment
				$args['file'] = false;
			}
		}

		$rule = 'core.' . ($args['id'] > 0 ? 'edit' : 'create');

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments') || !$user->authorise('core.access.payments', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get payment model
		$payment = $this->getModel();

		// try to save arguments
		$id = $payment->save($args);

		if (!$id)
		{
			// get string error
			$error = $payment->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=managepayment';

			if ($args['id'])
			{
				$url .= '&cid[]=' . $args['id'];
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=payment.edit&cid[]=' . $id);

		return true;
	}

	/**
	 * Deletes a list of records set in the request.
	 *
	 * @return 	boolean
	 */
	public function delete()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

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

		$cid = $app->input->get('cid', array(), 'uint');

		// check user permissions
		if (!$user->authorise('core.delete', 'com_vikappointments') || !$user->authorise('core.access.payments', 'com_vikappointments'))
		{
			// back to main list, not authorised to delete records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// delete selected records
		$this->getModel()->delete($cid);

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Publishes the selected records.
	 *
	 * @return 	boolean
	 */
	public function publish()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

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

		$cid  = $app->input->get('cid', array(), 'uint');
		$task = $app->input->get('task', null);

		$state = $task == 'unpublish' ? 0 : 1;

		// check user permissions
		if (!$user->authorise('core.edit.state', 'com_vikappointments') || !$user->authorise('core.access.payments', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// change state of selected records
		$this->getModel()->publish($cid, $state);

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$input = JFactory::getApplication()->input;

		$url = 'index.php?option=com_vikappointments&view=payments';

		$id_employee = $input->getUint('id_employee', 0);

		if ($id_employee > 0)
		{
			$url .= '&id_employee=' . $id_employee;
		}

		$this->setRedirect($url);
	}

	/**
	 * AJAX end-point used to retrieve the configuration
	 * of the selected driver.
	 *
	 * @return 	void
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
		
		// access payment config through platform handler
		$form = VAPApplication::getInstance()->getPaymentConfig($driver);
		
		$params = array();

		if ($id)
		{
			// load payment details
			$payment = $this->getModel()->getItem($id);

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

		// render payment form
		$html = JLayoutHelper::render('form.fields', $data);
		
		// send HTML form to caller
		$this->sendJSON(json_encode($html));
	}
}
