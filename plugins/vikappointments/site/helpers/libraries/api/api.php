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
 * VikAppointments API base framework.
 * This class is used to run all the installed plugins in a given directory.
 *
 * All the events are runnable only if the user is correctly authenticated.
 *
 * @see VAPApiUser
 * @see VAPApiResponse
 * @see VAPApiError
 * @see VAPApiEvent
 *
 * @since 1.7
 */
abstract class VAPApi
{
	/**
	 * The path of the folder containing all the available plugins.
	 *
	 * @var array
	 */
	private $includePaths = array();

	/**
	 * True if the API framework is enabled and accessible.
	 *
	 * @var boolean
	 */
	private $enabled = true;	

	/**
	 * The instance of the user which is using the API framework.
	 *
	 * @var VAPApiUser
	 */
	private $user = null;

	/**
	 * The last error caught.
	 *
	 * @var VAPApiError
	 */
	private $error = null;

	/**
	 * The array that contains the configuration keys.
	 *
	 * @var array
	 */
	private $config = array();

	/**
	 * Flag used to avoid sending the headers while outputting the events data.
	 *
	 * @var boolean
	 */
	protected $sendHeaders = true;

	/**
	 * The instance of the API framework.
	 *
	 * @var VAPApi
	 */
	protected static $instance = null;

	/**
	 * Class constructor.
	 * @protected This class can be accessed only through the static getInstance() method.
	 *
	 * @param 	string 	$path  The dir path containing all the plugins.
	 *
	 * @see 	getInstance()
	 */
	protected function __construct($path = null)
	{
		if (empty($path))
		{
			// use default folder if not specified
			$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'plugins';
		}

		// set include paths
		$this->setIncludePaths($path);
	}

	/**
	 * Class cloner.
	 */
	private function __clone()
	{
		// cloning function not accessible
	}

	/**
	 * Get the instance of the API object.
	 * 
	 * @param 	string 	$path 	The dir path containing all the plugins.
	 *
	 * @return 	VAPApi 	The instance of the API framework.
	 */
	public static function getInstance($path = null)
	{
		if (static::$instance === null)
		{
			static::$instance = new static($path);
		}

		return static::$instance;
	}

	/**
	 * Return true if the APIs framework is enabled and accessible.
	 *
	 * @return 	boolean	 True if enabled, otherwise false.
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Enable the API framework.
	 *
	 * @return 	self  This object to support chaining.
	 */
	protected function enable()
	{
		$this->enabled = true;

		return $this;
	}

	/**
	 * Disable the API framework.
	 *
	 * @return 	self  This object to support chaining.
	 */
	protected function disable()
	{
		$this->enabled = false;

		return $this;
	}

	/**
	 * Return true if the user is correctly logged.
	 *
	 * @return 	boolean	 True if logged, otherwise false.
	 */
	public function isConnected()
	{
		return $this->user !== null && $this->user->id();
	}

	/**
	 * Return the object of the logged user.
	 *
	 * @return 	VAPApiUser	The object of the user connected, otherwise NULL.
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Disconnect the user.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function disconnect()
	{
		$this->user = null;

		return $this;
	}

	/**
	 * Get the path of the specified event.
	 *
	 * @return 	mixed  The event path if exists, false otherwise.
	 */
	public function getEventPath($event)
	{
		// get all include paths
		$paths = $this->getIncludePaths();

		// trim trailing .php from event name
		$event = preg_replace("/\.php$/i", '', $event);
		
		// iterate supported paths
		foreach ($paths as $path)
		{
			// build event path
			$tmp = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $event . '.php';

			// make sure the file exists
			if (is_file($tmp))
			{
				return $tmp;
			}
		}

		return false;
	}

	/**
	 * Gets a list of supported include paths.
	 *
	 * @return  array
	 */
	public function getIncludePaths()
	{
		return $this->includePaths;
	}

