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
 * Driver class used to export the orders/appointments in ICS format.
 *
 * @since 1.7  Use VAPOrderExportDriverIcs instead.
 */
class VAPOrderExportDriverIcs extends VAPOrderExportDriver
{
	/**
	 * ICS declarations buffer.
	 *
	 * @var string
	 */
	private $ics;

	/**
	 * A list of custom fields.
	 *
	 * @var array
	 */
	private $customFields;

	/**
	 * Checks whether the specified group is supported by the
	 * export driver. Children classes can override this method
	 * to drop the support for a specific group.
	 *
	 * @param 	string 	 $group  The group to check.
	 *
	 * @return 	boolean  True if supported, false otherwise.
	 */
	public function isSupported($group)
	{
		// only appointments are supported here
		return $this->isGroup('appointment');
	}

	/**
	 * @override
	 * Builds the form parameters required to the ICS driver.
	 *
	 * @return 	array
	 */
	protected function buildForm()
	{
		return array(
			/**
			 * An optional subject to be used instead of the
			 * default one.
			 *
			 * @var text
			 */
			'subject' => array(
				'type'    => 'text',
				'label'   => JText::translate('VAP_EXPORT_DRIVER_ICS_SUBJECT_FIELD'),
				'help'    => JText::translate('VAP_EXPORT_DRIVER_ICS_SUBJECT_FIELD_HELP'),
			),

			/**
			 * Include past events.
			 * If disabled, reservations older than the current
			 * month won't have to be included.
			 *
			 * @var checkbox
			 */
			'pastevents' => array(
				'type'    => 'checkbox',
				'label'   => JText::translate('VAP_EXPORT_DRIVER_ICS_PAST_DATES_FIELD'),
				'help'    => JText::translate('VAP_EXPORT_DRIVER_ICS_PAST_DATES_FIELD_HELP'),
				'default' => true,
			),

			/**
			 * Events default reminder.
			 * The minutes in advance since the event date time
			 * for which the alert will be triggered.
			 *
			 * @var select
			 */
			'reminder' => array(
				'type'    => 'select',
				'label'   => JText::translate('VAP_EXPORT_DRIVER_ICS_REMINDER_FIELD'),
				'help'    => JText::translate('VAP_EXPORT_DRIVER_ICS_REMINDER_FIELD_HELP'),
				'default' => -1,
				'options' => array(
					-1  => JText::translate('VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_NONE'),
					0   => JText::translate('VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_EVENT_TIME'),
					5   => JText::sprintf('VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_MIN', 5),
					10  => JText::sprintf('VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_MIN', 10),
					15  => JText::sprintf('VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_MIN', 15),
					30  => JText::sprintf('VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_MIN', 30),
					60  => JText::plural('VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_HOURS', 1),
					120 => JText::plural('VAP_EXPORT_DRIVER_ICS_REMINDER_OPT_N_HOURS', 2),
				),
			),
		);
	}

	/**
	 * @override
	 * Exports the orders in the given format.
	 *
	 * @return 	string 	The resulting export string.
	 */
	public function export()
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		// init buffer
		$this->ics = '';

		// load custom fields
		VAPLoader::import('libraries.customfields.loader');
		$this->customFields = VAPCustomFieldsLoader::getInstance()
			->noRequiredCheckbox()
			->noInputFile()
			->noSeparator()
			->translate()
			->fetch();

		/**
		 * Starts the calendar declaration.
		 * 
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-4-icalendar-object.html
		 */
		$this->addLine('BEGIN', 'VCALENDAR');

		// create ICS header
		$this->createHeader();

		/**
		 * Trigger event to allow the plugins to include custom options before
		 * the body of the ICS file.
		 *
		 * @param 	mixed 	 $handler  The current handler instance.
		 *
		 * @return 	string   The rules to include.
		 *
		 * @since 	1.6.6
		 */
		$res = $dispatcher->trigger('onBuildBodyExportICS', array($this));

		// include the custom rules before the body
		$this->ics .= implode('', array_filter($res));

