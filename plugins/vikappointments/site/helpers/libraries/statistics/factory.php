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

VAPLoader::import('libraries.statistics.widget');

/**
 * Factory class used to obtain and instantiate the
 * supported statistics widgets.
 *
 * @since 1.7
 */
abstract class VAPStatisticsFactory
{
	/**
	 * Returns an associative array represeting the complete dashboard of widgets.
	 *
	 * @param 	string 	$location  The location in which the widgets have been published,
	 *                             such as 'dashboard' or 'finance'.
	 * @param 	mixed 	$user      The user that configured the widgets. If not specified,
	 *                             the current one will be used.
	 *
	 * @return 	array 	An associative array of widgets at the assigned positions.
	 *
	 * @uses 	getActiveWidgets()
	 */
	public static function getDashboard($location, $user = null)
	{
		// get list of active widgets
		$widgets = static::getActiveWidgets($location, $user);

		$dashboard = array();

		// iterate widgets
		foreach ($widgets as $w)
		{
			// check if the position already exists
			if (!isset($dashboard[$w->getPosition()]))
			{
				// create position
				$dashboard[$w->getPosition()] = array();
			}

			// Register widget within the position.
			// Do not use an associative array because the same
			// widget could be used more than once.
			$dashboard[$w->getPosition()][] = $w;
		}

		return $dashboard;
	}

	/**
	 * Returns a list containing all the active widgets.
	 *
	 * @param 	string 	$location  The location in which the widgets have been published,
	 *                             such as 'dashboard' or 'finance'.
	 * @param 	mixed 	$user      The user that configured the widgets. If not specified,
	 *                             the current one will be used.
	 *
	 * @return 	array 	An array of widgets.
	 *
	 * @uses 	getSupportedWidgets()
	 */
	public static function getActiveWidgets($location, $user = null)
	{
		$dbo = JFactory::getDbo();

		if (!$user instanceof JUser)
		{
			// load specified/current user
			$user = JFactory::getUser($user);
		}

		// get list of active widgets
		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_stats_widget'))
			->where($dbo->qn('location') . ' = ' . $dbo->q($location))
			->where($dbo->qn('id_user') . ' IN (0, ' . (int) $user->id . ')')
			->order($dbo->qn('id_user') . ' DESC')
			->order($dbo->qn('ordering') . ' ASC');

		$dbo->setQuery($q);
		
		// load active widgets
		$widgets = $dbo->loadObjectList();

		if ($widgets)
		{
			// in case the first widget is not global, take only
			// the widgets that are assigned to the specified user
			if ($widgets[0]->id_user > 0)
			{
				// filter the array and exclude widgets with `id_user` equals to "0"
				$widgets = array_values(array_filter($widgets, function($widget)
				{
					return $widget->id_user > 0;
				}));
			}
		}
		else
		{
			// no widgets loaded
			$widgets = array();
		}

		// get all supported widgets
		$supported = static::getSupportedWidgets($location, $user);

		$list = array();

		// iterate active widgets
		foreach ($widgets as $data)
		{
			// make sure the widget is supported
			if (isset($supported[$data->widget]))
			{
				// assign object to a temporary variable
				// to avoid altering the list reference
				$widget = clone $supported[$data->widget];

				// set widget ID
				$widget->setID($data->id);

				// set widget user ID
				$widget->setUserID($data->id_user);

				// set widget title
				$widget->setTitle($data->name);

				// assign the widget to its position
				$widget->setPosition($data->position);

				// set widget size
				$widget->setSize($data->size);

				// Register using the default creation ordering.
				// Do not use an associative array because the same
				// widget could be used more than once.
				$list[] = $widget;
			}
		}

		return $list;
	}

