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
 * This class is a bridge to implement and extend the methods
 * provided by the Joomla! framework to trigger and dispatch 
 * the plugin events.
 *
 * @since 1.6
 * @since 1.7  Renamed from UIEventDispatcher
 */
class VAPEventDispatcher
{
	/**
	 * A list of instances.
	 *
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * The class used to dispatch events.
	 *
	 * @var mixed
	 */
	protected $dispatcher;

	/**
	 * An array of options to push always as last
	 * element within the event arguments.
	 * This array may contain information about the 
	 * caller, such as alias, version and client.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Returns a new instance of this object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param 	array 	$option 	 An array of options.
	 * @param 	mixed 	$dispatcher  The real dispatcher instance.
	 *
	 * @return 	self 	A new instance.
	 */
	public static function getInstance(array $options = array(), $dispatcher = null)
	{
		$sign = is_object($dispatcher) ? get_class($dispatcher) : null;

		if (!isset(static::$instances[$sign]))
		{
			static::$instances[$sign] = new static($options, $dispatcher);
		}
		else
		{
			// the instance already exists, update all the options
			foreach ($options as $k => $v)
			{
				static::$instances[$sign]->setOption($k, $v);
			}
		}

		return static::$instances[$sign];
	}

	/**
	 * Class constructor.
	 *
	 * @param 	array 	$option 	 An array of options.
	 * @param 	mixed 	$dispatcher  The real dispatcher instance.
	 *
	 * @uses 	import()
	 */
	public function __construct(array $options = array(), $dispatcher = null)
	{
		if (is_null($dispatcher))
		{
			/**
			 * In case of Joomla 4, use the main application
			 * as event dispatcher.
			 *
			 * @since 1.7
			 */
			if (VersionListener::isJoomla4x())
			{
				$dispatcher = array(JFactory::getApplication(), 'triggerEvent');
			}
			else
			{
				$dispatcher = JEventDispatcher::getInstance();
			}
		}

		$this->dispatcher = $dispatcher;
		$this->options    = $options;

		// always import plugins that belong to "vikappointments" and "e4j" folders
		$this->import(array('vikappointments', 'e4j'));
	}