	/**
	 * Adds one path to include in plugin search.
	 * Proxy of addIncludePaths().
	 *
	 * @param   string  $path  The path to search for plugins.
	 *
	 * @return  self 	This object to support chaining.
	 *
	 * @uses 	addIncludePaths()
	 */
	public function addIncludePath($path)
	{
		return $this->addIncludePaths($path);
	}

	/**
	 * Adds one or more paths to include in plugin search.
	 *
	 * @param   mixed  $paths  The path or array of paths to search for plugins.
	 *
	 * @return  self   This object to support chaining.
	 *
	 * @uses 	getIncludePaths()
	 * @uses 	setIncludePaths()
	 */
	public function addIncludePaths($paths)
	{
		if (empty($paths))
		{
			return $this;
		}

		$includePaths = $this->getIncludePaths();

		// in case the path is an array, merge all the paths and make sure we have no duplicated
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
		$this->setIncludePaths($includePaths);

		return $this;
	}

	/**
	 * Sets the include paths to search for plugins.
	 *
	 * @param   array  $paths  Array with paths to search in.
	 *
	 * @return  self   This object to support chaining.
	 */
	public function setIncludePaths($paths)
	{
		$this->includePaths = (array) $paths;

		return $this;
	}

	/**
	 * Connect the specified user to the API framework.
	 *
	 * In case the login fails, here is evaluated a permanent BAN.
	 * Otherwise the MANIFEST of the user is updated and the BAN is reset.
	 *
	 * This method can raise the following internal errors:
	 * - 100 = Authentication Error (Generic)
	 * - 101 = The username is empty or invalid
	 * - 102 = The password is empty or invalid
	 * - 104 = The account is blocked
	 *
	 * @param 	VAPApiUser  $user  The object to represent the user login.
	 *
	 * @return 	boolean     True if the user is accepted, otherwise false.
	 */
	public function connect(VAPApiUser $user)
	{
		// check if API framework is enabled
		if (!$this->isEnabled())
		{
			// do not log anything and stop flow
			return false;
		}

		// check if the user is banned
		// and the user is connectable
		// and the login connection returns a valid user ID
		if (
			!($banned = $this->isBanned($user)) 
			&& $user->isConnectable() 
			&& ($id_user = $this->doConnection($user)) !== false
		) {
			// setup the user and fill the ID
			$this->user = $user;
			$this->user->assign($id_user);

			// update user manifest
			$this->updateUserManifest();

			$this->resetBan($this->user);

			return true;
		}

		// login failed : if user is not yet banned, evaluate a ban
		if (!$banned && $this->needBan($user))
		{
			// ban the user
			$this->ban($user);
		}

		// only if the user is not banned
		// register the failure of the login (no event is reported)
		if (!$banned)
		{
			$credentials = $user->getCredentials();
			
			$text = sprintf(
				'Authentication Error! Authentication error for user {%s : %s}.',
				$credentials->username,
				$credentials->password
			);

			$this->registerEvent(null, new VAPApiResponse(0, $text));
		}

		if ($banned)
		{
			// set error : user banned
			$this->setError(104, 'Authentication Error! This account is blocked.');
		}
		else if (!strlen($user->getUsername()))
		{
			// set error : username empty
			$this->setError(101, 'Authentication Error! The username is empty or invalid.');
		}
		else if (!strlen($user->getPassword()))
		{
			// set error : password empty
			$this->setError(102, 'Authentication Error! The password is empty or invalid.');
		}
		else if (!$this->hasError())
		{
			// no err specified yet : set a generic authentication error
			$this->setError(100, 'Authentication Error!');
		}

		return false;
	}

