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
 * Class used to keep a list of events within a calendar rectangle.
 *
 * @since 1.6
 */
class CalendarRect
{
	/**
	 * The starting date delimiter.
	 *
	 * @var JDate
	 */
	protected $start;

	/**
	 * The ending date delimiter.
	 *
	 * @var JDate
	 */
	protected $end;

	/**
	 * The events list.
	 *
	 * @var array
	 */
	protected $events;

	/**
	 * Class constructor.
	 *
	 * @param 	string  $start  The starting delimiter.
	 * @param  	string  $end    The ending delimiter.
	 * @param 	mixed   $event  The event(s) to push.
	 *
	 * @uses 	addEvent()
	 */
	public function __construct($start, $end, $event = null)
	{
		// ini bounds
		$this->setBounds($start, $end);

		$this->events = array();

		if ($event)
		{
			$this->addEvent($event);
		}
	}

	/**
	 * Pushes a new event within the internal list.
	 *
	 * @param 	object 	The event object.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function addEvent($event)
	{
		if (!is_array($event))
		{
			$event = array($event);
		}

		foreach ($event as $e)
		{
			$this->events[] = $e;
		}

		return $this;
	}

	/**
	 * Returns the starting delimiter.
	 *
	 * @param 	boolean  True to return a string, false
	 *                   to obtain a date object.
	 *
	 * @return 	mixed    Either a date object or a string.
	 */
	public function start($string = true)
	{
		if ($string)
		{
			return $this->start->format('Y-m-d H:i:s', true);
		}

		return $this->start;
	}

	/**
	 * Returns the hours of the starting delimiter.
	 *
	 * @return 	integer
	 */
	public function startH()
	{
		return (int) $this->start->format('H', true);
	}

	/**
	 * Returns the minutes of the starting delimiter.
	 *
	 * @return 	integer
	 */
	public function startM()
	{
		return (int) $this->start->format('i', true);
	}

	/**
	 * Returns the hour-min amount of the starting delimiter.
	 *
	 * @return 	integer
	 *
	 * @uses 	startH()
	 * @uses 	startM()
	 */
	public function startHM()
	{
		return $this->startH() * 60 + $this->startM();
	}

	/**
	 * Returns the ending delimiter.
	 *
	 * @param 	boolean  True to return a string, false
	 *                   to obtain a date object.
	 *
	 * @return 	mixed    Either a date object or a string.
	 */
	public function end($string = true)
	{
		if ($string)
		{
			return $this->end->format('Y-m-d H:i:s', true);
		}

		return $this->end;
	}

	/**
	 * Returns the hours of the ending delimiter.
	 *
	 * @return 	integer
	 */
	public function endH()
	{
		return (int) $this->end->format('H', true);
	}

	/**
	 * Returns the minutes of the ending delimiter.
	 *
	 * @return 	integer
	 */
	public function endM()
	{
		return (int) $this->end->format('i', true);
	}

	/**
	 * Returns the hour-min amount of the ending delimiter.
	 *
	 * @return 	integer
	 *
	 * @uses 	endH()
	 * @uses 	endM()
	 */
	public function endHM()
	{
		return $this->endH() * 60 + $this->endM();
	}

	/**
	 * Checks if the events are referring to the same day or not.
	 * This method return false in the following case:
	 * - start 	2018-07-13 @ 23:00
	 * - end 	2018-07-14 @ 02:00
	 *
	 * @return 	boolean  True if the delimiters are within the same day, false otherwise.
	 */
	public function isSameDay()
	{
		return $this->start->format('Ymd', true) == $this->end->format('Ymd', true);
	}

	/**
	 * Returns the events list.
	 *
	 * @return 	array
	 */
	public function events()
	{
		return $this->events;
	}

	/**
	 * Returns the first event or the given property (if specified) of the first event.
	 *
	 * @param 	string 	$key  The property to get. Null to return the whole object.
	 * @param  	mixed 	$def  The default value in case the property doesn't exist.
	 *
	 * @return 	mixed 	The event object or a specific property of the object.
	 */
	public function event($key = null, $def = null)
	{
		if (!$key)
		{
			return $this->events[0];
		}

		if (isset($this->events[0]->{$key}))
		{
			return $this->events[0]->{$key};
		}

		return $def;
	}

	/**
	 * Returns the number of events contained within the list
	 *
	 * @return 	integer
	 */
	public function getEventsCount()
	{
		return count($this->events);
	}

	/**
	 * Extends the bounds of this box according to the delimiters of the given event.
	 *
	 * @param 	integer  $start  The starting delimiter.
	 * @param  	integer  $end 	 The ending delimiter.
	 * @param 	mixed 	 $event  The event(s) to push.
	 *
	 * @return 	self 	 This object to support chaining.
	 *
	 * @uses 	addEvent() 	  
	 */
	public function extendBounds($start, $end, $event)
	{
		$min = min(array($this->start->format('Y-m-d H:i:s'), $start));
		$max = max(array($this->end->format('Y-m-d H:i:s'), $end));

		// update bounds
		$this->setBounds($min, $max);

		return $this->addEvent($event);
	}

