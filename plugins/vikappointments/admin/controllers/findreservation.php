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
 * VikAppointments find reservation controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerFindreservation extends VAPControllerAdmin
{
	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		// check user permissions
		if (!$user->authorise('core.edit', 'com_vikappointments') || !$user->authorise('core.access.reservations', 'com_vikappointments'))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->setRedirect('index.php?option=com_vikappointments&view=reservations');

			return false;
		}

		$args = array();
		$args['id_emp'] = $app->input->getUint('id_employee', 0);
		$args['id_ser'] = $app->input->getUint('id_service', 0);

		$args['id_res'] = $app->input->getUint('id', 0);
		$args['people'] = $app->input->getUint('people', 1);
		$args['day']    = $app->input->getString('checkin_ts', '');

		$this->setRedirect('index.php?option=com_vikappointments&view=findreservation&' . http_build_query(array_filter($args)));

		return true;
	}

	/**
	 * AJAX end-point used to fetch the availability timeline.
	 * This task expects the following arguments set in request.
	 *
	 * @param 	integer  $id_ser  The service ID.
	 * @param 	integer  $id_emp  The employee ID.
	 * @param 	string   $daty    The check-in date.
	 * @param 	integer  $id_res  The selected appointment ID.
	 * @param 	integer  $people  The number of participants.
	 *
	 * @return 	void
	 */
	public function timelineajax()
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

		$args = array();
		$args['id_ser'] = $input->getUint('id_ser', 0);
		$args['id_emp'] = $input->getUint('id_emp', 0);
		$args['date'] 	= $input->getString('day', '');
		$args['id_res'] = $input->getUint('id_res', 0);
		$args['people'] = $input->getUint('people', 1);

		// get model
		$model = $this->getModel();
		// use model to create the timeline
		$timeline = $model->getTimeline($args);

		$result = new stdClass;

		if ($timeline)
		{
			// create timeline response
			$result->html     = $timeline->display();
			$result->timeline = $timeline->getTimeline();
		}
		else
		{
			// raise error message
			$result->error    = $model->getError($index = null, $string = false);
			$result->timeline = array();

			$vik = VAPApplication::getInstance();

			if ($result->error instanceof Exception)
			{
				// exception found, use critical alert
				$result->html = $vik->alert($result->error->getMessage(), 'error');
			}
			else
			{
				// default availability error, use warning alert
				$result->html = $vik->alert($result->error);
			}
		}

		// send timeline to caller
		$this->sendJSON($result);
	}

	/**
	 * AJAX end-point used to load a list of appointments for
	 * the specified date and time.
	 *
	 * @param 	integer  $id_emp  The employee ID.
	 * @param 	string   $date    The check-in date.
	 * @param 	integer  $hour    The check-in hour.
	 * @param 	integer  $min     The check-in minute.
	 *
	 * @return 	void
	 */
	public function appointmentsajax()
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

		$id_emp = $input->getUint('id_emp', 0);
		$date 	= $input->getString('date', '');
		$hour   = $input->getUint('hour', 0);
		$min    = $input->getUint('min', 0);

		// create date instance
		$date = new JDate($date);
		// modify time
		$date->modify("{$hour}:{$min}:00");

		// get appointments model
		$model = $this->getModel('reservation');

		// find appointments list
		$list = $model->getAppointmentsAt($date->format('Y-m-d H:i:s'), $id_emp);

		// return list to caller
		$this->sendJSON($list);
	}
}
