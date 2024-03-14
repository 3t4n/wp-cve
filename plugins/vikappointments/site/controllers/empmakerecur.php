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
 * Employee area make recurrence controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerEmpmakerecur extends VAPEmployeeAreaController
{
	/**
	 * AJAX end-point used to get a recurrence preview.
	 * This task expects the following arguments set in request.
	 *
	 * @param 	integer  id      The appointment ID.
	 * @param 	integer  by      The recurrence by identifier.
	 * @param   integer  amount  The recurrence amount.
	 * @param 	integer  for     The recurrence for identifier.
	 *
	 * @return 	void
	 */
	public function preview()
	{
		$input = JFactory::getApplication()->input;
		$auth  = VAPEmployeeAuth::getInstance();

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

		$id = $input->getUint('id');

		// check user permissions
		if (!$id || !$auth->manageReservation($id, $readOnly = true))
		{
			// not allowed to access the specified order
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		try
		{
			// get order details
			VAPLoader::import('libraries.order.factory');
			$order = VAPOrderFactory::getAppointments($id, JFactory::getLanguage()->getTag());
		}
		catch (Exception $e)
		{
			// raise AJAX error, order not found
			UIErrorFactory::raiseError($e->getCode(), $e->getMessage());
		}

		// access first appointment
		$appointment = $order->appointments[0];

		// get recurrence instructions
		$recurrence = array();
		$recurrence['by']     = $input->getUint('by', 0);
		$recurrence['amount'] = $input->getUint('amount', 0);
		$recurrence['for']    = $input->getUint('for', 0);

		// get recurrence model
		$model = $this->getModel('makerecurrence');

		// create date by using the local timezone of the employee, because the DST
		// might change over time
		$tz = new DateTimeZone($auth->timezone);
		$empDate = new JDate($appointment->checkin->utc);
		$empDate->setTimezone($tz);

		// compose dates recurrence
		$arr = $model->getRecurrence($empDate, $recurrence);
		
		if (!$arr)
		{
			// invalid recurrence
			UIErrorFactory::raiseError(500, JText::translate('VAPMAKERECNOROWS'));
		}

		$config = VAPFactory::getConfig();

		$results = array();

		// iterate all dates found
		foreach ($arr as $date)
		{
			$tmp = array();
			$tmp['format'] = JHtml::fetch('date', $date, $config->get('dateformat') . ' ' . $config->get('timeformat'));
			$tmp['date']   = $date;

			// check the availability for the same appointment,
			// but with the new date
			$tmp['available'] = $model->checkAvailability($appointment, $date);

			if ($tmp['available'])
			{
				// available
				$tmp['message'] = JText::translate('VAPMAKERECDATEOK');
			}
			else
			{
				// not available
				$tmp['message'] = JText::translate('VAPMAKERECDATEFAIL');

				// get reason from model
				$tmp['reason'] = $model->getError(null, true);

				// DO NOT suggest here new employee
				$tmp['employees'] = null;

				// check availability close to the current date
				$tmp['times'] = $model->checkNearbyAvailability($appointment, $date);

				if (!$tmp['times'])
				{
					// unset times list if empty
					$tmp['times'] = null;
				}
			}

			// register result
			$results[] = $tmp;
		}

		// send response to caller
		$this->sendJSON($results);
	}

	/**
	 * AJAX end-point used to create a recurrence for the appointment.
	 * This task expects the following arguments set in request.
	 *
	 * @param 	integer  id      The appointment ID.
	 * @param 	integer  by      The recurrence by identifier.
	 * @param   integer  amount  The recurrence amount.
	 * @param 	integer  for     The recurrence for identifier.
	 * @param 	array    hints   An associative array containing the hints for
	 *                           those appointments without availability.
	 *
	 * @return 	void
	 */
	public function create()
	{
		$input = JFactory::getApplication()->input;
		$auth  = VAPEmployeeAuth::getInstance();

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

		$id = $input->getUint('id', 0);

		// check user permissions
		if (!$id || !$auth->manageReservation($id, $readOnly = true) || !$auth->createReservation())
		{
			// not allowed to access the specified order
			UIErrorFactory::raiseError(403, JText::translate('JERROR_ALERTNOAUTHOR'));
		}

		try
		{
			// get order details
			VAPLoader::import('libraries.order.factory');
			$order = VAPOrderFactory::getAppointments($id, JFactory::getLanguage()->getTag());
		}
		catch (Exception $e)
		{
			// raise AJAX error, order not found
			UIErrorFactory::raiseError($e->getCode(), $e->getMessage());
		}

		// access first appointment
		$appointment = $order->appointments[0];

		// get recurrence instructions
		$recurrence = array();
		$recurrence['by']     = $input->getUint('by', 0);
		$recurrence['amount'] = $input->getUint('amount', 0);
		$recurrence['for']    = $input->getUint('for', 0);

		// get selected hints
		$hints = $input->get('hints', array(), 'array');

		// get recurrence model
		$model = $this->getModel('makerecurrence');

		// create recurrence
		$count = $model->createRecurrence($appointment, $recurrence, $hints);

		if (!$count)
		{
			// raise AJAX error, no created appointments
			UIErrorFactory::raiseError(500, JText::translate('VAPMAKERECSUCCESS0'));
		}

		// prepare response array
		$result = array();
		$result['message'] = JText::sprintf('VAPMAKERECSUCCESS1', $count);
		$result['count']   = $count;

		// send response to caller
		$this->sendJSON($result);
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function cancel()
	{
		$cid = JFactory::getApplication()->input->getUint('cid', array(0));

		$this->setRedirect('index.php?option=com_vikappointments&task=empmanres.edit&cid[]=' . $cid[0]);
	}
}