	/**
	 * Returns a list containing all the supported widgets.
	 *
	 * @param 	string 	$location  The location in which the widgets have been published,
	 *                             such as 'dashboard' or 'finance'.
	 * @param 	mixed 	$user      The user that configured the widgets. If not
	 * 					           specified, the current one will be used.
	 *
	 * @return 	array 	An associative array of widgets.
	 *
	 * @uses 	getInstance()
	 */
	public static function getSupportedWidgets($location, $user = null)
	{
		if (!$user instanceof JUser)
		{
			// load specified/current user
			$user = JFactory::getUser($user);
		}

		$widgets = array();
		$files   = array();

		// prepare base path in which the widgets are stored
		$basePath = VAPLIB . DIRECTORY_SEPARATOR . 'statistics' . DIRECTORY_SEPARATOR . 'widgets';
		// recursively load all files, since they are grouped in categories
		$files = JFolder::files($basePath, $filter = '\.php$', $fullPath = true, $recursive = true);

		// map files to route path to file name
		$files = array_map(function($file) use ($basePath)
		{
			// get rid of base path
			$file = str_replace($basePath, '', $file);
			return ltrim(preg_replace("/[\/\\\\]+/", '_', $file), '_');
		}, $files);

		/**
		 * Trigger event to let the plugins register external widgets
		 * to extend the statistics dashboard without editing any core files.
		 *
		 * @return 	array 	A list of supported widgets.
		 *
		 * @since 	1.7
		 */
		$list = VAPFactory::getEventDispatcher()->trigger('onFetchSupportedStatisticsWidgets');

		foreach ($list as $chunk)
		{
			// merge default files with specified ones
			$files = array_merge($files, (array) $chunk);
		}

		// iterate files
		foreach ($files as $file)
		{
			try
			{
				// try to instantiate the widget
				$widget = static::getInstance($file);

				// instantiation went fine, make sure the widget supports the requested group
				// and the permissions of the given user
				if ($widget->isSupported($location) && $widget->checkPermissions($user))
				{
					// group supported, register widget within the list
					$widgets[$widget->getName()] = $widget;
				}
			}
			catch (Exception $e)
			{
				// widget not supported
			}
		}

		// sort widgets alphabetically
		uasort($widgets, function($a, $b)
		{
			// compare as normal strings
			return strcasecmp($a->getTitle(), $b->getTitle());
		});

		return $widgets;
	}

	/**
	 * Returns the statistics widget instance, ready for the usage.
	 * Searches for any arguments set in the request required by the widget.
	 *
	 * @param 	string 	$widget   The widget name/filename.
	 * @param 	mixed 	$options  Either an array or an object of options to be passed 
	 * 							  to the order instance.
	 *
	 * @return 	VAPStatisticsWidget
	 *
	 * @throws 	Exception
	 *
	 * @uses 	getInstance()
	 */
	public static function getWidget($widget, $options = array())
	{
		// get widget first
		$widget = static::getInstance($widget, $options);

		$input = JFactory::getApplication()->input;

		// iterate form arguments
		foreach ($widget->getForm() as $k => $field)
		{
			// use registry for ease of use
			$field = new JRegistry($field);

			// validate field filter
			if ($field->get('multiple') == 1)
			{
				// retrieve an array
				$filter = 'array';
			}
			else
			{
				// retrieve a string otherwise
				$filter = 'string';
			}

			// get value from request
			$value = $input->get($k, null, $filter);

			// cast to integer in case of checkbox, 
			// so that we can use "0" instead of NULL
			if ($field->get('type') == 'checkbox')
			{
				$value = (int) $value;
			}

			// make sure we have a value in the request
			if ($value !== null)
			{
				// Push value within the options.
				// Any value previously set will be overwritten.
				$widget->setOption($k, $value);
			}

			// check if the field was mandatory
			if ($field->get('required') == 1)
			{
				// get value from widget configuration
				$value = $widget->getOption($k, null);

				// make sure the value exists
				if ($value === null || $value === array())
				{
					// missing field, throw exception
					throw new Exception(sprintf('Statistics widget missing required [%s] field', $k), 400);
				}
			}
		}

		return $widget;
	}

