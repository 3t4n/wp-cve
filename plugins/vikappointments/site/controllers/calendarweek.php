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
 * VikAppointments weekly calendar controller.
 *
 * @since 	1.7
 */
class VikAppointmentsControllerCalendarweek extends VAPControllerAdmin
{
	/**
	 * AJAX task used to return the availability table of a specific service/employee.
	 *
	 * This method expects the following parameters to be sent via POST or GET.
	 *
	 * @param 	integer  id_emp  The employee ID.
	 * @param 	integer  id_ser  The service ID.
	 * @param 	string   day     The check-in date.
	 *
	 * @return 	void
	 */
	public function availtableajax()
	{
		$input = JFactory::getApplication()->input;

		$args = array();
		$args['id_emp']    = $input->getUint('id_emp', 0);
		$args['id_ser']    = $input->getUint('id_ser', 0);
		$args['start'] 	   = $input->getString('day', '');
		$args['people']    = $input->getUint('people', null);
		$args['locations'] = $input->getUint('locations', null);

		$args['layout'] = 'weekly';

		// get model
		$model = $this->getModel('employeesearch');
		// use model to create the timeline
		$calendar = $model->getCalendar($args);

		// prepare layout data
		$data = array(
			'calendar'    => $calendar,
			'id_service'  => $args['id_ser'],
			'id_employee' => $args['id_emp'],
		);

		// render layout
		$html = JLayoutHelper::render('blocks.calendar.weekly', $data);

		// send calendar to caller
		$this->sendJSON(json_encode($html));
	}
}
