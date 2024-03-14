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
 * VikAppointments calendar controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerCalendar extends VAPControllerAdmin
{
	/**
	 * Task used to switch calendar layout.
	 *
	 * @return 	void
	 */
	function switch()
	{
		$app = JFactory::getApplication();

		// get requested layout
		$page = $app->input->get('layout', 'calendar');

		// register return URL
		$this->setRedirect('index.php?option=com_vikappointments&view=' . $page);

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

		// update page in configuration
		VAPFactory::getConfig()->set('calendarlayout', $page);
		// update user state to allow different users to see different
		// layouts for their whole session
		$app->setUserState('vap.calendar.layout', $page);

		return true;
	}

	/**
	 * AJAX end-point used to load a list of appointments for
	 * the specified date.
	 *
	 * @param 	integer  $id_emp  The employee ID.
	 * @param 	string   $date    The check-in date.
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

		// get appointments model
		$model = $this->getModel('reservation');

		// find appointments list
		$list = $model->getAppointmentsOn($date, $id_emp);

		// get rid of closures
		$list = array_values(array_filter($list, function($elem)
		{
			return !$elem->closure;
		}));

		// build appointments HTML table
		$html = JLayoutHelper::render('calendar.appointments', array('appointments' => $list));

		// return list to caller
		$this->sendJSON(json_encode($html));
	}
}
