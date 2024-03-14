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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments import model.
 *
 * @since 1.7
 */
class VikAppointmentsModelImport extends JModelVAP
{
	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed   An array of imported rows on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		// prepare parameters
		$type  = isset($data['type']) ? $data['type'] : '';
		$assoc = isset($data['assoc']) ? $data['assoc'] : array();
		$args  = isset($data['args']) ? $data['args'] : array();

		// do import
		return $this->import($type, $assoc, $args);
	}

	/**
	 * Processes an import file.
	 *
	 * @param 	string  $type   The import file type.
	 * @param 	array   $assoc  A lookup used to associate the CSV columns with the db table.
	 * @param 	array   $args   An array of options.
	 *
	 * @return 	mixed   An array of imported rows on success, false otherwise.
	 */
	public function import($type, $assoc = array(), $args = array())
	{
		VAPLoader::import('libraries.import.factory');
		$handler = ImportFactory::getObject($type);

		if (!$handler)
		{
			throw new Exception('Import type not supported.', 404);
		}

		if (!$handler->hasFile())
		{
			// no uploaded file
			return false;
		}

		// process import
		$count = $handler->save($assoc, $args);

		// flush imported file
		$this->delete($type);

		// build response array
		$response = array(
			'count' => $count,
			'total' => $handler->getTotalCount(),
		);

		// look for errors and copy them within model
		foreach ($handler->getErrors() as $error)
		{
			$this->setError($error);
		}

		return $response;
	}

	/**
	 * Uploads an import file to manage the associations between
	 * the columns contained within the CSV and the properties
	 * of the database table.
	 *
	 * @param 	string   $type  The import type.
	 * @param 	array    $csv   The uploaded file.
	 *
	 * @return 	mixed    The file path on success, false otherwise.  
	 */
	public function upload($type, $csv)
	{
		if (!$type)
		{
			// missing type
			return false;
		}

		// create new file name
		$file_name = $type . '_' . $csv['name'];

		// build path in which the files are stored
		$path = VAPADMIN . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'resources';
		// append file name to path
		$path .= DIRECTORY_SEPARATOR . $file_name;
		
		/**
		 * Added support for the following MIME types:
		 * - text/plain (.txt)
		 * - application/vnd.ms-excel (.csv on Windows)
		 *
		 * @since 1.6.3
		 * @since 1.7    The filtering is now applied to the file name.
		 */
		// $filters = 'text/csv,text/plain,application/vnd.ms-excel';
		$filters = 'csv';

		// upload import file
		$resp = VikAppointments::uploadFile($csv, $path, $filters, $overwrite = true);

		if (!$resp->status)
		{
			// unable to upload the image, abort
			$this->setError(JText::translate($resp->errno == 1 ? 'VAPCONFIGUPLOADERROR' : 'VAPCONFIGFILETYPEERROR'));

			return false;
		}

		// upload went fine
		return $resp->path;
	}

	/**
	 * Method to delete the uploaded import file.
	 *
	 * @param   string   $type  The import type.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($type = null)
	{
		if (!$type)
		{
			// missing type
			return false;
		}

		// build path in which the import files are uploaded
		$folder = VAPADMIN . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;

		// get all files that starts with the provided type: [TYPE]_*.csv
		$files = glob($folder . $type . '_*.csv');

		$res = false;

		// delete one by one
		foreach ($files as $file)
		{
			$res = unlink($file) || $res;
		}

		return $res;
	}
}