		// iterate records to export
		foreach ($this->getRecords() as $event)
		{
			// use registry for ease of use
			$event = new JRegistry($event);

			// add event properties
			$this->addEvent($event);
		}

		/**
		 * Closes the calendar
		 *
		 * @see BEGIN:VCALENDAR
		 */
		$this->addLine('END', 'VCALENDAR');

		// return generated buffer
		return $this->ics;
	}

	/**
	 * @override
	 * Downloads the orders in a file compatible with the given format.
	 *
	 * @param 	string 	$filename 	The name of the file that will be downloaded.
	 *
	 * @return 	void
	 *
	 * @uses 	export()
	 */
	public function download($filename = null)
	{
		// obtain export string
		$buffer = $this->export();

		if ($filename)
		{
			// strip file extension
			$filename = preg_replace("/\.ics$/i", '', $filename);
		}
		else
		{
			// use current date time as name
			$filename = JHtml::fetch('date', 'now', 'Y-m-d H_i_s');
		}

		$app = JFactory::getApplication();

		// declare headers
		$app->setHeader('Content-Type', 'text/calendar; charset=utf-8');
		$app->setHeader('Content-Disposition', 'attachment; filename=' . $filename . '.ics');
		$app->setHeader('Content-Length', strlen($buffer));
		$app->setHeader('Cache-Control', 'no-store, no-cache');

		// send headers
		$app->sendHeaders();
		
		// output buffer for download
		echo $buffer;
	}

	/**
	 * Returns the list of records to export.
	 *
	 * @return 	array 	A list of records.
	 */
	protected function getRecords()
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		// select all reservation columns
		$q->select('r.*');
		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));

		// select service name
		$q->select($dbo->qn('s.name', 'service_name'));
		$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'));

		// select employee name and timezone
		$q->select($dbo->qn('e.nickname', 'employee_name'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'));

		// exclude all parent orders
		$q->where($dbo->qn('r.id_parent') . ' > 0');
		// exclude all closures
		$q->where($dbo->qn('r.closure') . ' = 0');

		// get approved statuses
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1));

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		// include records with check-in equals or higher than 
		// the specified starting date
		$from = $this->getOption('fromdate');

		if (!VAPDateHelper::isNull($from))
		{
			$q->where($dbo->qn('r.checkin_ts') . ' >= ' . $dbo->q($from));
		}

		// include records with check-in equals or lower than 
		// the specified ending date
		$to = $this->getOption('todate');

		if (!VAPDateHelper::isNull($to))
		{
			$q->where($dbo->qn('r.checkin_ts') . ' <= ' . $dbo->q($to));
		}

		// retrieve only the selected records, if any
		$ids = $this->getOption('cid');

		if ($ids)
		{
			/**
			 * The export system is now able to fetch also the appointments assigned to a parent order.
			 * 
			 * @since 1.7.4
			 */
			$q->andWhere([
				$dbo->qn('r.id') . ' IN (' . implode(',', array_map('intval', $ids)) . ')',
				$dbo->qn('r.id_parent') . ' IN (' . implode(',', array_map('intval', $ids)) . ')',
			], 'OR');
		}

		// retrieve employee filter, if any
		$id_emp = (int) $this->getOption('id_employee');

		if ($id_emp > 0)
		{
			$q->where($dbo->qn('r.id_employee') . ' = ' . $id_emp);
		}

		// check whether the past events should be excluded
		if (!$this->getOption('pastevents'))
		{
			// get current date at midnight
			$date = new JDate('today 00:00:00', JFactory::getUser()->getTimezone());
			// Back to the first day of the month.
			// Do not use "first day of" modifier because PHP 7.3
			// seems to experience some strange behaviors.
			$date->modify($date->format('Y-m-01'));

			$q->where($dbo->qn('r.checkin_ts') . ' >= ' . $dbo->q($date->toSql()));
		}

		/**
		 * Check whether the imported events should be excluded or not.
		 * 
		 * @since 1.7.3
		 */
		if ($this->getOption('imported', true) === false)
		{
			$q->andWhere([
				$dbo->qn('r.icaluid') . ' IS NULL',
				$dbo->qn('r.icaluid') . ' = ' . $dbo->q(''),
			], 'OR');
		}

		// order by ascending checkin
		$q->order($dbo->qn('r.checkin_ts') . ' ASC');

		/**
		 * Trigger event to allow the plugins to manipulate the query used to retrieve
		 * a standard list of records.
		 *
		 * @param 	mixed  &$query 	 The query string or a query builder object.
		 * @param 	mixed  $options  A configuration registry.
		 *
		 * @return 	void
		 *
		 * @since 	1.6.6
		 */
		$dispatcher->trigger('onBeforeListQueryExportICS', array(&$q, $this->options));

		$dbo->setQuery($q);
		return $dbo->loadObjectList();
	}

	/**
	 * Creates the header of the calendar.
	 *
	 * @return 	void
	 */
	protected function createHeader()
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		// set up default head information
		$head = array(
			'version'  => '2.0',
			'prodid'   => '-//e4j//VikAppointments ' . VIKAPPOINTMENTS_SOFTWARE_VERSION . '//EN',
			'calscale' => 'GREGORIAN',
			'calname'  => VAPFactory::getConfig()->get('agencyname'),
		);

		/**
		 * Trigger event to allow the plugins to include custom options within the
		 * head of the ICS file.
		 *
		 * @param 	array 	 &$head 	The default head data.
		 * @param 	mixed 	 $handler 	The current handler instance.
		 *
		 * @return 	string   The rules to include.
		 *
		 * @since 	1.6.6
		 * @since   1.7      $head is now an array and it is passed by reference.
		 */
		$res = $dispatcher->trigger('onBuildHeadExportICS', array(&$head, $this));

		/**
		 * This property specifies the identifier corresponding to the highest version number
		 * or the minimum and maximum range of the iCalendar specification that is required
		 * in order to interpret the iCalendar object.
		 * 
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-7-4-version.html
		 */
		$this->addLine('VERSION', $head['version']);

		/**
		 * This property specifies the identifier for the product that created the iCalendar object.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-7-3-product-identifier.html
		 */
		$this->addLine('PRODID', $head['prodid']);

		/**
		 * This property defines the calendar scale used for the calendar information
		 * specified in the iCalendar object.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-7-1-calendar-scale.html
		 */
		$this->addLine('CALSCALE', $head['calscale']);

		/**
		 * This non standard property defines the default name that will be used
		 * when creating a new subscription.
		 *
		 * @since 1.6.5
		 */
		$this->addLine('X-WR-CALNAME', $head['calname']);

		// $this->addLine('X-WR-TIMEZONE', JFactory::getApplication()->get('offset', 'UTC'));

		// append also the values that have been returned by the plugins
		$this->ics .= implode('', array_filter($res));
	}

	/**
	 * Adds an appointment as event within the calendar.
	 *
	 * @param 	JRegistry 	$event  The event to include.
	 *
	 * @return 	void
	 */
	protected function addEvent($event)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$config = VAPFactory::getConfig();
		$vik    = VAPApplication::getInstance();

		// fetch URI
		$uri = 'index.php?option=com_vikappointments&view=order&ordnum=' . $event->get('id') . '&ordkey=' . $event->get('sid');
		$uri = $vik->routeForExternalUse($uri);

		// fetch summary
		$summary = $this->getOption('subject');

		// retrieve customer name
		$customer = $event->get('purchaser_nominative');

		if (!$summary)
		{
			// use default summary built as "service" for customer or
			// "service - customer" for administrator
			$summary = '{service}';
			
			// sets a different title for the administrator
			if ($this->getOption('admin') && $customer)
			{
				$summary .= ' - {customer}';
			}
		}

		if (!$customer)
		{
			// use e-mail in case the name is missing
			$customer = $event->get('purchaser_mail');
		}

		if (!$customer)
		{
			// fallback to "Guest"
			$customer = 'Guest';
		}

		// retrieve service name
		$service = $event->get('service_name');

		// retrieve people
		$people = (int) $event->get('people', 0);

		// replace tags with reservation values
		$summary = preg_replace("/{customer}/", $customer, $summary);
		$summary = preg_replace("/{service}/", $service, $summary);
		$summary = preg_replace("/{people}/", $people, $summary);

		// fetch modified date
		$modified = max(array($event->get('createdon'), $event->get('modifiedon')));

		$description = '';

		// fetch description
		if ($this->getOption('admin'))
		{
			// decode custom fields and translate values
			$cf = (array) json_decode($event->get('custom_f', '{}'), true);
			$cf = VAPCustomFieldsLoader::translateObject($cf, $this->customFields);

			// create description containing the user custom fields
			foreach ($this->customFields as $field)
			{
				$k = $field['name'];

				if (!array_key_exists($k, $cf))
				{
					// field not found inside the given object, go to next one
					continue;
				}

				$v = $cf[$k];

				// take only if the value is not empty
				if ((is_scalar($v) && strlen($v)) || !empty($v))
				{
					// add colon as separator only in case the label doesn't
					// end with a punctuation
					if (preg_match("/[.,:;?!_\-]$/", $field['langname']))
					{
						// ends with a punctuation, do not use separator
						$sep = '';
					}
					else
					{
						$sep = ':';
					}

					// get a more readable label/text of the saved value
					$description .= $field['langname'] . $sep . ' ' . preg_replace("/\R/", "\\n", $v) . "\\n";
				}
			}
		}
		else
		{
			// no description for customer
		}

		/**
		 * @todo Include location once the database will support a FK to quickly access the
		 *       details of the appointment. Otherwise we risk to slow down the whole process.
		 */

		// build EVENT
		$data = array(
			'dtend'       => VikAppointments::getCheckout($event->get('checkin_ts'), $event->get('duration')),
			'uid'         => $event->get('icaluid'),
			'dtstamp'     => $event->get('createdon'),
			'location'    => $config->get('agencyname'),
			'description' => $description,
			'url'         => $uri,
			'summary'     => $summary,
			'dtstart'     => $event->get('checkin_ts'),
			'modified'    => $modified,
			'attendees'   => array(),
		);

		if (!$data['uid'])
		{
			// iCal UID not found, generate a new one
			$data['uid'] = md5($event->get('id') . '-' . $event->get('sid'));
		}

		// get attendees and decode them
		$attendees = $event->get('attendees');
		$attendees = $attendees ? (array) json_decode($attendees, true) : [];

		// inject customer details within attendee data
		array_unshift($attendees, array(
			'purchaser_nominative' => $event->get('purchaser_nominative'),
			'purchaser_mail'       => $event->get('purchaser_mail'),
		));

		// build attendees ICS data
		foreach ($attendees as $attendee)
		{
			if (empty($attendee['purchaser_mail']))
			{
				// go ahead, missing attendee e-mail
				continue;
			}

			// register attendee structure
			$data['attendees'][] = array(
				'key' => array(
					'ATTENDEE',
					'CN=' . $attendee['purchaser_nominative'],
					'CUTYPE=INDIVIDUAL',
					'EMAIL=' . $attendee['purchaser_mail'],
				),
				'value' => 'mailto:' . $attendee['purchaser_mail'],
			);
 		}

		/**
		 * Provide a grouping of component properties that describe an event.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-6-1-event-component.html
		 */
		$this->addLine('BEGIN', 'VEVENT');

		/**
		 * Trigger event to allow the plugins to manipulate the event
		 * details before being included.
		 *
		 * @param 	array   &$event   The event data.
		 * @param 	mixed   $record   The database record.
		 * @param 	mixed   $handler  The current handler instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.6.6
		 * @since   1.7     $record is now a registry.
		 */
		$dispatcher->trigger('onBeforeBuildEventICS', array(&$data, $event, $this));

		/**
		 * This property specifies the persistent, globally unique identifier for the
		 * iCalendar object. This can be used, for example, to identify duplicate calendar
		 * streams that a client may have been given access to.
		 *
		 * Generate a md5 string of the order number because "UID" values MUST NOT include any 
		 * data that might identify a user, host, domain, or any other private sensitive information.
		 *
		 * @link https://icalendar.org/New-Properties-for-iCalendar-RFC-7986/5-3-uid-property.html
		 */
		$this->addLine('UID', $data['uid']);

		/**
		 * This property specifies when the calendar component begins.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-2-4-date-time-start.html
		 */
		$this->addLine(
			array('DTSTART', 'VALUE=DATE-TIME'),
			$this->tsToCal($data['dtstart'])
		);

		/**
		 * This property specifies the date and time that a calendar component ends.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-2-2-date-time-end.html
		 */
		$this->addLine(
			array('DTEND', 'VALUE=DATE-TIME'),
			$this->tsToCal($data['dtend'])
		);

		/**
		 * In the case of an iCalendar object that specifies a "METHOD" property, this property
		 * specifies the date and time that the instance of the iCalendar object was created.
		 * In the case of an iCalendar object that doesn't specify a "METHOD" property, this
		 * property specifies the date and time that the information associated with the calendar
		 * component was last revised in the calendar store.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-7-2-date-time-stamp.html
		 */
		$this->addLine('DTSTAMP', $this->tsToCal($data['dtstamp']));

		/**
		 * In case an event is modified through a client, it updates the Last-Modified property to the
		 * current time. When the calendar is going to refresh an event, in case the Last-Modified is
		 * not specified or it is lower than the current one, the changes will be discarded.
		 * For this reason, it is needed to specify our internal modified date in order to refresh
		 * any existing events with the updated details.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-7-3-last-modified.html
		 */
		$this->addLine('LAST-MODIFIED', $this->tsToCal($data['modified']));

		/**
		 * This property may be used to convey a location where a more dynamic
		 * rendition of the calendar information can be found.
		 *
		 * @link https://icalendar.org/New-Properties-for-iCalendar-RFC-7986/5-5-url-property.html
		 */
		$this->addLine(array('URL', 'VALUE=URI'), $data['url']);

		/**
		 * This property defines a short summary or subject for the calendar component.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-1-12-summary.html
		 */
		$this->addLine('SUMMARY', $this->escape($data['summary']));
		
		/**
		 * This property provides a more complete description of the calendar component
		 * than that provided by the "SUMMARY" property.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-1-5-description.html
		 */
		if ($data['description'])
		{
			$this->addLine('DESCRIPTION', $this->escape($data['description']));
		}

		/**
		 * This property defines the intended venue for the activity defined by a calendar component.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-1-7-location.html
		 */
		$this->addLine('LOCATION', $this->escape($data['location']));

		/**
		 * This property defines whether or not an event is transparent to busy time searches.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-2-7-time-transparency.html
		 */
		$this->addLine('TRANSP', 'OPAQUE');

		// iterate all attendees
		foreach ($data['attendees'] as $attendee)
		{
			/**
			 * This property defines an "Attendee" within a calendar component.
			 *
			 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-4-1-attendee.html
			 */
			$this->addLine($attendee['key'], $attendee['value']);
		}

		// check if a reminder should be included
		$reminder = (int) $this->getOption('reminder');

		if ($reminder >= 0)
		{
			// create event alarm
			$this->createAlarm($event, $reminder);
		}

		/**
		 * Trigger event to allow the plugins to include custom options within the
		 * current calendar event.
		 *
		 * @param 	array 	 $event    The event data.
		 * @param 	mixed 	 $record   The database record.
		 * @param 	mixed 	 $handler  The current handler instance.
		 *
		 * @return 	string   The rules to include.
		 *
		 * @since 	1.6.6
		 * @since   1.7      $record is now a registry.
		 */
		$res = $dispatcher->trigger('onAfterBuildEventICS', array($data, $event, $this));

		// append also the values that have been returned by the plugins
		$this->ics .= implode('', array_filter($res));

		/**
		 * Closes the event properties.
		 *
		 * @see BEGIN:VEVENT
		 */
		$this->addLine('END', 'VEVENT');
	}

	/**
	 * Creates an alarm for the specified event.
	 *
	 * @param 	JRegistry  $event     The event to bind.
	 * @param 	integer    $reminder  The reminder in minutes.
	 *
	 * @return 	void
	 */
	protected function createAlarm($event, $reminder = 0)
	{
		/**
		 * Provide a grouping of component properties that define an alarm.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-6-6-alarm-component.html
		 */
		$this->addLine('BEGIN', 'VALARM');

		/**
		 * This property specifies a positive duration of time.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-2-5-duration.html
		 */
		if ($reminder == 0)
		{
			// trigger alert at event time
			$duration = '-PT0S';
		}
		else if ($reminder < 60)
		{
			// trigger alert X minutes in advance
			$duration = '-PT' . $reminder . 'M';
		}
		else
		{
			// trigger alert X hours in advance
			$duration = '-PT' . floor($reminder / 60) . 'H' . ($reminder % 60) . 'M';
		}

		/**
		 * This property specifies when an alarm will trigger.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-6-3-trigger.html
		 */
		$this->addLine(array('TRIGGER', 'RELATED=START'), $duration);

		/**
		 * This property defines the action to be invoked when an alarm is triggered.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-6-1-action.html
		 */
		$this->addLine('ACTION', 'DISPLAY');

		/**
		 * In a DISPLAY alarm, the intended alarm effect is for the text value of
		 * the "DESCRIPTION" property to be displayed to the user.
		 *
		 * @link https://icalendar.org/iCalendar-RFC-5545/3-8-1-5-description.html
		 */
		$this->addLine('DESCRIPTION', JText::translate('VAP_EXPORT_DRIVER_ICS_REMINDER_FIELD'));
		
		/**
		 * Closes the alarm properties.
		 *
		 * @see BEGIN:VALARM
		 */
		$this->addLine('END', 'VALARM');
	}

	/**
	 * Adds a line within the ICS buffer by caring of
	 * the iCalendar standards.
	 *
	 * @param 	mixed  $rule     Either the rule command or an array of commands to be concatenated (;).
	 * @param 	mixed  $content  Either the rule content or an array of contents to be concatenated (,).
	 *
	 * @return 	self   This object to support chaining.
	 */
	protected function addLine($rule, $content = null)
	{
		// concat rules in case of array
		if (is_array($rule))
		{
			// rule with multiple parts, use semi-colon
			$rule = implode(';', $rule);
		}

		// concat contents in case of array
		if (is_array($content))
		{
			// multi-contents list, use comma
			$content = implode(',', $content);
		}

		// create line
		if (is_null($content))
		{
			// we had the full line within the rule
			$line = $rule;
		}
		else
		{
			// merge rule and content
			$line = $rule . ':' . $content;
		}

		// split string every 73 characters (reserve 2 chars to include new line and space)
		$chunks = str_split($line, 73);

		// merge lines togheter by using indentation technique,
		// then add the line to the buffer
		$this->ics .= implode("\n ", $chunks) . "\n";

		return $this;
	}

	/**
	 * Converts a UNIX timestamp to a valid ICS date string.
	 *
	 * @param 	integer  $ts  The timestamp to convert.
	 *
	 * @return 	string 	 The formatted date.
	 */
	protected function tsToCal($ts)
	{
		return JDate::getInstance($ts)->format('Ymd\THis\Z');
	}

	/**
	 * Escapes a line value.
	 *
	 * @param 	string 	$str  The string to escape.
	 *
	 * @return 	string 	The escaped string.
	 */
	protected function escape($str)
	{
		// escape reserved characters
		return preg_replace('/([\,;])/','\\\$1', $str);
	}
}