	/**
	 * Returns the statistics widget instance.
	 *
	 * @param 	string 	$widget   The widget name/filename.
	 * @param 	mixed 	$options  Either an array or an object of options to be passed 
	 * 							  to the order instance.
	 *
	 * @return 	VAPStatisticsWidget
	 *
	 * @throws 	Exception
	 */
	public static function getInstance($widget, $options = array())
	{
		// remove file extension if provided
		$widget = preg_replace("/\.php$/", '', $widget);

		/**
		 * Trigger event to let the plugins include external widgets.
		 * The plugins MUST include the resources needed, otherwise
		 * it wouldn't be possible to instantiate the returned classes.
		 *
		 * @param 	string  $widget  The name of the widget to include.
		 *
		 * @return 	string 	The classname of the widget.
		 *
		 * @since 	1.7
		 */
		$classname = VAPFactory::getEventDispatcher()->triggerOnce('onFetchStatisticsWidgetClassname', array($widget));

		if (!$classname)
		{
			// replace underscores with dots to use folder notation
			$pathId = preg_replace("/_+/", '.', $widget);

			// attempt to load file by using the group notation
			if (!VAPLoader::import('libraries.statistics.widgets.' . $pathId))
			{
				// fallback to default folder
				if (!VAPLoader::import('libraries.statistics.widgets.' . $widget))
				{
					throw new Exception(sprintf('Statistics widget [%s] not found', $widget), 404);
				}
			}

			// replace every non-alnum character with a blank space
			$widget = preg_replace("/[^a-zA-Z0-9]+/", ' ', $widget);
			// make widget name camel case and get rid of blank spaces
			$widget = preg_replace("/\s+/", '', ucwords($widget));

			// create class name
			$classname = 'VAPStatisticsWidget' . $widget;
		}

		// make sure the class handler exists
		if (!class_exists($classname))
		{
			throw new Exception(sprintf('Statistics widget class [%s] does not exist', $classname), 404);
		}

		// instantiate handler
		$widget = new $classname($options);

		// make sure the widget is a valid instance
		if (!$widget instanceof VAPStatisticsWidget)
		{
			throw new Exception(sprintf('Statistics widget class [%s] is not a valid instance', $classname), 500);
		}

		return $widget;
	}

	/**
	 * Returns a list containing all the supported positions in which the widget can be placed.
	 *
	 * @param 	string 	 $location  The location in which the widgets have been published,
	 *                              such as 'dashboard' or 'finance'.
	 * @param 	mixed 	 $user      The user that configured the widgets. If not specified,
	 *                              the current one will be used.
	 *
	 * @return 	array 	 A list of positions.
	 */
	public static function getSupportedPositions($location = null, $user = null)
	{
		$dbo = JFactory::getDbo();

		if (!$user instanceof JUser)
		{
			// load specified/current user
			$user = JFactory::getUser($user);
		}

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('position', 'id_user')))
			->from($dbo->qn('#__vikappointments_stats_widget'))
			->where($dbo->qn('id_user') . ' IN (0, ' . (int) $user->id . ')')
			->group($dbo->qn('position'))
			->group($dbo->qn('id_user'))
			->order($dbo->qn('id_user') . ' DESC')
			->order($dbo->qn('ordering') . ' ASC');

		if ($location)
		{
			$q->where($dbo->qn('location') . ' = ' . $dbo->q($location));
		}

		$dbo->setQuery($q);
		$widgets = $dbo->loadObjectList();

		if ($widgets)
		{
			// in case the first widget is not global, take only
			// the widgets that are assigned to the specified user
			if ($widgets[0]->id_user > 0)
			{
				// filter the array and exclude widgets with `id_user` equals to "0"
				$widgets = array_values(array_filter($widgets, function($widget)
				{
					return $widget->id_user > 0;
				}));
			}

			// return only the position
			return array_map(function($widget)
			{
				return $widget->position;
			}, $widgets);
		}

		return array();
	}
}
