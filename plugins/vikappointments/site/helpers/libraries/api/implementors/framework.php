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
 * VikAppointments API framework implementor.
 * This class is used to run all the installed plugins in a given directory.
 *
 * All the events are runnable only if the user is correctly authenticated.
 *
 * @see VAPApi
 * @see VAPApiUser
 * @see VAPApiResponse
 * @see VAPApiError
 * @see VAPApiEvent
 *
 * @since  	1.7
 */
class VAPApiFramework extends VAPApi
{
	/**
	 * Class constructor.
	 * @protected This class can be accessed only through the static getInstance() method.
	 *
	 * In case the framework is not accessible, it will be disabled.
	 *
	 * @param 	string 	$path  The dir path containing all the plugins.
	 *
	 * @see 	getInstance()
	 */
	protected function __construct($path = null)
	{
		parent::__construct($path);

		// make sure the API framework is enabled
		$enabled = VAPFactory::getConfig()->getBool('apifw');

		if (!$enabled)
		{
			// disable API
			$this->disable();
		}
	}

	/**
	 * Authenticate the provided user and connect it on success.
	 * The credentials of the user are stored in the database.
	 *
	 * This method can raise the following internal errors:
	 * - 103 = The username and password do not match
	 * - 104 = This account is blocked
	 * - 105 = The source IP is not authorised
	 *
	 * @param 	VAPApiUser  $user  The object of the user.
	 *
	 * @return 	integer     The ID of the user on success, otherwise false.
	 *
	 * @uses 	setError()  Set the error raised.
	 */
	protected function doConnection(VAPApiUser $user)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		// get login that matches with the credentials provided
		$q->select('*')
			->from($dbo->qn('#__vikappointments_api_login'))
			->where($dbo->qn('username') . ' = ' . $dbo->q($user->getUsername()))
			->where('BINARY ' . $dbo->qn('password') . ' = ' . $dbo->q($user->getPassword()));

		$dbo->setQuery($q, 0, 1);
		
		// load login
		$login = $dbo->loadAssoc();

		if (!$login)
		{
			// set error : credentials not correct
			$this->setError(103, 'Authentication Error! The username and password do not match.');
			return false;
		}

		// check if login account is still active
		if (!$login['active'])
		{
			// set error : login blocked
			$this->setError(104, 'Authentication Error! This account is blocked.');
			return false;
		}

		// check if user IP address is in the list of the allowed IPs
		// if there are no IPs specified, all addresses are allowed
		if (strlen($login['ips']))
		{
			$ip_list = json_decode($login['ips'], true);

			if (count($ip_list) && !in_array($user->getSourceIp(), $ip_list))
			{
				// set error : ip address not allowed
				$this->setError(105, 'Authentication Error! The source IP is not authorised.');
				return false;
			}
		}

