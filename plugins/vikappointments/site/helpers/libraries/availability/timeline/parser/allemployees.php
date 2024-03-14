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

VAPLoader::import('libraries.availability.timeline.parser');

/**
 * Timeline parser for all the employees assigned to the requested service.
 *
 * @since 1.7
 */
class VAPAvailabilityTimelineParserAllemployees extends VAPAvailabilityTimelineParser
{
	/**
	 * Internal method used by children classes to implement their own logic
	 * to fetch the availability timeline.
	 *
	 * @param 	string 	 $date    The UTC date in military format.
	 * @param 	integer  $people  The number of participants.
	 * @param 	integer  $id      The selected appointment ID.
	 *
	 * @return 	mixed    The resulting timeline on success, false otherwise.
	 */
	protected function buildTimeline($date, $people = 1, $id = 0)
	{
		$id_service = (int) $this->search->get('id_service');

		// load all supported employees
		$employees = JModelVAP::getInstance('service')->getEmployees($id_service);

		$timelines = array();

		$error = null;

		// iterate employees
		foreach ($employees as $employee)
		{
			// temporarily set the employee
			$this->search->set('id_employee', (int) $employee->id);

			// get proper parser with updated searcher
			$parser = VAPAvailabilityTimelineFactory::getParser($this->search);

			// obtain timeline from parent
			$timeline = $parser->getTimeline($date, $people, $id);

			if ($timeline)
			{
				$tmp = new stdClass;
				$tmp->id       = $employee->id;
				$tmp->name     = $employee->nickname;
				$tmp->timeline = $timeline;

				// register timeline only if existing
				$timelines[] = $tmp;
			}
			else
			{
				// get error from parser
				$error = $parser->getError();
			}
		}

		// reset employee from search
		$this->search->set('id_employee', 0);

		// make sure all the times are not in the past
		if (!$timelines)
		{
			// use error returned by the parser
			$this->setError($error ? $error : JText::translate('VAPFINDRESNOLONGERAVAILABLE'));

			return false;
		}

		return $timelines;
	}
}
