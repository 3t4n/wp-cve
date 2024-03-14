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
 * Class used to dispatch a cron job.
 *
 * @since 1.5
 * @since 1.7 Renamed from CronDispatcher.
 * 
 * @see VAPCronJob
 */
class VAPCronDispatcher
{
	/**
	 * Base drivers path.
	 *
	 * @deprecated 1.8
	 */
	public static $BASE_PATH = null;

	/**
	 * A list of include paths.
	 *
	 * @var   array
	 * @since 1.7
	 */
	protected static $includePaths = array();

	/**
	 * Class constructor.
	 */
	private function __construct()
	{
		// construct is not accessible
	}

	/**
	 * Instantiates the cron job class based on the specified action.
	 *
	 * @param 	string 	 $action 	The name of the file (case insensitive).
	 * @param 	integer  $id 		The ID of the cron job.
	 * @param 	mixed 	 $args 		The parameters of the cron job.
	 *
	 * @return 	mixed 	 The CronJob object or null.
	 *
	 * @uses 	includeJob()
	 */

	public static function getJob($action, $id = 0, $args = array())
	{
		// include the cronjob and get its classname
		$classname = self::includeJob($action);

		if ($classname !== null && class_exists($classname))
		{
			// if the classname is not NULL and the class exists,
			// instantiate a new object
			$obj = new $classname($id, $args);

			// make sure the object in as instance of CronJob class
			if ($obj instanceof VAPCronJob)
			{
				return $obj;
			}
		}

		// return NULL if the cronjob or the getJob method don't exist
		return null;
	}

	/**
	 * Returns the fields needed for the configuration of the cron job.
	 *
	 * @param 	string 	$action  The name of the file (not case sensitive). 
	 *
	 * @return 	mixed 	The CronFormField array or null.
	 *
	 * @uses 	includeJob()
	 */

	public static function getJobConfiguration($action)
	{
		// include the cronjob and get its classname
		$classname = self::getJob($action);

		if ($classname !== null && method_exists($classname, 'getConfiguration'))
		{
			// if the classname is not NULL and the class owns the `getConfiguration`
			// static method, return the object containing its configuration
			return $classname->getConfiguration();
		}

		 // return NULL if the cron job or `getConfiguration` method don't exist
		return null;
	}

	/**
	 * Includes all the cron jobs contained in the base path.
	 *
	 * @param 	boolean  $assoc  True to get an associative array using
	 * 							 the filename as keys. False to return a 
	 * 							 sequential array.
	 *
	 * @return 	array 	 The list of included cron jobs.
	 *
	 * @uses 	includeJob()
	 * @since 	1.6
	 */
	public static function includeAll($assoc = true)
	{
		$pool = array();

		// get all registered include paths
		foreach (static::getIncludePaths() as $path)
		{
			// normalize path
			$path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

			// get the list of files stored into the current path
			foreach (glob($path . '*.php') as $file)
			{
				// strip the full path and take the basename only
				$basename = basename($file);

				// try to include the cron job
				if ($job = self::getJob($basename))
				{
					// if associative array use the basename as key
					// and push the cron job into the list
					if ($assoc)
					{
						$pool[$basename] = $job;
					}
					// otherwise push the cron job only
					else
					{
						$pool[] = $job;
					}
				}
			}
		}

		return $pool;
	}

	/**
	 * Includes the cron job file based on the specified action.
	 * Returns the name of the class on success, otherwise NULL.
	 *
	 * @param 	string 	$action  The name of the file (case insensitive). 
	 *
	 * @return 	mixed 	The classname or null.
	 */
	public static function includeJob($action)
	{
		if (empty($action))
		{
			return null;
		}

		// if exists, remove file extension from action
		$action = preg_replace("/\.php$/i", '', $action);

		// get all registered include paths
		foreach (static::getIncludePaths() as $path)
		{
			// normalize path
			$path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $action . '.php';

			if (is_file($path))
			{
				// if the file exists include it
				require_once $path;

				// make action camelcase
				$action = preg_replace("/_+/", ' ', $action);
				$action = preg_replace("/\s+/", '', ucwords($action));

				// create class name
				$classname = 'VAPCronJob' . $action;

				if (!class_exists($classname))
				{
					// let's try also with old notation
					$classname = preg_replace("/^VAP/", '', $classname);
				}

				if (class_exists($classname))
				{
					// return the classname of the included cron job
					return $classname;
				}
			}
		}

		// return null if the cron job doesn't exist
		return null;
	}

	/**
	 * Gets a list of supported include paths.
	 *
	 * @return  array
	 *
	 * @since 	1.7
	 */
	public static function getIncludePaths()
	{
		/**
		 * Check whether the folder of the cron jobs
		 * is specified in the old method for backward
		 * compatibility.
		 *
		 * @deprecated 1.8
		 */
		if (isset(static::$BASE_PATH))
		{
			// register path in a temporary variable
			// to avoid infinite loops
			$tmp = static::$BASE_PATH;
			// unset property
			static::$BASE_PATH = null;
			// include base path with new technique
			static::addIncludePath($tmp);
		}

		return static::$includePaths;
	}

	/**
	 * Adds one path to include in driver search.
	 * Proxy of addIncludePaths().
	 *
	 * @param   string  $path  The path to search for drivers.
	 *
	 * @return  array   A list of include paths.
	 *
	 * @since 	1.7
	 *
	 * @uses 	addIncludePaths()
	 */
	public static function addIncludePath($path)
	{
		return static::addIncludePaths($path);
	}

	/**
	 * Adds one or more paths to include in driver search.
	 *
	 * @param   mixed  $paths  The path or array of paths to search for drivers.
	 *
	 * @return  array   A list of include paths.
	 *
	 * @since 	1.7
	 *
	 * @uses 	getIncludePaths()
	 * @uses 	setIncludePaths()
	 */
	public static function addIncludePaths($paths)
	{
		$includePaths = static::getIncludePaths();

		if (!empty($paths))
		{
			// in case the path is an array, merge all the paths and make sure we have no duplicates
			if (is_array($paths))
			{
				$includePaths = array_unique(array_merge($includePaths, $paths));
			}
			// otherwise add the path as first element
			else
			{
				$includePaths[] = $paths;
			}

			// update include paths
			static::setIncludePaths($includePaths);
		}

		return $includePaths;
	}

	/**
	 * Sets the include paths to search for drivers.
	 *
	 * @param   array 	$paths  Array with paths to search in.
	 *
	 * @return  void
	 *
	 * @since   1.7
	 */
	public static function setIncludePaths($paths)
	{
		static::$includePaths = (array) $paths;
	}
}

/**
 * Register a class alias for backward compatibility.
 *
 * @deprecated 1.8
 */
class_alias('VAPCronDispatcher', 'CronDispatcher');