	/**
	 * Trigger the specified event.
	 * Accessible only in case the user is correctly connected.
	 *
	 * This method can raise the following internal errors:
	 * - 100 = Authentication Error (Generic)
	 * - 201 = The event requested does not exist
	 * - 202 = The event requested is not valid
	 * - 203 = The event requested is not runnable
	 * - 204 = The event requested is not authorized
	 * - 500 = Internal error of the plugin executed
	 *
	 * The response of the plugin is always echoed.
	 *
	 * @param 	string	 $event 	The filename of the plugin to run.
	 * @param 	array 	 $args 		The arguments to pass within the plugin.
	 * @param 	boolean  $register 	True to register the response, otherwise false to skip it.
	 *
	 * @return 	boolean	 True if the plugin is executed without errors.
	 */
	public function trigger($event, array $args = array(), $register = true)
	{
		// check if API framework is still enabled
		if (!$this->isEnabled() || !$this->isConnected())
		{
			// this condition can be verified only when triggered manually
			$this->setError(100, 'Authentication Error');
			return false;
		}

		$obj = null;

		$response = new VAPApiResponse();

		if ($event)
		{
			// the event requested does not exist (?) : define response and error
			$response->setStatus(0)->setContent('File Not Found! The event requested does not exist.');
			$this->setError(201, $response->getContent());

			$eventPath = $this->getEventPath($event);
		}
		else
		{
			// prevent fatal errors in case the event is missing
			$response->setStatus(0)->setContent('Missing event.');
			$this->setError(200, $response->getContent());

			$eventPath = false;
		}

		if ($eventPath)
		{
			// COMMIT : the event exists

			// the event is not valid (?) : define response and error
			$response->setContent('Event Not Found! The event requested is not valid.');
			$this->setError(202, $response->getContent());

			$event_clazz = str_replace('_', ' ', $event);
			$event_clazz = ucwords($event_clazz);
			$event_clazz = str_replace(' ', '', $event_clazz);

			$event_clazz = 'VAPApiEvent' . $event_clazz;

			require_once $eventPath;

			if (class_exists($event_clazz))
			{
				// COMMIT : the event is valid

				// the event does not own a runnable method (?) : define response and error 
				$response->setContent('Run Method Not Accessible! The event requested is not runnable.');
				$this->setError(203, $response->getContent());

				// Invoke abstract method to load the configuration of the event.
				// This way, the framework implementor can retrieve the preferences
				// by using the preferred storage system.
				$options = $this->loadEventConfig($event);

				// instantiate runnable event
				$obj = new $event_clazz($event, $options);

				if ($obj instanceof VAPApiEvent)
				{
					// COMMIT : the event is runnable

					// the user is not authorized to run the event (?) : define response and error 
					$response->setContent('Event Authorization Error! The event requested is not authorized.');
					$this->setError(204, $response->getContent());

					if ($this->user->authorise($obj))
					{
						// COMMIT : the user is authorized

						// clear the response error
						$response->clearContent();

						try
						{
							// run the event, which is able to modify the response
							$output = $obj->run($args, $response);
						}
						catch (Exception $e)
						{
							// Catch any exception that might have been thrown
							// by the dispatched event. Generates an error
							// according to the VAPApiError specifications.
							$output = new VAPApiError($e->getCode(), $e->getMessage());
						}

						// invoke abstract method to save the configuration of the event
						$this->saveEventConfig($obj);

						// register request payload for extended logging
						$response->setPayload($args);

						if ($response->isVerified())
						{
							// call get error function to clean all
							$this->getError();

							// safely output the response fetched by the event
							$this->output($output, $response->getContentType());
						}
						else
						{
							if ($output instanceof VAPApiError)
							{
								// set error retrieved from plugin
								$this->setError($output);
							}
							else
							{
								// generic event error (500) : get details from response
								$this->setError(500, $response->getContent());
							}
						}
					}
				}
			}
		}

		// register event and response
		if ($register)
		{
			$this->registerEvent($obj, $response);
		}

		return $response->isVerified();
	}

