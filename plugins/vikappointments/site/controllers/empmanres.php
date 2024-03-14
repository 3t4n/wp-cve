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
 * Employee area edit reservation controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpmanres extends VAPEmployeeAreaController
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

		$data = array();
		$data['id_service'] = $app->input->getUint('id_ser');
		$data['people']     = $app->input->getUint('people');
		$data['day']        = $app->input->getString('day');
		$data['factor']     = $app->input->getUint('duration_factor', null);

		if (!empty($data['day']))
		{
			$h = $app->input->getUint('hour', 0);
			$m = $app->input->getUint('min', 0);

			// create date instance adjusted to employee timezone
			$date = new JDate($data['day'] . " $h:$m:00", $auth->timezone);

			// get check-in UTC
			$data['checkin_ts'] = $date->toSql();
		}

		// strip missing information
		$data = array_filter($data);

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.reservation.data', $data);

		// check user permissions
		if (!$auth->manageReservation())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->setRedirect('index.php?option=com_vikappointments&view=emplogin');

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empmanres');

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

		$data = array();
		$data['id_service'] = $app->input->getUint('id_ser');
		$data['people']     = $app->input->getUint('people');
		$data['day']        = $app->input->getString('day');
		$data['factor']     = $app->input->getUint('duration_factor', null);

		if (!empty($data['day']))
		{
			$h = $app->input->getUint('hour', 0);
			$m = $app->input->getUint('min', 0);

			// create date instance adjusted to employee timezone
			$date = new JDate($data['day'] . " $h:$m:00", $auth->timezone);

			// get check-in UTC
			$data['checkin_ts'] = $date->toSql();
		}

		// strip missing information
		$data = array_filter($data);

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.reservation.data', $data);

		$cid = $app->input->getUint('cid', array(0));

		// check user permissions
		if (!$auth->manageReservation($cid[0]))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->setRedirect('index.php?option=com_vikappointments&view=emplogin');

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empmanres&cid[]=' . $cid[0]);

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
			$this->setRedirect('index.php?option=com_vikappointments&view=emplogin');
		}
	}

	/**
	 * Save reservation details.
	 *
	 * @return 	void
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$auth  = VAPEmployeeAuth::getInstance();

		// always redirect to login page in case of error
		$this->setRedirect('index.php?option=com_vikappointments&view=emplogin');

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');

			return false;
		}

		$id_reservation = $input->getUint('id', 0);

		// check user permissions
		if (($id_reservation && !$auth->manageReservation($id_reservation)) || (!$id_reservation && !$auth->createReservation()))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');

			return false;
		}

		$args = array();
		$args['id'] = $id_reservation;

		// DETAILS

		$args['id_service'] = $input->getUint('id_service');
		$args['checkin_ts'] = $input->getString('checkin_ts');
		$args['people']     = $input->getUint('people', 1);
		$args['duration']   = $input->getUint('duration');
		$args['sleep']      = $input->getUint('sleep');

		$args['validate_availability'] = $input->getBool('validate_availability', false);

		// ORDER

		$args['service_price'] = $input->getFloat('service_price', 0);
	    $args['service_net']   = $input->getFloat('service_net', 0);
	    $args['service_tax']   = $input->getFloat('service_tax', 0);
	    $args['service_gross'] = $input->getFloat('service_gross', 0);
	    $args['tax_breakdown'] = $input->getString('tax_breakdown');

	    $args['total_net']  = $input->getFloat('total_net', 0);
	    $args['total_tax']  = $input->getFloat('total_tax', 0);
	    $args['total_cost'] = $input->getFloat('total_cost', 0);

		$args['status']     = $input->getString('status', '');
		$args['id_payment'] = $input->getUint('id_payment', 0);
		$args['id_user']    = $input->getUint('id_user', 0);

		$args['notifycust'] = $input->getBool('notifycust', false);

		// OPTIONS

		// get selected options
		$args['options'] = $input->get('option_json', array(), 'array');
		// load deleted options
		$args['deletedOptions'] = $input->get('option_deleted', array(), 'uint');

		// NOTES

		$args['notes']    = JComponentHelper::filterText($input->getRaw('notes', ''));
		$args['id_notes'] = $input->getUint('id_notes', 0);

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

			$url = 'index.php?option=com_vikappointments&view=empmanres';

			if ($id_reservation)
			{
				$url .= '&cid[]=' . $id_reservation;
			}

			// redirect to edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=empmanres.edit&cid[]=' . $id);

		return true;
	}

	/**
	 * Deletes a list of selected appointments.
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

		// always redirect to login page
		$this->setRedirect('index.php?option=com_vikappointments&view=emplogin');

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

			return false;
		}

		try
		{
			// delete selected records
			if ($this->getModel()->delete($cid))
			{
				$app->enqueueMessage(JText::translate('VAPEMPRESREMOVED1'));	
			}
		}
		catch (Exception $e)
		{
			// an error occurred
			$app->enqueueMessage($e->getMessage(), 'error');

			return false;
		}

		return true;
	}

	/**
	 * Returns a list of users that match the query.
	 * The users are filtered using the specified "term" via request.
	 * If the ID is provided, the specific user will be returned instead.
	 *
	 * @return 	void
	 */
	function searchusers()
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

		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
		{
			// not authorised to search the customers
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}
		
		$search = $input->getString('term', '');

		// get customers model
		$model = $this->getModel('customer');

		$options = array();

		/**
		 * @issue 56
		 * In case there is at least a subscription, the website is probably
		 * configured as a portal. This means that the employees are not related 
		 * each other. So, they shouldn't be able to see the details of the
		 * customers that booked reservations for other employees.
		 * 
		 * @since 1.6
		 */
		VAPLoader::import('libraries.models.subscriptions');
		if (VAPSubscriptions::has($group = 1))
		{
			// has a portal of employees, restrict the customers visibility
			$options['id_employee'] = $auth->id;
		}

		// search customers
		$customers = $model->search($search, $options);

		// send users to caller
		$this->sendJSON($customers);
	}

	/**
	 * AJAX end-point used to test how the taxes are applied.
	 * The task expects the following arguments to be set in request.
	 *
	 * @param 	integer  id_tax  The tax ID.
	 * @param 	float    amount  The base amount.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	function taxajax()
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

		$auth = VAPEmployeeAuth::getInstance();

		// make sure the user is an employee
		if (!$auth->isEmployee())
		{
			// not authorised
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		$id_tax  = $input->getUint('id_tax', 0);
		$amount  = $input->getFloat('amount', 0);

		$options = array();
		$options['id_user'] = $input->getUint('id_user', 0);
		$options['lang']    = $input->getString('langtag', null);
		$options['subject'] = $input->getString('subject', null);

		VAPLoader::import('libraries.tax.factory');

		// calculate taxes
		$result = VAPTaxFactory::calculate($id_tax, $amount, $options);
		
		// send result to caller
		$this->sendJSON($result);
	}
}
