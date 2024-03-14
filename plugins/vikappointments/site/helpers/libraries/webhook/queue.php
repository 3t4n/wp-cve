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

VAPLoader::import('libraries.webhook.webhook');

/**
 * Web hooks queue manager class.
 *
 * @since 1.7
 */
class VAPWebHookQueue
{
	/**
	 * Singleton reference.
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * A list of supported web hooks.
	 *
	 * @var array
	 */
	protected $hooks = array();

	/**
	 * A queue of payloads.
	 *
	 * @var array
	 */
	protected $queue = array();

	/**
	 * Returns the queue instance by creating it only once.
	 *
	 * @return 	self
	 */
	public static function getInstance()
	{
		if (static::$instance === null)
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Constructs the object by pre-loading all the supported web hooks
	 * created by the administrator.
	 */
	public function __construct()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_webhook'))
			->where($dbo->qn('published') . ' = 1');

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $hook)
		{
			// decode hook params
			$hook->params = $hook->params ? json_decode($hook->params) : array();
			// register web hook record
			$this->hooks[] = $hook;
		}
	}

	/**
	 * Auto-delivery the pending requests while this instance gets
	 * destructed by the garbage collector.
	 */
	public function __destruct()
	{
		$this->deliver();
	}

	/**
	 * Registers a new hook within the queue.
	 *
	 * @param 	string 	 $hook     The hook name.
	 * @param 	mixed    $payload  The payload to delivery.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function register($hook, $payload)
	{
		// make sure the hook is supported
		if (!$this->getHooks($hook))
		{
			// hook not observed
			return false;
		}

		// create web hook instance
		$webhook = VAPWebHook::getInstance($hook, $payload);

		// iterate all previously registered web hooks to make
		// sure they should be merged together instead of being
		// send with separated requests
		$inside = $this->search($webhook);

		if ($inside)
		{
			// web hook already registered, merge payloads
			$inside->extend($webhook);
		}
		else
		{
			// web hook not found, register within the queue
			$this->queue[] = $webhook;
		}

		return true;
	}

	/**
	 * Proxy for `deliver`.
	 * 
	 * @deprecated 1.8  Use deliver() instead.
	 */
	public function delivery()
	{
		return $this->deliver();
	}

	/**
	 * Deliveries the web hooks currently registered within the queue.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 * 
	 * @since   1.7.4  Renamed from delivery.
	 */
	public function deliver()
	{
		if (!$this->queue)
		{
			// nothing to notify
			return false;
		}

		// Close connection to speed up the page load.
		// Nothing processed from now on needs to be presented to the user.
		$this->closeConnection();

		// get event dispatcher
		$dispatcher = VAPFactory::getEventDispatcher();

		$status = false;

		$http = new JHttp();

		// get web hook model
		$model = JModelVAP::getInstance('webhook');

		// iterate all the elements registered within the queue
		foreach ($this->queue as $job)
		{
			// get all matching web hooks
			$hooks = $this->getHooks($job->getHook());

			foreach ($hooks as $hook)
			{
				// prepare headers
				$headers = array(
					'Content-Type'         => 'application/json',
					'X-VAP-WEBHOOK-ID'     => $hook->id,
					'X-VAP-WEBHOOK-ACTION' => $hook->hook,
					'X-VAP-WEBHOOK-SECURE' => md5($hook->secret),
				);

				try
				{
					// fetch request payload
					$payload = $job->getPayload($hook->params);
				}
				catch (Exception $e)
				{
					// extract exception details and send them as payload, so that we can
					// track the error through the log files
					$payload = array(
						'code'    => $e->getCode(),
						'message' => $e->getMessage(),
						'trace'   => $e->getTrace(),
					);
				}

				/**
				 * Plugins can use this event to manipulate the payload to post and the
				 * request headers. By setting the payload to "false", the dispatcher
				 * will automatically skip the web hook.
				 *
				 * @param 	mixed       &$payload  The payload to send.
				 * @param 	array       &$headers  The request headers.
				 * @param 	object      $hook      The web hook details.
				 * @param 	VAPWebHook  $job       The web hook job.
				 *
				 * @return 	void
				 *
				 * @since 	1.7
				 */
				$dispatcher->trigger('onBeforeDispatchWebhook', array(&$payload, &$headers, $hook, $job));

				// ignore payload in case the webhook explicitly returned false
				if ($payload !== false)
				{
					// JSON encode payload (if not already encoded)
					$payload = is_string($payload) ? $payload : json_encode($payload);

					// dispatch request to the specified end-point
					$response = $http->post($hook->url, $payload, $headers);

					/**
					 * Plugins can use this event to manipulate the response received after
					 * deploying the web hook.
					 *
					 * @param 	object  $response  The HTTP response object.
					 * @param 	object  $hook      The web hook details.
					 *
					 * @return 	void
					 *
					 * @since 	1.7
					 */
					$dispatcher->trigger('onAfterDispatchWebhook', array($response, $hook));

					// prepare save data
					$saveData = array(
						'id'       => $hook->id,
						'lastping' => true,
						'logkey'   => $hook->logkey,
						'log'      => array(
							'headers'  => $headers,
							'payload'  => json_decode($payload),
							'response' => $response->body,
						),
					);

					// validate HTTP response code
					if ($response->code >= 200 && $response->code < 300)
					{
						$status = true;

						// reset failure counter on success
						$saveData['failed'] = 0;
					}
					else
					{
						// increase failure counter
						$saveData['failed'] = $hook->failed + 1;
					}

					// update web hook record
					$model->save($saveData);
				}
			}
		}

		return $status;
	}

	/**
	 * Checks whether the specified web hook should be triggered
	 * and returns a list of matching records.
	 *
	 * @param 	string 	$action  The action to look for.
	 * 
	 * @return 	array   An array of matching web hooks.
	 */
	public function getHooks($action)
	{
		// filter the array of supported web hooks and return only the
		// ones with the same action
		return array_filter($this->hooks, function($hook) use ($action)
		{
			return $hook->hook == $action;
		});
	}

	/**
	 * Searches inside the queue whether we have a matching web hook.
	 *
	 * @param 	VAPWebHook  $hook
	 * 
	 * @return 	mixed       The matching web hook on success, null otherwise.
	 */
	protected function search(VAPWebHook $hook)
	{
		foreach ($this->queue as $job)
		{
			if ($job->equalsTo($hook))
			{
				// matching element, return it
				return $job;
			}
		}

		// no matching element
		return null;
	}

	/**
	 * Tries to immediately terminate the HTTP connection before dispatching the queue.
	 * This way we can present the response to the user without having to wait for the
	 * queue completion.
	 *
	 * @return 	void
	 */
	protected function closeConnection()
	{
		// fastcgi_finish_request is the cleanest way to send the response and keep the script running,
		// but not every server has it
		if (!is_callable('fastcgi_finish_request'))
		{
			return;
		}

		/**
		 * Trigger hook to safely prevent the closure of the HTTP connection at runtime.
		 * This way we can prevent any errors that might occur with certain server
		 * configurations.
		 *
		 * @return 	boolean  False to avoid closing the connection.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->false('onBeforeWebhookCloseHttpConnection'))
		{
			// do not close the connection, wait for the queue completion
			return;
		}

		set_time_limit(0);

		// ignore user abort to prevent the behavior that terminates the process by calling flush
		// or any other similar function
		ignore_user_abort(true);
		fastcgi_finish_request();
	}
}
