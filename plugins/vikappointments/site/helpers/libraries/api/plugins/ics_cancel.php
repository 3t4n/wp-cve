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
 * Event used to cancel/delete the appointment matching the given ICS event.
 * The requirements of the payloads follows the Google standards.
 *
 * @since 1.7
 */
class VAPApiEventIcsCancel extends VAPApiEvent
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
		try
		{
			// load reservation model
			$model = JModelVAP::getInstance('reservation');

			if (!$model)
			{
				// an error occurred, model not found...
				throw new Exception('Missing reservation model', 400);
			}

			///////////////////////////////////
			// fetch event unique identifier //
			///////////////////////////////////

			if (!empty($args['id']))
			{
				$uid = $args['id'];
			}
			else if (!empty($args['iCalUID']))
			{
				$uid = $args['iCalUID'];
			}
			else
			{
				// missing event ID
				throw new Exception('Event ID not specified', 400);
			}

			//////////////////////////
			// fetch appointment id //
			//////////////////////////

			// load appointment matching the specified iCal UID
			$appointment = $model->getItem(array('icaluid' => $uid));

			if (!$appointment)
			{
				// appointment not found
				throw new Exception(sprintf('Appointment with UID [%s] not found', $uid), 404);
			}

			////////////////////////
			// fetch event status //
			////////////////////////
			
			if (empty($args['status']))
			{
				// use default cancelled status if not specified
				$args['status'] = 'cancelled';
			}

			///////////////////////////////
			// cancel/delete appointment //
			///////////////////////////////

			if ($args['status'] == 'cancelled')
			{
				// update record with cancelled status
				$id = $model->save(array(
					'id'     => $appointment->id,
					'status' => JHtml::fetch('vaphtml.status.cancelled', 'appointments', 'code'),
				));
			}
			else
			{
				// permanently delete the appointment
				$id = $model->delete($appointment->id);
			}

			if (!$id)
			{
				// an error occurred, retrieve error from model
				$error = $model->getError();

				if (!$error instanceof Exception)
				{
					$error = new Exception($error ? $error : 'Error', 500);
				}

				throw $error;
			}
		}
		catch (Exception $e)
		{
			// register response and abort request
			$response->setStatus(0)->setContent($e->getMessage());

			throw $e;
		}

		// save was successful
		$response->setStatus(1);
		// register short description
		$response->setContent(sprintf('Appointment [%d] cancelled, from [%s]', $appointment->id, $appointment->icaluid));

		// let the application framework safely output the response
		return $appointment;
	}

	/**
	 * @override
	 * Returns the title of the event, a more readable representation of the plugin name.
	 *
	 * @return 	string 	The title of the event.
	 */
	public function getTitle()
	{
		return 'ICS Cancellation';
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
		return JLayoutHelper::render('api.plugins.ics_cancel', array('plugin' => $this));
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
			'created'          => $created->format('Y-m-d\TH:i:s.000\Z', $local = true),
			'creator'          => array('email' => 'employee@mail.com'),
			'duration_minutes' => 60,
			'end'              => array('dateTime' => $end->toISO8601(true)),
			'id'               => md5(time()),
			'organizer'        => array('email' => 'employee@mail.com'),
			'start'            => array('dateTime' => $start->toISO8601(true)),
			'status'           => 'cancelled',
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
