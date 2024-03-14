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

/**
 * The iCalendar parser is based on the integration provided by Jonathan Goode.
 * 
 * @link https://github.com/u01jmg3/ics-parser
 * 
 * @since 1.7.3
 */
class VAPIcalParserV1 extends VAPIcalParser
{
	/**
	 * Implements the algorithm used to parse the iCalendar buffer.
	 * 
	 * @param 	string  $buffer
	 * 
	 * @return 	array
	 */
	protected function parseBuffer($buffer)
	{
		// define default timezone
		$this->options->def('timezone', JFactory::getApplication()->get('offset', 'UTC'));

		// init calendar parser
		$cal = new \ICal\ICal($buffer, [
			// inject default timezone
			'defaultTimeZone' => $this->options->get('timezone'),
			// ignore all the events prior the current date minus the specified amount
			'filterDaysBefore' => $this->options->get('exclude_prev_days', null),
			// ignore all the events after the current date plus the specified amount
			'filterDaysAfter' => $this->options->get('exclude_next_days', null),
		]);

		$tz = new DateTimeZone($this->options->get('timezone'));

		$list = [];

		foreach ($cal->events() as $event)
		{
			// convert dates and times in UTC
			$start = JFactory::getDate($event->dtstart_tz, $tz);
			$end   = JFactory::getDate($event->dtend_tz, $tz);

			// fetch the creation date from the most appropriate property 
			$created = $event->created ? $event->created : $event->dtstamp;

			// build event wrapper
			$list[] = new VAPIcalEvent([
				'uid'         => $event->uid,
				'start'       => $start->toISO8601(),
				'end'         => $end->toISO8601(),
				'created'     => $created ? JFactory::getDate($created)->toISO8601() : null,
				'modified'    => $event->last_modified ? JFactory::getDate($event->last_modified)->toISO8601() : null,
				'summary'     => $event->summary,
				'description' => $event->description,
				'location'    => $event->location,
				'organizer'   => $event->organizer,
				'attendee'    => $event->attendee,
			]);
		}

		return $list;
	}
}
