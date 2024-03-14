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
 * VikAppointments find reservation model.
 *
 * @since 1.7
 */
class VikAppointmentsModelFindreservation extends JModelVAP
{
	/**
	 * Returns the structure of the calendar to display
	 * as tables of months.
	 *
	 * @param 	array 	$options  An array of options.
	 *
	 * @return 	object 	The resulting calendar.
	 */
	public function getCalendar(array $options = array())
	{
		/**
		 * Back to the first day of the month.
		 * Do not use "first day of" modifier because PHP 7.3
		 * seems to experience some strange behaviors.
		 *
		 * @link https://stackoverflow.com/q/2094797
		 */
		$options['start'] = JDate::getInstance()->format('Y-m-01');

		// proxy for calendar model
		return JModelVAP::getInstance('calendar')->getCalendar($options);
	}

	/**
	 * Calculates the resulting availability timeline according
	 * to the specified search options.
	 *
	 * @param 	array 	$options  An array of options.
	 *
	 * @return 	mixed 	The resulting renderer.
	 */
	public function getTimeline($options)
	{
		// proxy for calendar model
		return JModelVAP::getInstance('calendar')->getTimeline($options);
	}
}
