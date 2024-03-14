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

VAPLoader::import('libraries.availability.search');
VAPLoader::import('libraries.availability.timeline.block');

/**
 * Timeline wrapper.
 *
 * @since 1.7
 */
class VAPAvailabilityTimeline implements IteratorAggregate, JsonSerializable
{
	/**
	 * The check-in date expressed in military format.
	 *
	 * @var string
	 */
	protected $date;

	/**
	 * An availability search instance
	 *
	 * @var VAPAvailabilitySearch
	 */
	protected $search;

	/**
	 * The system/employee timezone.
	 *
	 * @var DateTimeZone
	 */
	protected $employeeTimezone;

	/**
	 * An array of times.
	 *
	 * @var array
	 */
	protected $timeline = array();

	/**
	 * Class constructor.
	 *
	 * @param 	string 	               $date    The check-in date (military format).
	 * @param 	VAPAvailabilitySearch  $search  The search instance.
	 */
	public function __construct($date, VAPAvailabilitySearch $search)
	{
		$this->date   = $date;
		$this->search = $search;

		// get employee model to access helper methods
		$model = JModelVAP::getInstance('employee');
		// Recover default employee timezone. In case the employee is
		// not set, the default system timezone will be used.
		$tz = $model->getTimezone($this->search->get('id_employee'));

		if ($tz)
		{
			// register employee timezone
			$this->employeeTimezone = new DateTimeZone($tz);
		}
	}

	/**
	 * Adds a new timeline level.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function addLevel()
	{
		// make sure the last added timeline is not empty
		if (!$this->timeline || $this->timeline[count($this->timeline) - 1])
		{
			// add timeline level
			$this->timeline[] = array();
		}

		return $this;
	}

	/**
	 * Creates the timeline starting from the specified array.
	 *
	 * @param 	array  $timeline  An associative array containing the time (as key) 
	 *                            and the availability status (as value).
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setTimes(array $timeline)
	{
		// reset timeline
		$this->timeline = array();

		foreach ($timeline as $times)
		{
			// add level to timeline
			$this->addLevel();

			// add times one by one
			foreach ($times as $time => $status)
			{
				$this->addTime($time, $status);
			}
		}

		return $this;
	}

	/**
	 * Registers a new timeline block.
	 *
	 * @param 	mixed    $time       Either a time string or a time expressed in minutes.
	 * @param 	integer  $status     The availability status.
	 * @param 	integer  $duration   The time slot duration (in minutes).
	 * @param 	float    $price      An optional time slot price.
	 * @param 	array    $occupancy  An optional occupancy array.
	 *
	 * @return 	VAPAvailabilityTimelineBlock  The newly created time block.
	 */
	public function addTime($time, $status, $duration = null, $price = null, $occupancy = null)
	{
		if (is_numeric($time))
		{
			// convert minutes to time string
			$time = JHtml::fetch('vikappointments.min2time', $time, $string = true, $format = 'H:i:s');
		}

		if (!$duration)
		{
			// recover duration from search
			$model = JModelVAP::getInstance('serempassoc');
			$override = $model->getOverrides($this->search->get('id_service'), $this->search->get('id_employee'));

			if ($override)
			{
				$duration = $override->duration;
			}
		}

		// create date instance coming from the timezone of the employee
		$dt = new JDate($this->date . ' ' . $time, $this->employeeTimezone);

		// create time block
		$block = new VAPAvailabilityTimelineBlock($dt, $status, $duration, $price, $occupancy);

		if (!$this->timeline)
		{
			// create first level
			$this->addLevel();
		}

		// register block within the last timeline level
		$this->timeline[count($this->timeline) - 1][] = $block;

		return $block;
	}

	/**
	 * Checks whether the timeline as at least a level
	 * with at least a time block.
	 *
	 * @return 	boolean
	 */
	public function hasTimes()
	{
		return $this->timeline && $this->timeline[0];
	}

	/**
	 * Returns the timeline searcher.
	 *
	 * @return 	VAPAvailabilitySearch
	 */
	public function getSearch()
	{
		return $this->search;
	}

	/**
	 * Returns the date of the timeline, adjusted to the local timezone
	 * of the employee.
	 *
	 * @return 	string
	 */
	public function getDate()
	{
		return JDate::getInstance($this->date, $this->employeeTimezone)
			->format('Y-m-d', $local = true);
	}

	/**
	 * Returns the timeline as a source array.
	 *
	 * @param 	boolean  $flatten     Whether to include the levels or not.
	 * @param 	boolean  $statusOnly  True to register only the time status, false to
	 *                                take all the details of the time block as array.
	 *
	 * @return 	array
	 */
	public function toArray($flatten = false, $statusOnly = true)
	{
		$arr = array();

		// iterate levels
		foreach ($this->timeline as $level)
		{
			if (!$flatten)
			{
				// include level
				$arr[] = array();
			}

			// iterate level times
			foreach ($level as $time)
			{
				// extract time
				$hm = $time->checkin('H:i');
				// convert time to minutes
				$hm = JHtml::fetch('vikappointments.time2min', $hm);

				if ($statusOnly)
				{
					$value = $time->status;
				}
				else
				{
					$value = $time->toArray();
				}

				if ($flatten)
				{
					// register time slot at the same level
					$arr[$hm] = $value;
				}
				else
				{
					// register time slot at the current level
					$arr[count($arr) - 1][$hm] = $value;
				}
			}
		}

		return $arr;
	}

	/**
	 * Creates an array iterator for ease of use.
	 *
	 * @return 	ArrayIterator
	 *
	 * @since 	1.7
	 */
	#[ReturnTypeWillChange]
	public function getIterator()
    {
        return new ArrayIterator($this->timeline);
    }

    /**
	 * Creates a standard object, containing all the supported properties,
	 * to be used when this class is passed to "json_encode()".
	 *
	 * @return  object
	 *
	 * @see     JsonSerializable
	 */
    #[ReturnTypeWillChange]
	public function jsonSerialize()
	{
		return $this->timeline;
	}
}
