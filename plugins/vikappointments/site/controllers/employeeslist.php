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
 * VikAppointments employees list view controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerEmployeeslist extends VAPControllerAdmin
{
	/**
	 * AJAX task used to return the availability table of a specific employee.
	 *
	 * This method expects the following parameters to be sent via POST or GET.
	 *
	 * @param 	integer  id_emp  The employee ID.
	 * @param 	integer  id_ser  The service ID.
	 * @param 	string   date    The check-in date.
	 *
	 * @return 	void
	 */
	public function availtableajax()
	{
		$input = JFactory::getApplication()->input;

		$args = array();
		$args['id_ser'] = $input->getUint('id_ser', 0);
		$args['id_emp'] = $input->getUint('id_emp', 0);
		$args['date'] 	= $input->getString('date', '');

		// get model
		$model = $this->getModel();
		// use model to create the timeline
		$timeline = $model->getTimeline($args);

		if (!$timeline)
		{
			// raise error message
			$error = $model->getError($index = null, $string = false);

			if ($error instanceof Exception)
			{
				UIErrorFactory::raiseError($error->getCode(), $error->getMessage());
			}
			else
			{
				UIErrorFactory::raiseError(500, $error);
			}
		}

		// set item ID as display data
		$args['itemid'] = $input->getUint('Itemid');

		// create timeline response
		$result = new stdClass;
		$result->html     = $timeline->display($args);
		$result->timeline = $timeline->getTimeline();

		// send timeline to caller
		$this->sendJSON($result);
	}
}
