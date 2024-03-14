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
 * Decorator used to optimize the ICS file downloaded through a different reader.
 * 
 * Here are listed all the supported configuration settings:
 * 
 * @var integer  filesize           The optimization will be performed only in case the
 *                                  size of the buffer exceeds the specified threshold.
 * @var integer  exclude_prev_days  Ignore all the events prior the current date minus
 *                                  the specified amount of days.
 * @var integer  exclude_next_days  Ignore all the events after the current date plus
 *                                  the specified amount of days.
 * 
 * @since 1.7.4
 */
final class VAPIcalReaderOptimizer implements VAPIcalReader
{
	/** @var  VAPIcalReader */
	protected $reader;

	/** @var JRegistry */
	protected $options;

	/**
	 * Class constructor.
	 * 
	 * @param 	VAPIcalReader  $reader   The reader implementor.
	 * @param   array          $options  A configuration array.
	 */
	public function __construct(VAPIcalReader $reader, array $options = [])
	{
		$this->reader  = $reader;
		$this->options = new JRegistry($options);
	}

	/**
	 * Loads the iCalendar through the attached reader and tries to reduce
	 * the file by excluding the events that do not match the specified query.
	 * 
	 * @return  string  The iCalendar string.
	 */
	public function load()
	{
		// obtain buffer
		$buffer = $this->reader->load();

		if (strlen($buffer) > (int) $this->options->get('filesize', 0))
		{
			// the length of the buffer exceeds the specified threshold,
			// we should proceed with the optimization of the calendar
			$buffer = $this->optimize($buffer);
		}

		return $buffer;
	}

	/**
	 * Attempts to optimize the received calendar buffer.
	 * 
	 * @param   string  $buffer  The iCalendar string.
	 * 
	 * @return  string  The optimized iCalendar string.
	 */
	protected function optimize(string $buffer)
	{
		// normalize all end of lines
		$buffer = preg_replace("/\R/", PHP_EOL, $buffer);

		// keep the heading of the calendar
		$ics = substr($buffer, 0, $offset = strpos($buffer, 'BEGIN:VEVENT'));

		// define the left threshold to exclude all the previous events
		$beforeThreshold = $this->options->get('exclude_prev_days', null);

		if (!is_null($beforeThreshold))
		{
			$beforeThreshold = JFactory::getDate('-' . (int) $beforeThreshold . ' days');
		}

		// define the right threshold to exclude all the next events
		$afterThreshold = $this->options->get('exclude_next_days', null);

		if (!is_null($afterThreshold))
		{
			$afterThreshold = JFactory::getDate('+' . (int) $beforeThreshold . ' days');
		}

		// iterate as long as we can find an event
		while (($next = strpos($buffer, 'BEGIN:VEVENT', $offset + 1)) !== false)
		{
			// obtain the whole event declaration
			$event = substr($buffer, $offset, $next - $offset);

			// update offset with the starting position of the next event
			$offset = $next;

			// detect event date start
			if (preg_match("/DTSTART(.*?):(.*?)\R/", $event, $match))
			{
				try
				{
					// try to instantiate a date time
					$dt = JFactory::getDate($match[2]);

					if ((!$beforeThreshold || $beforeThreshold <= $dt) && (!$afterThreshold || $dt <= $afterThreshold))
					{
						// event compliant, append it within the optimized calendar
						$ics .= $event;
					}
				}
				catch (Exception $e) {
					// invalid date time, ignore event
				}
			}
		}

		// Copy all the remaining characters.
		// NOTE: the last event will never be checked against the specified filters, because the loop
		// always stops while reaching the starting declaration of the latter. Anyway, we don't have
		// to worry about as a single event cannot compromise the whole loading process.
		$ics .= substr($buffer, $offset);

		return $ics;
	}
}
