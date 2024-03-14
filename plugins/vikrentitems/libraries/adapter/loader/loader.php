<?php
/** 
 * @package     VikWP - Libraries
 * @subpackage  adapter.loader
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Plugin smart loader class.
 *
 * @since 10.0
 */
abstract class JLoader
{
	/**
	 * The list containing all the resources loaded.
	 *
	 * @var array
	 */
	protected static $includes = array();

	/**
	 * The list containing all the filename aliases.
	 *
	 * @var array
	 */
	protected static $aliases = array();

	/**
	 * Base path to load resources.
	 *
	 * @var string
	 */
	public static $base = '';

	/**
	 * Loads the specified file.
	 *
	 * @param   string  $key   The class name to look for (dot notation).
	 * @param   string  $base  Search this directory for the class.
	 *
	 * @return  boolean  True on success, otherwise false.
	 */
	public static function import($key, $base = null)
	{
		// if no base provided, use the default one
		if (empty($base))
		{
			$base = static::$base;
		}

		$sign = serialize(array($key, $base));

		// if the resource is not loaded, try to do it
		if (!isset(static::$includes[$sign]))
		{
			$success = false;

			// remove trailing slash (if any)
			$base = rtrim($base, DIRECTORY_SEPARATOR);

			$parts = explode('.', $key);
			$class = array_pop($parts);

			// if the file has been registered with an alias, replace it with the original one
			if (isset(static::$aliases[$class]))
			{
				$class = static::$aliases[$class];
			}

			// re-insert class to build the relative path
			$parts[] = $class;

			// build the path
			$path = implode(DIRECTORY_SEPARATOR, $parts);

			// if the file exists, load it
			if (is_file($base . DIRECTORY_SEPARATOR . $path . '.php'))
			{
				$success = (bool) include_once $base . DIRECTORY_SEPARATOR . $path . '.php';
			}

			// cache the loading status
			static::$includes[$sign] = $success;
		}

		return static::$includes[$sign];
	}

	/**
	 * Register an alias of a given class filename.
	 * This is useful for those files that contain a dot in their name.
	 *
	 * @param 	string 	$name 	The filename to register.
	 * @param 	string 	$alias 	The alias to use.
	 */
	public static function registerAlias($name, $alias)
	{	
		if (!isset(static::$aliases[$alias]))
		{
			static::$aliases[$alias] = $name;
		}
	}
}

/**
 * Implemented a runtime autoloader to prevent errors due to
 * libraries that haven't been manually loaded.
 *
 * @since 10.1.35
 */
spl_autoload_register(function($class)
{
	$original = $class;

	// add support for VikWP class prefix
	$class = preg_replace("/^VikWP/", 'J', $class);

	// observe only the classes that starts with "J"
	if (!preg_match("/^J/", $class))
	{
		return false;
	}

	switch ($class)
	{
		case 'JPagination':
			$result = JLoader::import('adapter.pagination.pagination');
			break;

		case 'JView':
		case 'JViewLegacy':
			$result = JLoader::import('adapter.mvc.view');
			break;

		case 'JController':
		case 'JControllerLegacy':
			$result = JLoader::import('adapter.mvc.controller');
			break;

		case 'JControllerAdmin':
			$result = JLoader::import('adapter.mvc.controllers.admin');
			break;

		case 'JComponentHelper':
			$result = JLoader::import('adapter.component.helper');
			break;

		case 'JModuleHelper':
			$result = JLoader::import('adapter.module.helper');
			break;

		case 'JPath':
			$result = JLoader::import('adapter.filesystem.path');
			break;

		case 'JFile':
			$result = JLoader::import('adapter.filesystem.file');
			break;

		case 'JArchive':
			$result = JLoader::import('adapter.filesystem.archive');
			break;

		case 'JFolder':
			$result = JLoader::import('adapter.filesystem.folder');
			break;

		case 'JForm':
			$result = JLoader::import('adapter.form.form');
			break;

		case 'JFormField':
			$result = JLoader::import('adapter.form.field');
			break;

		case 'JRegistry':
			$result = JLoader::import('adapter.application.registry');
			break;

		case 'JVersion':
			$result = JLoader::import('adapter.application.version');
			break;

		default:
			$result = false;
	}

	// in case the loaded class exists and the requested on starts with VikWP,
	// create an alias to support a more appropriate notation
	if (class_exists($class) && preg_match("/^VikWP/", $original))
	{
		class_alias($class, $original);
		$result = true;
	}

	return $result;
});
