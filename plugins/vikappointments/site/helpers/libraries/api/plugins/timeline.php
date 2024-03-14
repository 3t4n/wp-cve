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
 * Event used to load the availability timeline for a specific date.
 *
 * @since 1.7
 */
class VAPApiEventTimeline extends VAPApiEvent
{
	/**
	 * The custom action that the event have to perform.
	 * This method should not contain any exit or die function, 
	 * otherwise the event won't be properly terminated.
	 *
	 * @param 	array           $args      The provided arguments for the event.
	 * @param 	VAPApiResponse  $response  The response object for admin.
	 *
	 * @return 	mixed           The response to output or the error message (ErrorAPIs).
	 */
	protected function doAction(array $args, VAPApiResponse $response)
	{
		if (empty($args['id_ser']))
		{
			// missing service ID attribute
			$error = new Exception('The payload didn\'t specify the ID of the service', 400);

			// register response and abort request
			$response->setStatus(0)->setContent($error->getMessage());

			throw $error;
		}

		if (empty($args['date']))
		{
			// get current date if not specified
			$args['date'] = JFactory::getDate()->format('Y-m-d');
		}

		// load the model able to handle the timeline
		$model = JModelVAP::getInstance('employeesearch');

		// load the timeline
		$timeline = $model->getTimeline($args);

		if (!$timeline)
		{
			// an error occurred, retrieve error from model
			$error = $model->getError();

			if (!$error instanceof Exception)
			{
				$error = new Exception($error ? $error : 'Error', 500);
			}

			// register response and abort request
			$response->setStatus(0)->setContent($error->getMessage());

			throw $error;
		}

		// valid response
		$response->setStatus(1);

		// let the application framework safely output the response
		return $timeline->getTimeline();
	}

	/**
	 * @override
	 * Returns the title of the event, a more readable representation of the plugin name.
	 *
	 * @return 	string 	The title of the event.
	 */
	public function getTitle()
	{
		return 'Availability Timeline';
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
		return JLayoutHelper::render('api.plugins.timeline', array('plugin' => $this));
	}
}
