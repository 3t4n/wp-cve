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
 * Factory class to access the handler object to import items.
 *
 * @since 1.6
 */
final class ImportFactory
{
	/**
	 * A list containing all the classes already instantiated.
	 *
	 * @var array
	 */
	private static $instances = array();

	/**
	 * A list containing all the exportable classes already instantiated.
	 *
	 * @var array
	 */
	private static $exportable = array();

	/**
	 * Gets the import handler object, only creating it
	 * if it doesn't exist yet.
	 *
	 * @param 	string 	$type 	The entity type to import.
	 *
	 * @return 	mixed 	ImportObject on success, otherwise null.
	 */
	public static function getObject($type)
	{
		if (!isset(static::$instances[$type]))
		{
			static::$instances[$type] = null;

			// check whether the import file is supported
			if (static::isSupported($type))
			{
				VAPLoader::import('libraries.import.object');

				/**
				 * Trigger event to let the plugins include external importers.
				 * The plugins should include the resources needed, otherwise
				 * it wouldn't be possible to instantiate the returned classes.
				 *
				 * @param 	string  $type  The name of the import object to include.
				 *
				 * @return 	string 	The classname of the object.
				 *
				 * @since 	1.7
				 */
				$classname = VAPFactory::getEventDispatcher()->triggerOnce('onFetchImportObjectClassname', array($type));

				if (!$classname)
				{
					// in case no plugins returned a valid classname, 
					// attempt to load one of the existing ones
					$classname = 'ImportObject';

					if (VAPLoader::import('libraries.import.classes.' . $type))
					{
						// object found, use it as is
						$classname .= ucwords($type);
					}
				}

				// load import configuration
				$xml = static::loadXml($type);

				// create instance
				static::$instances[$type] = new $classname($xml, $type);
			}
		}

		return static::$instances[$type];
	}

	/**
	 * Checks if the specified entity type is supported.
	 *
	 * @param 	string 	$type 	The entity type to import.
	 *
	 * @return 	mixed   The file path if supported, otherwise false.
	 */
	public static function isSupported($type)
	{
		// check whether the file exists
		foreach (static::getIncludePaths() as $path)
		{
			$file = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $type . '.xml';

			if (is_file($file))
			{
				return $file;
			}
		}

		return false;
	}

	/**
	 * Loads the XML containing the instructions for the import.
	 *
	 * @param 	string 	$type 	The entity type to import.
	 *
	 * @return 	SimpleXMLElement
	 */
	protected static function loadXml($type)
	{
		return simplexml_load_file(static::isSupported($type));
	}

	/**
	 * Returns a list of paths in which to search for import objects.
	 *
	 * @return 	array
	 *
	 * @since 	1.7
	 */
	protected static function getIncludePaths()
	{
		static $paths = null;

		// load paths only once
		if (is_null($paths))
		{
			$paths = array();
			// add native folder
			$paths[] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'forms';

			/**
			 * Trigger event to let the plugins register external import objects.
			 *
			 * @return 	array 	A list of include paths.
			 *
			 * @since 	1.7
			 */
			$results = VAPFactory::getEventDispatcher()->trigger('onFetchImportIncludePaths');

			foreach ($results as $tmp)
			{
				// merge default files with specified ones
				$paths = array_merge($paths, (array) $tmp);
			}

			// exclude duplicate
			$paths = array_unique($paths);
		}

		return $paths;
	}

