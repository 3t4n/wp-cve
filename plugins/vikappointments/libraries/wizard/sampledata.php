<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  wizard
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Implement the wizard step used to download and
 * install a sample data package.
 *
 * @since 1.2.3
 */
class VAPWizardStepSampleData extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return __('Sample Data', 'vikappointments');
	}

	/**
	 * Returns the step description.
	 *
	 * @return 	string  The step description.
	 */
	public function getDescription()
	{
		return __('<p>It is possible to download here a set of sample data, which will auto-populate VikAppointments to be immediately ready to use.</p>', 'vikappointments');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-download"></i>';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	public function getGroup()
	{
		// belongs to GLOBAL group
		return JText::translate('VAPMENUTITLEHEADER3');
	}

	/**
	 * Returns the HTML to display description and actions
	 * needed to complete the step.
	 *
	 * @return 	string  The HTML of the step.
	 */
	public function display()
	{
		// always try to search for a layout related to this step
		return JLayoutHelper::render('html.wizard.sampledata', array('step' => $this));
	}

	/**
	 * Checks whether the specified step can be skipped.
	 * By default, all the steps are mandatory.
	 * 
	 * @return 	boolean  True if skippable, false otherwise.
	 */
	public function canIgnore()
	{
		return true;
	}

	/**
	 * Implements the step execution.
	 *
	 * @param 	JRegistry  $data  The request data.
	 *
	 * @return 	boolean
	 */
	protected function doExecute($data)
	{
		// get selected sample data
		$id = $data->get('sampledata');

		JLoader::import('adapter.filesystem.folder');

		// get temporary dir
		$tmp = get_temp_dir();

		// clean temporary path
		$tmp = rtrim(JPath::clean($tmp), DIRECTORY_SEPARATOR);

		// make sure the folder exists
		if (!is_dir($tmp))
		{
			throw new Exception(sprintf('Temporary folder [%s] does not exist', $tmp), 404);
		}

		// make sure the temporary folder is writable
		if (!wp_is_writable($tmp))
		{
			throw new Exception(sprintf('Temporary folder [%s] is not writable', $tmp), 403);
		}

		// download end-point
		$url = 'https://vikwp.com/api/?task=sampledata.download';

		// init HTTP transport
		$http = new JHttp();

		// build smaple data file name
		$packname = 'sampledata-' . uniqid();

		// build request headers
		$headers = array(
			// turn on stream to push body within a file
			'stream'   => true,
			// define the filepath in which the data will be pushed
			'filename' => $tmp . DIRECTORY_SEPARATOR . $packname . '.zip',
			// make sure the request is non blocking
			'blocking' => true,
			// force timeout to 60 seconds
			'timeout'  => 60,
		);

		// build post data
		$data = array(
			'id' => $id,
		);

		// make connection with VikWP server
		$response = $http->post($url, $data, $headers);

		if ($response->code != 200)
		{
			// raise error returned by VikWP
			throw new Exception($response->body, $response->code);
		}

		// make sure the file has been saved
		if (!JFile::exists($headers['filename']))
		{
			throw new Exception('ZIP package could not be saved on disk', 404);
		}

		// get backup model
		$model = JModelVAP::getInstance('backup');

		$error = null;

		// attempt to install the sample data as a backup
		if (!$model->restore($headers['filename']))
		{
			// get last registered error
			$error = $model->getError();

			if (!$error instanceof Exception)
			{
				// create an exception with the fetched error message
				$error = new Exception($error, 500);
			}
		}

		// always delete the downloaded package
		JFile::delete($headers['filename']);

		if ($error)
		{
			// propagate error
			throw $error;
		}

		return true;
	}

	/**
	 * Returns a list of supported sample data.
	 *
	 * @return 	array  A list of sample data.
	 */
	public function getSampleData()
	{
		// build transient key
		$transient = 'vikappointments_sampledata_' . md5(VIKAPPOINTMENTS_SOFTWARE_VERSION);

		// get cached sample data list
		$data = get_transient($transient);

		if ($data)
		{
			// return cached transient
			return json_decode($data);
		}

		// instantiate HTTP transport
		$http = new JHttp();

		// build end-point URI
		$uri = 'https://vikwp.com/api/?task=sampledata.list';

		// build post data
		$post = array(
			'sku'     => 'vap',
			'version' => VIKAPPOINTMENTS_SOFTWARE_VERSION,
		);

		// load sample data from server
		$response = $http->post($uri, $post);

		if ($response->code == 200)
		{
			// decode response
			$data = json_decode($response->body);

			// cache sample data list for an hour
			set_transient($transient, json_encode($data), HOUR_IN_SECONDS);
		}
		else
		{
			$data = array();
		}

		return $data;
	}
}
