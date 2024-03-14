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
 * Web hook payload encapsulation.
 *
 * @since 1.7
 */
class VAPWebHook
{
	/**
	 * A list of include paths.
	 *
	 * @var array
	 */
	protected static $includePaths = array();

	/**
	 * The hook name.
	 *
	 * @var string
	 */
	protected $hook;

	/**
	 * The payload to send to the end-point.
	 *
	 * @var mixed
	 */
	protected $payload;

	/**
	 * Returns the proper instance able to handle the specified
	 * web hook action.
	 *
	 * @param 	string 	 $hook     The hook name.
	 * @param 	mixed    $payload  The payload to delivery.
	 *
	 * @return 	self
	 */
	public static function getInstance($hook, $payload = array())
	{
		// get rid of initial "on"
		$name = preg_replace("/^on/", '', $hook);
		$filename = strtolower($name);

		// use the default class name
		$classname = 'VAPWebHook' . $name;

		// save time by immediately checking whether the class already exists
		if (!class_exists($name))
		{
			$found = false;

			// iterate all include paths
			foreach (static::getIncludePaths() as $path)
			{
				// attempt to load file from given include path
				$found = $found || VAPLoader::import($classname, $path);
			}

			// check whether the file exists or has been already imported
			if ($found || VAPLoader::import('libraries.webhook.classes.' . $filename))
			{
				if (!class_exists($classname))
				{
					// invalid class, throw exception
					throw new RuntimeException(sprintf('Web Hook [%s] not found', $classname), 500);
				}
			}
			else
			{
				// missing handler, use default class
				$classname = 'VAPWebHook';
			}
		}

		// instantiate matching class
		return new $classname($hook, $payload);
	}

	/**
	 * Returns a list of supported web hook handlers.
	 *
	 * @param 	boolean  $object  True to return web hook instances, false to include
	 *                            only their names.
	 *
	 * @return 	array    An associative array containing the hook event (key) and the
	 *                   hook name/instance (value).
	 */
	public static function getSupportedHooks($object = false)
	{
		$hooks = array();

		// get all include paths
		$paths = static::getIncludePaths();
		// append the default directory
		$paths[] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes';

		foreach ($paths as $dir)
		{
			// get all PHP files contained within this folder
			$files = glob(rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.php');

			foreach ($files as $file)
			{
				// extract hook name from file path
				$hookname = preg_replace("/\.php$/i", '', basename($file));

				try
				{
					// create webhook instance
					$webhook = static::getInstance($hookname);

					if ($object)
					{
						// save the whole webhook instance
						$hooks[$webhook->getHook()] = $webhook;	
					}
					else
					{
						// register only the webhook name
						$hooks[$webhook->getHook()] = $webhook->getName();
						// free space
						unset($webhook);
					}
				}
				catch (Exception $e)
				{
					// catch error and propagate system message without breaking the flow
					JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				}
			}
		}

		// sort web hooks by name (preserve association)
		uasort($hooks, function($a, $b)
		{
			if ($a instanceof VAPWebHook)
			{
				$a = $a->getName();
			}

			if ($b instanceof VAPWebHook)
			{
				$b = $b->getName();
			}

			return strcmp($a, $b);
		});

		return $hooks;
	}

	/**
	 * Class constructor.
	 *
	 * @param 	string 	 $hook     The hook name.
	 * @param 	mixed    $payload  The payload to delivery.
	 */
	public function __construct($hook, $payload)
	{
		$this->hook    = $hook;
		$this->payload = $payload;
	}

	/**
	 * Returns a readable name of the hook.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		// by default use the hook as name
		return $this->hook;
	}

	/**
	 * Returns an associative array of supported parameters.
	 *
	 * @return 	array
	 */
	public function getForm()
	{
		// by default no supported parameters
		return array();
	}

	/**
	 * Returns the registered hook name.
	 *
	 * @return 	string
	 */
	public function getHook()
	{
		return $this->hook;
	}

	/**
	 * Returns the registered payload.
	 *
	 * @param 	mixed  $options  Either an array or an object, which should contain
	 *                           the value of the specified parameters.
	 *
	 * @return 	mixed
	 */
	public function getPayload($options = array())
	{
		if (is_array($this->payload) && array_keys($this->payload) === range(0, count($this->payload) - 1))
		{
			$arr = array();

			// we have a sequential array, we need to name the properties of the list so that
			// the receiver can fetch them in a better way
			foreach ($this->payload as $i => $v)
			{
				$arr['arg' . $i] = $v;
			}

			return $arr;
		}

		return $this->payload;
	}

	/**
	 * Comparator to check whether 2 instances share the same payload parent.
	 *
	 * @return 	boolean
	 */
	public function equalsTo($webhook)
	{
		// always different by default
		return false;
	}

	/**
	 * Extends the current payload with the specified data.
	 *
	 * @param 	mixed  $data  The additional details to inject.
	 *
	 * @return 	void
	 */
	public function extend($data)
	{
		if ($data instanceof VAPWebHook)
		{
			// extract payload from object
			$data = $data->getPayload();
		}

		// convert both the payloads into associative arrays
		$tmp  = json_decode(json_encode($this->payload), true);
		$data = json_decode(json_encode($data), true);

		$tmp  = is_array($tmp)  ? $tmp  : (array) $tmp;
		$data = is_array($data) ? $data : (array) $data;

		// merge both the payload details
		$this->payload = array_merge($tmp, $data);
	}

	/**
	 * Gets a list of supported include paths.
	 *
	 * @return  array
	 */
	public static function getIncludePaths()
	{
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
	 */
	public static function setIncludePaths($paths)
	{
		static::$includePaths = (array) $paths;
	}
}