	/**
	 * Updates an option value.
	 *
	 * @param 	string 	$key 	The option name.
	 * @param 	mixed 	$value 	The option value.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function setOption($key, $value)
	{
		$this->option[$key] = $value;

		return $this;
	}

	/**
	 * Imports all the plugins that belong to the specified group.
	 *
	 * @param 	mixed 	$group 	The plugins folder to import or a list of folders.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	public function import($group)
	{
		// cast the group to an array as it may be possible
		// to import multiple folders at once
		foreach ((array) $group as $folder)
		{
			JPluginHelper::importPlugin($folder);
		}

		return $this;
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and returning all their values.
	 *
	 * @param 	mixed 	$event  The event to trigger or an array containing the 
	 * 							event name [0] and the plugins folder to import [1].
	 * @param 	array 	$args   An array of arguments.
	 *
	 * @return 	array 	An array of results from each function call.
	 *
	 * @uses 	import()
	 */
	public function trigger($event, array $args = array())
	{
		if (is_array($event))
		{
			$arr = $event;

			// an array was passed, obtain the event name and the
			// folder name to import all the related plugins
			$event = array_shift($arr);
			$group = array_shift($arr);

			if ($group)
			{
				// groups was specified, import the plugins
				$this->import($group);
			}
		}

		if ($this->options)
		{
			// push the options as last argument
			$args[] = $this->options;
		}

		if (is_array($this->dispatcher))
		{
			// retrieve dispatcher instance and the related trigger method
			list($dispatcher, $trigger) = $this->dispatcher;
		}
		else
		{
			// use the default trigger method instead
			$dispatcher = $this->dispatcher;
			$trigger    = 'trigger';
		}

		// trigger event with the specified dispatcher
		$result = $dispatcher->{$trigger}($event, $args);

		if (!preg_match("/web_?hook/i", $event))
		{
			// in case the event doesn't contain "webhook" word, register related web hook
			VAPLoader::import('libraries.webhook.queue');
			VAPWebHookQueue::getInstance()->register($event, $args);
		}

		return $result;
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and returning only the first value.
	 *
	 * @param 	mixed 	$event  The event to trigger or an array containing the 
	 * 							event name and the plugins folder to import.
	 * @param 	array 	$args   An array of arguments.
	 *
	 * @return 	mixed 	The first value.
	 *
	 * @uses 	trigger()
	 */
	public function triggerOnce($event, array $args = array())
	{
		$res = $this->trigger($event, $args);

		// get the first positive (bool) value
		foreach ($res as $value)
		{
			if ($value)
			{
				return $value;
			}
		}

		// No positive value, the array is probably empty or
		// contains empty values (null, false, empty string or 0).
		// Get the first one in case the array has length, otherwise
		// null will be returned.
		return array_shift($res);
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and make sure at least a plugin returned a positive value (bool).
	 *
	 * @param 	mixed 	 $event  The event to trigger or an array containing the 
	 * 							 event name and the plugins folder to import.
	 * @param 	array 	 $args   An array of arguments.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	trigger()
	 */
	public function is($event, array $args = array())
	{
		$res = $this->trigger($event, $args);

		return (bool) array_filter($res);
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and make sure at least a plugin returned a negative value (bool).
	 *
	 * @param 	mixed 	 $event  The event to trigger or an array containing the 
	 * 							 event name and the plugins folder to import.
	 * @param 	array 	 $args   An array of arguments.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	trigger()
	 */
	public function not($event, array $args = array())
	{
		$res = $this->trigger($event, $args);

		foreach ($res as $r)
		{
			if (!$r)
			{
				// negative response
				return true;
			}
		}

		// no response or only successful values
		return false;
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and make sure at least a plugin returned TRUE.
	 *
	 * @param 	mixed 	 $event  The event to trigger or an array containing the 
	 * 							 event name and the plugins folder to import.
	 * @param 	array 	 $args   An array of arguments.
	 *
	 * @return 	boolean  True if verified, false otherwise.
	 *
	 * @since 	1.7
	 *
	 * @uses 	trigger()
	 */
	public function true($event, array $args = array())
	{
		$res = $this->trigger($event, $args);

		return in_array(true, $res, true);
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and make sure at least a plugin returned FALSE.
	 *
	 * @param 	mixed 	 $event  The event to trigger or an array containing the 
	 * 							 event name and the plugins folder to import.
	 * @param 	array 	 $args   An array of arguments.
	 *
	 * @return 	boolean  True if verified, false otherwise.
	 *
	 * @since 	1.7
	 *
	 * @uses 	trigger()
	 */
	public function false($event, array $args = array())
	{
		$res = $this->trigger($event, $args);

		return in_array(false, $res, true);
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and make sure at least a plugin returned TRUE.
	 * Alternatively it looks for an element equals to FALSE.
	 *
	 * @param 	mixed 	 $event  The event to trigger or an array containing the 
	 * 							 event name and the plugins folder to import.
	 * @param 	array 	 $args   An array of arguments.
	 *
	 * @return 	mixed    A boolean in case of true/false, null otherwise.
	 *
	 * @since 	1.7
	 *
	 * @uses 	trigger()
	 */
	public function trueOrFalse($event, array $args = array())
	{
		$res = $this->trigger($event, $args);

		if (in_array(true, $res, true))
		{
			return true;
		}

		if (in_array(false, $res, true))
		{
			return false;
		}

		return null;
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and make sure at least a plugin returned FALSE.
	 * Alternatively it looks for an element equals to TRUE.
	 *
	 * @param 	mixed 	 $event  The event to trigger or an array containing the 
	 * 							 event name and the plugins folder to import.
	 * @param 	array 	 $args   An array of arguments.
	 *
	 * @return 	mixed    A boolean in case of false/true, null otherwise.
	 *
	 * @since 	1.7
	 *
	 * @uses 	trigger()
	 */
	public function falseOrTrue($event, array $args = array())
	{
		$res = $this->trigger($event, $args);

		if (in_array(false, $res, true))
		{
			return false;
		}

		if (in_array(true, $res, true))
		{
			return true;
		}

		return null;
	}

	/**
	 * Triggers an event by dispatching arguments to all observers that handle
	 * the specified event and filters the returned values to take only numbers.
	 *
	 * @param 	mixed 	 $event  The event to trigger or an array containing the 
	 * 							 event name and the plugins folder to import.
	 * @param 	array 	 $args   An array of arguments.
	 *
	 * @return 	array    The returned values.
	 *
	 * @since 	1.7
	 *
	 * @uses 	trigger()
	 */
	public function numbers($event, array $args = array())
	{
		$res = $this->trigger($event, $args);

		// filter the returned values and take only integers and floats
		return array_filter($res, function($return)
		{
			return is_int($return) || is_float($return);
		});
	}
}