		return $login['id'];
	}

	/**
	 * Register the provided event and response.
	 * This log is registered in the database and it is visible only from the administrator.
	 *
	 * @param 	VAPApiEvent     $event     The event requested.
	 * @param 	VAPApiResponse  $response  The response caught or raised.
	 *
	 * @return 	boolean         True if the event has been registered, otherwise false.
	 *
	 * @uses 	isConnected() Check if the user is connected.
	 * @uses 	getUser()     Get the current user.
	 */
	protected function registerEvent(VAPApiEvent $event = null, VAPApiResponse $response = null)
	{
		$log     = '';
		$status  = 2;
		$id_user = $this->isConnected() ? $this->getUser()->id() : -1;
		$ip      = $this->isConnected() ? $this->getUser()->getSourceIp() : null;

		// if the event is not empty : register it
		if ($event !== null)
		{
			$log .= 'Event: ' . $event->getName() . "\n";
		}

		// if the response is not empty : register it and evaluate the status
		if ($response !== null)
		{
			$log .= $response->getContent();

			$status = $response->isVerified() ? 1 : 0;
		}

		if (empty($log))
		{
			// if the evaluated log is still empty
			if ($id_user > 0)
			{
				// try to register the details of the user
				$log = 'User [' . $this->getUser()->getUsername() . '] login @ ' . JHtml::fetch('date', 'now', 'Y-m-d H:i:s', JFactory::getApplication()->get('offset', 'UTC'));
			}
			else
			{
				// otherwise register a "unrecognised" response
				$log = 'Unable to recognize the response';
			}

		}

		// prepare log data
		$data = array(
			'id'       => 0,
			'id_login' => $id_user,
			'status'   => $status,
			'content'  => $log,
			'payload'  => $response->getPayload(),
		);

		// save log through model
		return (bool) JModelVAP::getInstance('apilog')->save($data);
	}

	/**
	 * Update the user manifest after a successful authentication.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	getUser() Access the user object.
	 */
	protected function updateUserManifest()
	{
		if ($this->getUser() === null)
		{
			return false;
		}

		// prepare login data
		$data = array(
			'id'         => $this->getUser()->id(),
			'last_login' => 1,
		);

		// save manifest through model
		return JModelVAP::getInstance('apiuser')->save($data);
	}

	/**
	 * Check if the provided user has been banned.
	 * This action is executed only before the authentication.
	 * The ban is evaluated on the IP origin.
	 *
	 * A user is considered banned when its failures are equals or higher
	 * than the maximum number of failure attempts allowed.
	 *
	 * The failure attempts are always increased by the ban() function.
	 *
	 * @param 	VAPApiUser 	$user  The object of the user.
	 *
	 * @return 	boolean     True is the user is banned, otherwise false.
	 *
	 * @uses 	get() Get the maximum number of failure attempts from config.
	 * @see 	ban() Used to ban a user.
	 */
	protected function isBanned(VAPApiUser $user)
	{
		// get the number of failures associated to the IP address of the user
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn('fail_count'))
			->from($dbo->qn('#__vikappointments_api_ban'))
			->where($dbo->qn('ip') . ' = ' . $dbo->q($user->getSourceIp()));

		$dbo->setQuery($q, 0, 1);

		// if the failures count is equals or higher than the maximum allowed, it means the user
		// needs to be banned
		return (int) $dbo->loadResult() >= $this->get('max_failure_attempts', 10);
	}

	/**
	 * Considering this function is called after every failure, a ban is always needed.
	 * Every time this function is executed, the system will call the ban() function to apply the ban.
	 *
	 * @param 	VAPApiUser 	$user  The object of the user.
	 *
	 * @return 	boolean     Return true.
	 *
	 * @see 	ban() Used to ban a user.
	 */
	protected function needBan(VAPApiUser $user)
	{
		// all failures need to be banned
		// ban() function is used to increase the number of failures
		return true;
	}

	/**
	 * Increase the failure attempts of the provided user.
	 * Once this function is terminated, the user is not effectively banned, unless its 
	 * total failures are equals or higher than the maximum number allowed.
	 *
	 * @param 	VAPApiUser  $user  The object of the user.
	 *
	 * @return 	void
	 *
	 * @see 	isBanned()  Check if the user is banned.
	 */
	protected function ban(VAPApiUser $user)
	{
		$dbo = JFactory::getDbo();

		// get the ID of the user to ban

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn(array('id', 'fail_count')))
			->from($dbo->qn('#__vikappointments_api_ban'))
			->where($dbo->qn('ip') . ' = ' . $dbo->q($user->getSourceIp()));

		$dbo->setQuery($q, 0, 1);
		$data = $dbo->loadAssoc();

		if (!$data)
		{
			// create new ban
			$data = array(
				'id'         => 0,
				'fail_count' => 0,
			);
		}

		// increase failure count
		$data['fail_count']++;

		// save ban through model
		JModelVAP::getInstance('apiban')->save($data);
	}

	/**
	 * Reset the count of failure attempts for the provided user.
	 *
	 * @param 	VAPApiUser 	$user  The object of the user.
	 *
	 * @return 	boolean     True if the user is correctly logged, otherwise false.
	 */
	protected function resetBan(VAPApiUser $user)
	{
		if (!$user->id())
		{
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_api_ban'))
			->where($dbo->qn('ip') . ' = ' . $dbo->q($user->getSourceIp()));

		$dbo->setQuery($q, 0, 1);
		$id = (int) $dbo->loadResult();
		
		if ($id)
		{
			$data = array(
				'id'         => $id,
				'fail_count' => 0,
			);

			// reset ban through model
			JModelVAP::getInstance('apiban')->save($data);
		}

		return true;
	}

	/**
	 * Prepares the document to output the given data.
	 *
	 * @param 	mixed  $data  The data to output.
	 * @param 	mixed  $type  The content type.
	 *
	 * @return 	void
	 */
	public function output($data, $type = 'application/json')
	{
		if (!is_null($data))
		{
			$app = JFactory::getApplication();

			// check whether the output requires a specific content type
			// and make sure the headers haven't been already sent
			if ($type && $this->sendHeaders)
			{
				// set content type and send the headers
				$app->setHeader('Content-Type', $type);
				$app->sendHeaders();

				// lock headers sending
				$this->sendHeaders = false;
			}
		
			// try to stringify an object in case of JSON content type
			if (!is_string($data) && preg_match("/json/i", $type))
			{
				$data = json_encode($data);
			}

			echo $data;
		}
	}

	/**
	 * Loads the configuration for the specified event and user.
	 *
	 * @param 	string      $eventName  The name of the event.
	 * @param 	VAPApiUser  $user       The object of the user.
	 *
	 * @return 	mixed       Either an array or an object.
	 */
	protected function loadEventConfig($eventName, VAPApiUser $user = null)
	{
		$options = array();

		if (!$user)
		{
			// make sure we have a logged-in user
			if (!$this->isConnected())
			{
				// nope, return an empty array...
				return $options;
			}

			// use currently connected user
			$user = $this->getUser();
		}

		// get helper model
		$model = JModelVAP::getInstance('apiuseroptions');

		// load options related to the specified ID and event
		$data = $model->getOptions($user->id(), $eventName);

		if ($data)
		{
			// existing record, use the stored configuration
			$options = $data->options;
		}

		return $options;
	}

	/**
	 * Saves the configuration for the specified event and user.
	 *
	 * @param 	VAPApiEvent  $event  The event requested.
	 * @param 	VAPApiUser   $user   The object of the user.
	 *
	 * @return 	boolean      True on success, false otherwise.
	 */
	protected function saveEventConfig(VAPApiEvent $event, VAPApiUser $user = null)
	{
		$options = $event->getOptions();

		if (!$options)
		{
			// empty configuration, do not need to go ahead
			return true;
		}

		if (!$user)
		{
			// make sure we have a logged-in user
			if (!$this->isConnected())
			{
				// nope, saving failed
				return false;
			}

			// use currently connected user
			$user = $this->getUser();
		}

		// get helper model
		$model = JModelVAP::getInstance('apiuseroptions');

		// set up data to bind
		$data = array();
		$data['id_login'] = $user->id();
		$data['id_event'] = $event->getName();
		$data['options']  = $event->getOptions();

		// store options
		return $model->save($data);
	}
}