	/**
	 * Manually updates the rect bounds.
	 *
	 * @param 	string  $start  The starting delimiter.
	 * @param  	string  $end    The ending delimiter.
	 *
	 * @return 	self    This object to support chaining.
	 */
	public function setBounds($start, $end)
	{
		$this->start = new JDate($start);
		$this->end 	 = new JDate($end);
		
		$tz = JFactory::getUser()->getTimezone();

		// adjust dates to current used timezone
		$this->start->setTimezone($tz);
		$this->end->setTimezone($tz);

		return $this;
	}

	/**
	 * Checks if there is an intersection between the rect and the given delimiters.
	 *
	 * @param 	integer  $start  The starting delimiter.
	 * @param  	integer  $end 	 The ending delimiter.
	 *
	 * @return 	boolean  True if it is, false otherwise.
	 */
	public function intersects($start_b, $end_b)
	{
		$start_a = $this->start->format('Y-m-d H:i:s');
		$end_a   = $this->end->format('Y-m-d H:i:s');

		// IN_A <= IN_B  AND IN_B  <  OUT_A
		// IN_A <  OUT_B AND OUT_B <= OUT_A
		// IN_B <  IN_A  AND OUT_A <  OUT_B
		return ($start_a <= $start_b && $start_b <  $end_a)
			|| ($start_a <  $end_b   && $end_b   <= $end_a)
			|| ($start_b <  $start_a && $end_a   <  $end_b);
	}

	/**
	 * Checks if the appointment starts at the specified hour.
	 * This method doesn't consider the minutes (e.g. 9:30 starts @ 9:00).
	 *
	 * @param 	integer  $hour  The hour to check.
	 *
	 * @return 	boolean  True if starts, false otherwise.
	 */
	public function startsAt($hour)
	{
		if ($this->startH() == $hour)
		{
			return true;
		}

		return false;
	}

	/**
	 * Checks if the appointment ends at the specified hour.
	 * This method doesn't consider the minutes (e.g. 9:30 starts @ 9:00).
	 *
	 * @param 	integer  $hour  The hour to check.
	 *
	 * @return 	boolean  True if ends, false otherwise.
	 */
	public function endsAt($hour)
	{
		if ($this->endHM() > $hour * 60 && $this->endHM() <= ($hour + 1) * 60)
		{
			return true;
		}

		return false;
	}

	/**
	 * Checks if the appointment is between the given hour and the next one.
	 * This method doesn't consider the minutes.
	 *
	 * @param 	integer  $hour  The hour to check.
	 *
	 * @return 	boolean  True if contained, false otherwise.
	 */
	public function containsAt($hour)
	{
		if ($this->startH() < $hour && $hour < $this->endH())
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the texts that is going to be displayed within
	 * the calendar block.
	 *
	 * @return 	string
	 * 
	 * @since 	1.7
	 */
	public function getDisplayData($use)
	{
		$data = array();

		// build rect data
		$data['id'] 	 	= array();
		$data['service']	= array();
		$data['employee']	= array();

		foreach ($this->events() as $e)
		{
			$data['id'][] = $e->id;

			if (!in_array($e->id_service, $data['service']))
			{
				$data['service'][]  = $e->id_service;
			}

			if (!in_array($e->id_employee, $data['employee']))
			{
				$data['employee'][]  = $e->id_employee;
			}
		}

		if (count($data['service']) > 1)
		{
			// use custom color for shared blocks
			$data['color'] = '00d498';
		}
		else
		{
			$data['color'] = $this->event('service_color');

			// The color is missing... Probably the service was
			// created before the colors were supported.
			if (!$data['color'])
			{
				// use a random color from the preset
				$data['color'] = ltrim(JHtml::fetch('vaphtml.color.preset'), '#');
			}
		}

		if ($use)
		{
			// first block, define event label
			$data['label'] = '';

			$count    = $this->getEventsCount();
			$customer = $count ? $this->event()->purchaser_nominative : null;

			if ($count == 1 && $customer)
			{
				// display the customer name
				$data['label'] .= '<div class="app-rect-customer">' . $customer . '</div>';
			}
			
			if (count($data['service']) == 1)
			{
				// Only one service or multiple appointments for the same service.
				// Use service name as label.
				$data['label'] .= '<div class="app-rect-service">' . $this->event('service_name') . '</div>';
			}

			if ($count > 1)
			{
				$data['label'] .= '<div class="app-rect-count">' . JText::sprintf('VAPCALNUMAPP', $count) . '</div>';
			}

			$dispatcher = VAPFactory::getEventDispatcher();

			/**
			 * Trigger event to let external plugins manipulate the text to display,
			 * the background color and additional data to introduce within the HTML rect.
			 *
			 * @param 	array  &$data  An associative array of display data.
			 * @param 	self   $rect   The object handling all the events.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			$dispatcher->trigger('onDisplayCalendarRect', array(&$data, $this));
		}
		else
		{
			$data['label'] = '';
		}

		// fetch background style
		$data['background'] = 'background-color: #' . $data['color']. ';';
		
		/**
		 * Check if we should use a darker color for text depending
		 * on the brightness of the background.
		 *
		 * @since 1.7
		 */
		if (JHtml::fetch('vaphtml.color.dark', $data['color']))
		{
			// we have a dark background, use a light color
			$data['background'] .= ' color: #fff;';
		}

		return $data;
	}
}
