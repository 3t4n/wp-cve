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

JLoader::import('adapter.mvc.models.form');

/**
 * VikAppointments plugin License model.
 *
 * @since 1.1.11
 * @see   JModelForm
 */
class VikAppointmentsModelLicense extends JModelForm
{
	/**
	 * The base end-point URI.
	 *
	 * @var string
	 */
	protected $baseUri = 'https://vikwp.com/api/';

	/**
	 * Implements the request needed to validate
	 * the PRO license of the plugin.
	 *
	 * @param 	string  $key  The license key.
	 *
	 * @return 	mixed   The response if valid, false otherwise.
	 */
	public function validate($key)
	{
		// validate specified key
		if (!preg_match("/^[a-zA-Z0-9]{16,16}$/", $key))
		{
			// invalid key, register error
			$this->setError(new Exception(JText::translate('VAPINVALIDPROKEY'), 400));

			return false;
		}

		// update license hash
		VikAppointmentsLoader::import('update.license');
		$hash = VikAppointmentsLicense::getHash();

		// validation end-point
		$url = $this->baseUri . '?task=licenses.validate';

		// init HTTP transport
		$http = new JHttp();

		// build post data
		$data = array(
			'key'         => $key,
			'application' => 'vap',
			'version'     => VIKAPPOINTMENTS_SOFTWARE_VERSION,
			'domain'      => JUri::root(),
			'ip'          => $_SERVER['REMOTE_ADDR'],
			'hash'        => $hash,
		);

		// build headers
		$headers = array(
			/**
			 * Always bypass SSL validation while reaching our end-point.
			 *
			 * @since 1.2
			 */
			'sslverify' => false,
		);

		/**
		 * Apply filters to manipulate the post data and the headers at runtime.
		 *
		 * @param  array   $data      The post data array.
		 * @param  array   &$headers  An associative array of HTTP directives.
		 * @param  string  $action    The type of action to perform (validate).
		 *
		 * @since  1.2
		 */
		$data = apply_filters_ref_array('vikappointments_license_before_post', array($data, &$headers, 'validate'));

		// make connection with VikWP server
		$response = $http->post($url, $data, $headers);

		if ($response->code != 200)
		{
			// register error returned by VikWP
			$this->setError(new Exception($response->body, $response->code));

			return false;
		}

		// try decoding JSON
		$body = json_decode($response->body);

		if (!$body || $body->status != 1)
		{
			// invalid response received, register error
			$this->setError(new Exception(JText::sprintf('VAPINVALIDRESPONSE', $response->body), 500));

			return false;
		}

		// import necessary libraries
		VikAppointmentsLoader::import('update.changelog');
		VikAppointmentsLoader::import('update.license');

		// register values
		VikAppointmentsChangelog::store((isset($body->changelog) ? $body->changelog : ''));
		VikAppointmentsLicense::setKey($body->key);
		VikAppointmentsLicense::setExpirationDate($body->expdate);

		// return response object
		return $body;
	}

