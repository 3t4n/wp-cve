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

VAPLoader::import('libraries.widget.input');

/**
 * Base interface to display a custom input.
 *
 * @since 	1.6
 */
class UIWidgetLayout
{
	/**
	 * Get an instance of the specified widget.
	 *
	 * @param 	string 	 $widget 	The widget to load.
	 * @param 	mixed 	 ... 		A list of parameters to use for the widget.
	 *
	 * @return 	UIInput  The instantiated widget.
	 *
	 * @throws 	Exception
	 */
	public static function getInstance()
	{
		// get method arguments
		$args = func_get_args();

		// make sure the first argument is the name of the widget
		if (!count($args))
		{
			throw new Exception('Missing required argument', 500);
		}

		// pop the widget from the arguments
		$widget = strtolower(array_shift($args));

		// try to load the widget file
		if (!VAPLoader::import('libraries.widget.' . $widget . '.' . $widget))
		{
			throw new Exception('Widget [' . $widget . '] not found', 404);
		}

		// get the reflection of the class
		$reflect = new ReflectionClass('UI' . ucwords($widget));

		// construct the class with the specified argument.
		$obj = $reflect->newInstanceArgs($args);

		// make sure the object is an instance of UIInput
		if ($obj instanceof UIInput === false)
		{
			throw new Exception('The widget must be an instance of UIInput', 500);
		}

		return $obj;
	}
}
