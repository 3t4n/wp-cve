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
 * Encapsulates the information of an iCalendar event.
 * 
 * @since 1.7.3
 */
class VAPIcalEvent
{
	/**
	 * The event unique ID.
	 * 
	 * @var string|null
	 */
	protected $uid;

	/**
	 * The event start date (UTC).
	 * 
	 * @var string|null
	 */
	protected $start;

	/**
	 * The event start date (UTC).
	 * 
	 * @var string|null
	 */
	protected $end;

	/**
	 * The appointment duration, in seconds.
	 * 
	 * @var integer
	 */
	protected $duration;

	/**
	 * The event creation date (UTC).
	 * 
	 * @var string|null
	 */
	protected $created;

	/**
	 * The event last modify date (UTC).
	 * 
	 * @var string|null
	 */
	protected $modified;

	/**
	 * The event summary (title).
	 * 
	 * @var string|null
	 */
	protected $summary;

	/**
	 * The event long description.
	 * 
	 * @var string|null
	 */
	protected $description;

	/**
	 * The event location.
	 * 
	 * @var string|null
	 */
	protected $location;

	/**
	 * The event organizer.
	 * 
	 * @var string|null
	 */
	protected $organizer;

	/**
	 * The list of attendees, separated by a comma.
	 * 
	 * @var string|null
	 */
	protected $attendee;

	/**
	 * Class constructor.
	 * 
	 * @param 	array|object  $data  The event data to bind.
	 */
	public function __construct($data)
	{
		if (!is_array($data) && !is_object($data))
		{
			// invalid argument
			throw new InvalidArgumentException('Invalid iCal event data', 400);
		}

		// iterate all attributes/properties
		foreach ($data as $k => $v)
		{
			// set only in case the property has been explicitly declared by the class
			if (property_exists($this, $k))
			{
				$this->{$k} = $v;
			}
		}
	}

	/**
	 * Proxy used to easily access the internal properties of the class without
	 * allowing their manipulation.
	 * 
	 * @param 	string  $name  The property name.
	 * 
	 * @return 	mixed   The property value.
	 * 
	 * @throws 	UnexpectedValueException
	 */
	public function __get($name)
	{
		$getter = 'get' . ucfirst($name);

		// check whether we have an apposite getter to invoke
		if (method_exists($this, $getter))
		{
			return $this->{$getter}();
		}

		// check whether the property exists
		if (property_exists($this, $name))
		{
			return $this->{$name};
		}

		// invalid property, raise error
		throw new UnexpectedValueException(sprintf('Property [%s] not declared', $name), 500);
	}

	/**
	 * Returns the duration of the event in seconds.
	 * 
	 * @return 	integer
	 */
	public function getDuration()
	{
		if (is_null($this->duration))
		{
			// calculate duration only once
			$this->duration = VAPDateHelper::diff($this->start, $this->end, 'seconds');
		}

		return $this->duration;
	}

	/**
	 * Returns the last modify date.
	 * 
	 * @return 	string
	 */
	public function getLastModify()
	{
		$dates = [];

		if ($this->created)
		{
			$dates[] = JFactory::getDate($this->created)->toISO8601();
		}

		if ($this->modified)
		{
			$dates[] = JFactory::getDate($this->modified)->toISO8601();
		}

		if ($dates)
		{
			return max($dates);
		}

		return 0;
	}

	/**
	 * Returns the organizer e-mail, if any.
	 * 
	 * @return 	string
	 */
	public function getOrganizer()
	{
		// strip initial "mailto:" occurrence
		return preg_replace("/^mailto:/i", '', (string) $this->organizer);
	}

	/**
	 * Returns the attendee e-mails string.
	 * 
	 * @return 	string
	 */
	public function getAttendee()
	{
		// strip all "mailto:" occurrences
		return preg_replace("/(^|,\s*)mailto:/i", '$1', (string) $this->attendee);
	}

	/**
	 * Returns the list of attendees.
	 * 
	 * @param 	boolean  $organizer  True to include the organizer in the list.
	 * 
	 * @return 	array    A list of attendees.
	 */
	public function getAttendeesList($organizer = false)
	{
		$org_mail = $this->getOrganizer();

		// no specified attendees
		if (!$this->attendee)
		{
			if ($organizer && $org_mail)
			{
				// include the organizer in the list
				return [$org_mail];
			}

			// organizer not specified or exlcuded
			return [];
		}

		// explode e-mails in attendees list
		$attendees = preg_split("/\s*,\s*/", $this->getAttendee());

		// ignore empty values and the e-mail of the organizer
		$attendees = array_values(array_filter($attendees, function($email) use ($org_mail)
		{
			return $email && $email != $org_mail;
		}));

		// in case the organizer is set and should be included, re-add it at the beginning
		if ($organizer && $org_mail)
		{
			array_unshift($attendees, $org_mail);
		}

		return $attendees;
	}

	/**
	 * Magic method used to represent the event as a string.
	 * 
	 * @return 	string
	 */
	public function __toString()
	{
		// extract identifier
		$str = $this->summary ? $this->summary : $this->uid;
		
		if ($str)
		{
			$str .= ' (' . $this->start . ')';
		}
		else
		{
			$str = $this->start;
		}

		return $str;
	}
}