	/**
	 * Implements the request needed to download
	 * the PRO version of the plugin.
	 *
	 * @param 	string   $key  The license key.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function download($key)
	{
		// validate specified key
		if (!preg_match("/^[a-zA-Z0-9]{16,16}$/", $key))
		{
			// invalid key, register error
			$this->setError(new Exception(JText::translate('VAPINVALIDPROKEY'), 400));

			return false;
		}

		// update license hash
		VikAppointmentsLoader::import('update.license');
		$hash = VikAppointmentsLicense::getHash();

		JLoader::import('adapter.filesystem.folder');

		// get temporary dir
		$tmp = get_temp_dir();

		// clean temporary path
		$tmp = rtrim(JPath::clean($tmp), DIRECTORY_SEPARATOR);

		// make sure the folder exists
		if (!is_dir($tmp))
		{
			// missing temporary folder, register error
			$this->setError(new Exception(sprintf('Temporary folder [%s] does not exist', $tmp), 404));

			return false;
		}

		// make sure the temporary folder is writable
		if (!wp_is_writable($tmp))
		{
			// tmp folder not writable, register error
			$this->setError(new Exception(sprintf('Temporary folder [%s] is not writable', $tmp), 403));

			return false;
		}

		// download end-point
		$url = $this->baseUri . '?task=licenses.download';

		// init HTTP transport
		$http = new JHttp();

		// build request headers
		$headers = array(
			// turn on stream to push body within a file
			'stream'   => true,
			// define the filepath in which the data will be pushed
			'filename' => $tmp . DIRECTORY_SEPARATOR . 'vikappointmentspro.zip',
			// make sure the request is non blocking
			'blocking' => true,
			// force timeout to 60 seconds
			'timeout'  => 60,
			/**
			 * Always bypass SSL validation while reaching our end-point.
			 *
			 * @since 1.2
			 */
			'sslverify' => false,
		);

		// build post data
		$data = array(
			'key'         => $key,
			'application' => 'vap',
			'version'     => VIKAPPOINTMENTS_SOFTWARE_VERSION,
			'domain'      => JUri::root(),
			'ip'          => $_SERVER['REMOTE_ADDR'],
			'hash'        => $hash,
		);

		/**
		 * Apply filters to manipulate the post data and the headers at runtime.
		 *
		 * @param  array   $data      The post data array.
		 * @param  array   &$headers  An associative array of HTTP directives.
		 * @param  string  $action    The type of action to perform (download).
		 *
		 * @since  1.2
		 */
		$data = apply_filters_ref_array('vikappointments_license_before_post', array($data, &$headers, 'download'));

		// make connection VikWP server
		$response = $http->post($url, $data, $headers);

		if ($response->code != 200)
		{
			// register error returned by VikWP
			$this->setError(new Exception($response->body, $response->code));

			return false;
		}

		// make sure the file has been saved
		if (!JFile::exists($headers['filename']))
		{
			// something went wrong while saving the archive, register error
			$this->setError(new Exception('ZIP package could not be saved on disk', 404));

			return false;
		}

		// create destination folder for extracted elements
		$dest = $tmp . DIRECTORY_SEPARATOR . 'vikappointments';

		// make sure the destination folder doesn't exist
		if (JFolder::exists($dest))
		{
			// remove it before proceeding with the extraction
			JFolder::delete($dest);
		}

		// import archive class handler
		JLoader::import('adapter.filesystem.archive');

		// the package was downloaded successfully, let's extract it (onto TMP folder)
		$extracted = JArchive::extract($headers['filename'], $tmp);

		// we no longer need the archive
		JFile::delete($headers['filename']);

		if (!$extracted)
		{
			// an error occurred while extracting the files, register it
			$this->setError(new Exception(sprintf('Cannot extract files to [%s]', $tmp), 500));

			return false;
		}

		// make sure the folder is intact
		if (!JFolder::exists($dest))
		{
			// impossible to access the extracted elements, register error
			$this->setError(new Exception(sprintf('Cannot access extracted elements from [%s] folder', $dest), 404));

			return false;
		}
		
		// copy the root files
		$root_files = JFolder::files($dest, '.', false, true);

		foreach ($root_files as $file)
		{
			if (!JFile::copy($file, VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . basename($file)))
			{
				// delete folder before exiting
				JFolder::delete($dest);

				// we cannot afford to not be able to copy a root file, register error
				$this->setError(new Exception(sprintf('Cannot copy root [%s] file', basename($file)), 500));

				return false;
			}
		}

		// copy the root folders
		$root_folders = JFolder::folders($dest, '.', false, true);

		foreach ($root_folders as $folder)
		{
			if (!JFolder::copy($folder, VIKAPPOINTMENTS_BASE . DIRECTORY_SEPARATOR . basename($folder), '', true))
			{
				// delete folder before exiting
				JFolder::delete($dest);

				// we cannot afford to not be able to copy a root folder, register error
				$this->setError(new Exception(sprintf('Cannot copy root [%s] folder', basename($folder)), 500));

				return false;
			}
		}

		// process complete, clean up the temporary folder before exiting
		JFolder::delete($dest);

		return true;
	}
}