	/**
	 * Dispatch the specified event to catch the response echoed from the plugin.
	 * Accessible only in case the user is correctly connected.
	 *
	 * This method can raise the following internal errors:
	 * - 100 = Authentication Error (Generic)
	 * - 201 = The event requested does not exist
	 * - 202 = The event requested is not valid
	 * - 203 = The event requested is not runnable
	 * - 204 = The event requested is not authorized
	 * - 500 = Internal error of the plugin executed
	 *
	 * @param 	string	 $event 	The filename of the plugin to run.
	 * @param 	array 	 $args 		The arguments to pass within the plugin.
	 * @param 	boolean  $register 	True to register the response, otherwise false to skip it.
	 *
	 * @return 	string	 The response echoed from the plugin on success.
	 *
	 * @uses 	trigger() 	Trigger the event to catch the response.
	 *
	 * @throws 	Exception
	 */
	public function dispatch($event, array $args = array(), $register = false)
	{
		// temporarily lock the headers
		$headers = $this->sendHeaders;
		$this->sendHeaders = false;

		// start catching the response echoed
		ob_start();
		// trigger the plugin and get the verified status
		$verified = $this->trigger($event, $args, $register);
		// get the response echoed
		$contents = ob_get_contents();
		// stop catching
		ob_end_clean();

		// unlock the headers (by setting the previous value)
		$this->sendHeaders = $headers;

		if ($verified)
		{
			return $contents;
		}

		// get error
		$err = $this->getError();
		// raise exception
		throw new Exception($err->error, $err->errcode);
	}

	/**
	 * Set the last error caught.
	 *
	 * @param 	mixed 	$code  Either the error code identifier or the error instance.
	 * @param 	string 	$str   A text description of the error.
	 *
	 * @return 	self    This object to support chaining.
	 */
	protected function setError($code, $str = '')
	{
		if ($code instanceof VAPApiError)
		{
			$this->error = $code;
		}
		else
		{
			$this->error = new VAPApiError($code, $str);
		}

		return $this;
	}

	/**
	 * Get the last error caught and clean it.
	 *
	 * @return 	VAPApiError  The error object if exists, otherwise NULL.
	 */
	public function getError()
	{
		$err = $this->error;
		$this->error = null;
		return $err;
	}

	/**
	 * Return true if an error has been raised.
	 *
	 * @return 	boolean  True in case of error, otherwise false.
	 */
	public function hasError()
	{
		return $this->error !== null;
	}

	/**
	 * Check if the specified key is set in the configuration.
	 *
	 * @param 	string 	 $key 	The configuration key to check.
	 *
	 * @return 	boolean  True if exists, otherwise false.
	 */
	public function has($key)
	{
		return array_key_exists($key, $this->config);
	}

	/**
	 * Get the configuration value of the specified setting.
	 *
	 * @param 	string 	$key 	The key of the configuration value to get.
	 * @param 	mixed 	$def 	The default value if not exists.
	 *
	 * @return 	mixed 	The configuration value if exists, otherwise the default value.
	 *
	 * @uses 	has() 	Check if the setting exists.
	 */
	public function get($key, $def = null)
	{
		if ($this->has($key))
		{
			return $this->config[$key];
		}

		return $def;
	}

	/**
	 * Set the configuration value for the specified setting.
	 *
	 * @param 	string 	$key  The key of the configuration value to set.
	 * @param 	string 	$val  The configuration value to set.
	 *
	 * @return 	self    This object to support chaining.
	 */
	public function set($key, $val)
	{
		$this->config[$key] = $val;

		return $this;
	}

