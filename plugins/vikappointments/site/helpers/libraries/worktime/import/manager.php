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

VAPLoader::import('libraries.worktime.import.layout');
VAPLoader::import('libraries.worktime.import.type');

/**
 * Working days import manager.
 * 
 * @since 1.7.1
 */
abstract class VAPWorktimeImportManager
{
	/**
	 * An array of include paths.
	 * 
	 * @var array
	 */
	protected static $includePaths = null;

	/**
	 * An array of types.
	 * 
	 * @var VAPWorktimeImportType[]
	 */
	protected static $types = null;

	/**
	 * Processes the given file and try to import the working days according
	 * to the mime type of the file.
	 * 
	 * @param 	string  $file     The path of the file to import.
	 * @param 	array   $options  An array of options.
	 * 
	 * @return 	array   An array containing all the fetched working times.
	 * 
	 * @throws 	Exception
	 */
	public static function process($file, array $options = [])
	{
		// access the list of supported import types
		$types = static::getSupportedTypes();

		$auto_delete = false;

		// check whether the file exists
		if (!JFile::exists($file))
		{
			$app = JFactory::getApplication();

			// file missing, try to upload it from the request
			$fileData = $app->input->files->get($file, null, 'raw');

			if (!$fileData)
			{
				// file not found in request
				throw new Exception(sprintf('Import file [%s] not found', $file), 404);
			}

			// build the filter with the allowed file types
			$filters = implode(',', array_keys($types));

			// try to upload the file
			$result = VikAppointments::uploadFile($fileData, $app->get('tmp_path'), $filters);

			if (!$result->status)
			{
				// unable to upload the image, abort
				if ($result->errno == 1)
				{
					// invalid file type
					$error = new Exception(JText::sprintf('VAPCONFIGFILETYPEERRORWHO', $result->mimeType), 405);
				}
				else
				{
					// unable to upload the file
					$error = new Exception(JText::translate('VAPCONFIGUPLOADERROR'), 500);
				}

				throw $error;
			}

			// file uploaded, register the path
			$file = $result->path;

			// auto-delete the uploaded file at the end of the process
			$auto_delete = true;
		}

		// extract file type from path
		$type = pathinfo($file, PATHINFO_EXTENSION);

		$error = null;

		$list = [];

		try
		{
			// make sure the file type is supported
			if (!isset($types[$type]))
			{
				// cannot handle the given type
				throw new Exception(sprintf('Import type [%s] not supported', $type), 405);
			}

			$buffer = '';

			// open file in reading mode
			$fp = fopen($file, 'r');

			while (!feof($fp))
			{
				// read buffer from file
				$buffer .= fread($fp, 8192);
			}

			fclose($fp);

			// process import through the requested type
			$list = $types[$type]->process($buffer);
		}
		catch (Exception $e)
		{
			$error = $e;
		}

		if ($auto_delete)
		{
			// auto-delete the uploaded file
			JFile::delete($file);
		}

		if ($error)
		{
			// propagate the error after cleaning the temporary file
			throw $error;
		}

		if (!isset($options['layout']))
		{
			// layout not found, use the default one
			$options['layout'] = 'raw';
		}

		if (!$options['layout'] instanceof VAPWorktimeImportLayout)
		{
			// try to load the layout from the default directory
			VAPLoader::import('libraries.worktime.import.layout.' . $options['layout']);
			// build layout class name
			$layoutClassName = 'VAPWorktimeImportLayout' . ucfirst($options['layout']);

			if (!class_exists($layoutClassName))
			{
				// import layout handler missing
				throw new Exception(sprintf('Import layout [%s] not found', $options['layout']), 404);
			}

			// create layout handler
			$options['layout'] = new $layoutClassName();

			if (!$options['layout'] instanceof VAPWorktimeImportLayout)
			{
				// invalid instance
				throw new Exception(sprintf('Import layout [%s] is not a valid instance', $layoutClassName), 500);
			}
		}

		// adjust the returned times to the requested layout
		return $options['layout']->build($list);
	}

	/**
	 * Returns a list of supported types.
	 * 
	 * @return 	array  An associative array of instances.
	 */
	public static function getSupportedTypes()
	{
		if (!is_null(static::$types))
		{
			return static::$types;
		}

		static::$types = [];

		// get list of registered paths
		$paths = static::addIncludePath();

		// scan all the folders
		foreach ($paths as $path)
		{
			// scan all the PHP files
			foreach (JFolder::files($path, '\.php') as $file)
			{
				// include the file
				include_once JPath::clean($path . '/' . $file);

				// extract type from file name
				$type = explode('.', $file);
				$type = strtolower($type[0]);

				// build class name
				$classname = 'VAPWorktimeImportType' . ucfirst($type);

				if (!class_exists($classname))
				{
					// class not found
					continue;
				}

				// create import instance
				$driver = new $classname();

				if (!$driver instanceof VAPWorktimeImportType)
				{
					// invalid class
					continue;
				}

				// register import type
				static::$types[$type] = $driver;
			}
		}

		return static::$types;
	}

	/**
	 * Registers a new include path in which to search for the supported import types.
	 *
	 * @param 	mixed  $paths  Either an array or the path to include.
	 *
	 * @return 	array  A list of supported include paths.
	 */
	public static function addIncludePath($paths = null)
	{
		if (!static::$includePaths)
		{
			// init with the default include path
			static::$includePaths = [
				dirname(__FILE__) . DIRECTORY_SEPARATOR . 'type',
			];
		}

		$paths = (array) $paths;

		// iterate paths
		foreach ($paths as $path)
		{
			// make sure the paths hasn't been registered yet
			if (!in_array($path, static::$includePaths))
			{
				static::$includePaths[] = $path;
			}
		}

		return static::$includePaths;
	}
}
