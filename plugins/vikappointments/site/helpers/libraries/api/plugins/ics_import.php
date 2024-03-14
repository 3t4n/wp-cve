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
 * Event used to import a ICS event as an appointment.
 * The requirements of the payloads follows the Google standards.
 *
 * @since 1.7
 */
class VAPApiEventIcsImport extends VAPApiEvent
{
	/**
	 * The custom action that the event have to perform.
	 * This method should not contain any exit or die function, 
	 * otherwise the event won't be properly terminated.
	 *
	 * @param 	array           $args      The provided arguments for the event.
	 * @param 	VAPApiResponse  $response  The response object for admin.
	 *
	 * @return 	mixed           The response to output or the error message (VAPApiError).
	 */
	protected function doAction(array $args, VAPApiResponse $response)
	{
		$data = array();

		try
		{
			// create empty variables if not specified
			$data['summary']     = isset($args['summary'])     ? $args['summary']     :  '';
			$data['description'] = isset($args['description']) ? $args['description'] :  '';
			$data['created']     = isset($args['created'])     ? $args['created']     :  '';

			///////////////////////////////////
			// fetch event unique identifier //
			///////////////////////////////////

			if (!empty($args['uid']))
			{
				$data['uid'] = $args['uid'];
			}
			if (!empty($args['id']))
			{
				$data['uid'] = $args['id'];
			}
			else if (!empty($args['iCalUID']))
			{
				$data['uid'] = $args['iCalUID'];
			}

			//////////////////////////////
			// fetch check-in date time //
			//////////////////////////////

			if (empty($args['start']['dateTime']))
			{
				// missing start date time
				throw new Exception('Event start date time not specified', 400);
			}

			// In case the date string was passed via GET, the plus sign of
			// the timezone might be converted into a blank space. Before
			// creating the date, we need to restore it for a correct
			// handling of the date format.
			$start = preg_replace("/\s+/", '+', $args['start']['dateTime']);
			$data['start'] = JDate::getInstance($start)->toISO8601();

			////////////////////////////////
			// fetch appointment duration //
			////////////////////////////////

			if (!empty($args['duration_minutes']))
			{
				// convert minutes in seconds
				$data['duration'] = (int) $args['duration_minutes'] * 60;
			}
			else if (empty($args['duration_seconds']))
			{
				// use duration in seconds
				$data['duration'] = (int) $args['duration_seconds'];
			}
			else if (empty($args['duration_hours']))
			{
				// convert hours in seconds
				$data['duration'] = (int) $args['duration_hours'] * 3600;
			}

			/**
			 * NOTE: it is important to have the duration in seconds because 
			 * the `VAPIcalEvent` class only accepts the seconds unit.
			 */

			///////////////////////
			// fetch employee ID //
			///////////////////////
			
			if (!empty($args['organizer']['email']))
			{
				// use organizer e-mail
				$data['organizer'] = $args['organizer']['email'];
			}
			else if (!empty($args['creator']['email']))
			{
				// use creator e-mail
				$data['organizer'] = $args['creator']['email'];
			}

			//////////////////////////////////
			// fetch number of participants //
			//////////////////////////////////

			if (!empty($args['attendee_emails']))
			{
				$data['attendee'] = $args['attendee_emails'];
			}

			////////////////////////////
			// create new appointment //
			////////////////////////////

			// create import handler
			$importer = new VAPIcalImporter();

			// attempt to import iCal event
			$appData = $importer->importEvent(new VAPIcalEvent($data));
		}
		catch (Exception $e)
		{
			// register response and abort request
			$response->setStatus(0)->setContent($e->getMessage());

			throw $e;
		}

		// save was successful
		$response->setStatus(1);

		if ($appData)
		{
			// register short description
			$response->setContent(sprintf('Appointment [%d] saved, from [%s]', $appData['id'], $data['uid']));
		}
		else
		{
			// register short description
			$response->setContent(sprintf('Nothing to update for [%s] appointment', $data['uid']));
		}

		// let the application framework safely output the response
		return $appData;
	}

	/**
	 * @override
	 * Returns the title of the event, a more readable representation of the plugin name.
	 *
	 * @return 	string 	The title of the event.
	 */
	public function getTitle()
	{
		return 'ICS Import';
	}

	/**
	 * @override
	 * Returns the description of the plugin.
	 *
	 * @return 	string
	 */
	public function getDescription()
	{
		// read the description HTML from a layout
		return JLayoutHelper::render('api.plugins.ics_import', array('plugin' => $this));
	}

	/**
	 * Returns a dummy payload to be used within the plugin description.
	 *
	 * @return 	string
	 */
	public function getDummyPayload()
	{
		// get timezone of the current user
		$tz = JFactory::getUser()->getTimezone();

		// generate creation date (now)
		$created = JFactory::getDate();
		$created->setTimezone($tz);

		// generate check-in date time (next week at 10:00)
		$start = JFactory::getDate('+1 week');
		$start->setTimezone($tz);
		$start->modify('10:00:00');

		// generate check-out date time (next week at 11:00)
		$end = clone $start;
		$end->modify('+1 hour');

		// create dummy array
		$json = array(
			'attendee_emails'  => 'customer@mail.com',
			'created'          => $created->format('Y-m-d\TH:i:s.000\Z', $local = false),
			'creator'          => array('email' => 'employee@mail.com'),
			'description'      => 'Any contents specified here will be added as reservation notes.',
			'duration_minutes' => 60,
			'end'              => array('dateTime' => $end->toISO8601(true)),
			'id'               => md5(time()),
			'organizer'        => array('email' => 'employee@mail.com'),
			'start'            => array('dateTime' => $start->toISO8601(true)),
			'summary'          => 'YOUR_SERVICE_NAME',
			'status'           => 'confirmed',
		);

		if (defined('JSON_PRETTY_PRINT'))
		{
			// json encode using pretty print to improve readability
			$json = json_encode($json, JSON_PRETTY_PRINT);
		}
		else
		{
			// minified json encode 
			$json = json_encode($json);
		}

		return $json;
	}
}