	/**
	 * Gets the export handler object, only creating it
	 * if it doesn't exist yet.
	 *
	 * @param 	string 	$type 	The export handler.
	 * @param 	array 	$args 	An array of options.
	 *
	 * @return 	mixed 	Exportable on success, otherwise null.
	 */
	public static function getExportable($type, array $args = array())
	{
		if (!isset(static::$exportable[$type]))
		{
			static::$exportable[$type] = null;

			VAPLoader::import('libraries.import.exportable');

			/**
			 * Trigger event to let the plugins include external drivers.
			 * The plugins should include the resources needed, otherwise
			 * it wouldn't be possible to instantiate the returned classes.
			 *
			 * @param 	string  $driver  The name of the driver to include.
			 *
			 * @return 	string 	The classname of the driver.
			 *
			 * @since 	1.7
			 */
			$classname = VAPFactory::getEventDispatcher()->triggerOnce('onFetchExportDriverClassname', array($type));

			if (!$classname)
			{
				// in case the no plugins returned a valid classname, 
				// attempt to load one of the existing ones
				VAPLoader::import('libraries.import.export.' . $type);

				$classname = str_replace('_', ' ', $type);
				$classname = str_replace(' ', '', ucwords($classname));
				$classname = 'Exportable' . $classname;
			}

			if (class_exists($classname))
			{
				static::$exportable[$type] = new $classname($args);
			}
		}

		return static::$exportable[$type];
	}

	/**
	 * Returns the list of all the supported exportable handlers.
	 *
	 * @param 	string 	$query 	The query to use.
	 * 							The character '*' obtains all the files.
	 *
	 * @return 	array 	The exportable list.
	 */
	public static function getExportList($query = '*')
	{
		if (!$query)
		{
			$query = '*';
		}

		$paths = array();

		// include default internal path
		$paths[] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'export';

		/**
		 * Trigger event to let the plugins register external drivers
		 * as supported exporters.
		 *
		 * @return 	array 	A list of include paths.
		 *
		 * @since 	1.7
		 */
		$results = VAPFactory::getEventDispatcher()->trigger('onFetchExportIncludePaths');

		foreach ($results as $tmp)
		{
			// merge default files with specified ones
			$paths = array_merge($paths, (array) $tmp);
		}

		// exclude duplicate
		$paths = array_unique($paths);

		$pool = array();

		// iterate all defined paths
		foreach ($paths as $path)
		{
			// extract files contained within this folder
			$files = glob(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $query . '.php');

			foreach ($files as $file)
			{
				// fetch driver type
				$type = basename($file);
				$type = substr($type, 0, strrpos($type, '.'));

				// create driver instance
				$exportable = static::getExportable($type);

				if ($exportable)
				{
					$pool[$type] = $exportable;
				}
			}
		}

		/**
		 * Sort by driver name.
		 *
		 * @since 1.7
		 */
		uasort($pool, function($a, $b)
		{
			return strcmp($a->getName(), $b->getName());
		});

		return $pool;
	}

	/**
	 * Returns the JForm object used to display the additional
	 * parameters of the given export type.
	 *
	 * @param 	string 	$type 	The export type.
	 * @param 	array 	$args 	The data to bind.
	 *
	 * @return 	mixed 	The form object on success, otherwise false.
	 */
	public static function getExportableForm($type, array $args = array())
	{
		/**
		 * Trigger event to let the plugins include returns the correct
		 * file in which the configuration of the driver is located.
		 *
		 * @param 	string  $driver  The name of the driver.
		 *
		 * @return 	string 	The path of the XML file.
		 *
		 * @since 	1.7
		 */
		$path = VAPFactory::getEventDispatcher()->triggerOnce('onFetchExportDriverFormXML', array($type));

		if (!$path || !is_file($path))
		{
			// no plugins returned a custom XML file, attempt
			// to load it from the default folder
			$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . $type . '.xml';
		}

		// check if the XML fieldset exists
		if (!is_file($path))
		{
			return false;
		}

		/**
		 * Auto-wrap the form fields within import_args[] array without
		 * having to specify it from the XML file.
		 *
		 * @since 1.7
		 */
		$options = array(
			'control' => 'import_args',
		);

		// try to load the form
		$form = JForm::getInstance('exportable' . $type, $path, $options);

		if (!$form)
		{
			return false;
		}

		// inject the form values
		$form->bind($args);

		return $form;
	}
}