	/**
	 * Get the object of the given plugin name, otherwise return all the installed plugins if not specified.
	 *
	 * @param 	string 	$plg_name   The name of the plugin to get.
	 *                              If not specified it will be replaced by "*" (all plugins).
	 *
	 * @return 	array 	A list of the plugins found.
	 */
	public function getPluginsList($plg_name = '')
	{
		// if the plugin name is empty or NULL
		if ($plg_name === null || empty($plg_name))
		{
			// get all the installed plugins
			$plg_name = '*';
		}

		$paths = array();

		foreach ($this->getIncludePaths() as $dir)
		{
			// retrieve all the plugin that match the query
			$paths = array_merge($paths, glob(rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "$plg_name.php"));
		}

		$plugins = array();

		foreach ($paths as $p)
		{
			// require the plugin file
			require_once $p;

			// get the filename from full path
			$event = substr($p, ($n = strrpos($p, DIRECTORY_SEPARATOR) + 1), strrpos($p, '.') - $n);

			// convert the filename in classname
			$event_clazz = str_replace('_', ' ', $event);
			$event_clazz = ucwords($event_clazz);
			$event_clazz = str_replace(' ', '', $event_clazz);

			$event_clazz = 'VAPApiEvent' . $event_clazz;

			if (class_exists($event_clazz))
			{
				$obj = new $event_clazz($event);

				if ($obj instanceof VAPApiEvent)
				{
					$plugins[] = $obj;
				}
			}
		}

		// sort plugins by name since they might be located on different folders
		usort($plugins, function($a, $b)
		{
			return strcmp($a->getName(), $b->getName());
		});

		return $plugins;
	}

	/**
	 * Authenticate the provided user and connect it on success.
	 *
	 * @param 	VAPApiUser  $user  The object of the user.
	 *
	 * @return 	integer     The ID of the user on success, otherwise false.
	 */
	abstract protected function doConnection(VAPApiUser $user);

	/**
	 * Check if the provided user has been banned.
	 * This action is executed only before the authentication.
	 * The ban could be evaluated on the name of the user and on the IP origin.
	 *
	 * @param 	VAPApiUser  $user  The object of the user.
	 *
	 * @return 	boolean     True is the user is banned, otherwise false.
	 */
	abstract protected function isBanned(VAPApiUser $user);

	/**
	 * Evaluates if the provided user needs to be banned.
	 * This action is executed only after a failed authentication.
	 *
	 * @param 	VAPApiUser  $user  The object of the user.
	 *
	 * @return 	boolean     Return true if the user should be banned, otherwise false.
	 */
	abstract protected function needBan(VAPApiUser $user);

	/**
	 * Register a new ban for the provided user.
	 *
	 * @param 	VAPApiUser  $user  The object of the user.
	 */
	abstract protected function ban(VAPApiUser $user);

	/**
	 * Reset or remove the ban of the provided user.
	 *
	 * @param 	VAPApiUser  $user  The object of the user.
	 */
	abstract protected function resetBan(VAPApiUser $user);

	/**
	 * Register the provided event and response.
	 * This log should be visible only from the administrator.
	 *
	 * @param 	VAPApiEvent     $event     The event requested.
	 * @param 	VAPApiResponse  $response  The response caught or raised.
	 *
	 * @return 	boolean         True if the event has been registered, otherwise false.
	 */
	abstract protected function registerEvent(VAPApiEvent $event, VAPApiResponse $response);

	/**
	 * Update the user manifest after a successful authentication.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @see 	getUser() to access the user object.
	 */
	abstract protected function updateUserManifest();

	/**
	 * Prepares the document to output the given data.
	 *
	 * @param 	mixed  $data  The data to output.
	 * @param 	mixed  $type  The content type.
	 *
	 * @return 	void
	 */
	abstract public function output($data, $type = 'application/json');

	/**
	 * Loads the configuration for the specified event and user.
	 * @userby  APIs::trigger()
	 *
	 * @param 	string      $eventName  The name of the event.
	 * @param 	VAPApiUser  $user       The object of the user.
	 *
	 * @return 	mixed       Either an array or an object.
	 */
	abstract protected function loadEventConfig($eventName, VAPApiUser $user = null);

	/**
	 * Saves the configuration for the specified event and user.
	 *
	 * @param 	VAPApiEvent  $event  The event requested.
	 * @param 	VAPApiUser   $user   The object of the user.
	 *
	 * @return 	boolean      True on success, false otherwise.
	 */
	abstract protected function saveEventConfig(VAPApiEvent $event, VAPApiUser $user = null);
}
