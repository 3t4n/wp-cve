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
 * Event used to invoke the save method of the specified model.
 *
 * @since 1.7
 */
class VAPApiEventSaveData extends VAPApiEvent
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
		// first look within the request for the model to load
		$modelName = JFactory::getApplication()->input->get('model');

		if (!$modelName)
		{
			// model not found within the request, lets load it from the payload
			if (empty($args['modelname']))
			{
				// missing model
				$error = new Exception('Model not specified', 400);

				// register response and abort request
				$response->setStatus(0)->setContent($error->getMessage());

				throw $error;
			}

			// extract model name from payload
			$modelName = $args['modelname'];
		}

		// lets load the model
		$model = JModelVAP::getInstance($modelName);

		if (!$model)
		{
			// the given model doesn't exist
			$error = new Exception(sprintf('Model [%s] does not exist', $modelName), 404);

			// register response and abort request
			$response->setStatus(0)->setContent($error->getMessage());

			throw $error;
		}

		// save given payload
		$id = $model->save($args);

		if (!$id)
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

		// save was successful
		$response->setStatus(1);
		// register short description
		$response->setContent(sprintf('Saved [%s] with ID [%s]', $modelName, $id));

		// let the application framework safely output the response
		return $model->getData();
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
		return JLayoutHelper::render('api.plugins.save_data', array('plugin' => $this));
	}
}
