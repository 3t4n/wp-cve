<?php

namespace WPPayForm\Framework\Foundation;

trait AsyncRequestTrait
{
	/**
	 * Async actions names
	 * @var array
	 */
	protected $asyncActions = [];

	/**
	 * Launchable actions
	 * @var array
	 */
	protected $launchableActions = [];

	/**
	 * Add an async action
	 * 
	 * @param string $action
	 * @param mixed $handler Callback
	 * @return null
	 */
	public function addAsyncAction($action, $handler)
	{
		$this->asyncActions[$action] = $handler;
	}

	/**
	 * Queue an async action so it'll be dispatched
	 * automatically during the WordPress shutdown hook.
	 * 
	 * @param string $action
	 * @param mixed $handler Callback
	 * @param array $data The data to be passed to the handler
	 * @return null
	 */
	public function queueAsyncAction($action, $handler, $data = [])
	{
		$this->addAsyncAction($action, $handler);
		$this->doAsyncAction($action, $data);
	}

	/**
	 * Dequeue an async action so it won't be dispatched
	 * automatically during the WordPress shutdown hook.
	 * 
	 * @param string $action
	 * @return null
	 */
	public function dequeueAsyncAction($action)
	{
		unset($this->launchableActions[$action]);
	}

	/**
	 * Dispatch async action
	 * @param  string $action
	 * @param  array $data
	 * @return null
	 */
	public function doAsyncAction($action, $data = [])
	{
		$this->launchableActions[$action] = $data;
	}

	/**
	 * Register an async action
	 * @return null
	 */
	protected function registerAsyncActions()
	{
		$this->registerAdminPostAction(
			$this->config->get('app.slug')
		);
	}

	/**
	 * Register an async action in WordPress admin_post_[action]
	 * @param  string $slug
	 * @return null
	 */
	protected function registerAdminPostAction($slug)
	{
		add_action("admin_post_wpfluent_async_$slug", [$this, 'callback']);
		add_action("admin_post_nopriv_wpfluent_async_$slug", [$this, 'callback']);

		if (!has_action('shutdown', [$this, 'launchPostRequest'])) {
			if (!$this->request->get($this->getAsyncActionIdentifier())) {
				add_action('shutdown', [$this, 'launchPostRequest']);
			}

		}
	}

	/**
	 * The main callback for async actions
	 * @return null
	 * @throws \Exception
	 */
	public function callback()
	{
		add_filter('wp_die_handler', function() { die(); });

		try {
			$this->verifyNonce();

			foreach ($_POST['actions'] as $action => $data) {
				try {
					if (isset($this->asyncActions[$action])) {
						($this->parseHookHandler(
							$this->asyncActions[$action]
						))($data);
					}
				} catch (\Exception $e) {
					continue;
				}
			}
		} catch (\Exception $e) {
			return;
		}
	}

	/**
	 * Launch the background (async) request.
	 * @return null
	 */
	public function launchPostRequest()
	{
		if (!$this->hasLaunchableActions()) {
			return;
		}
		
		$data = $this->getData();

		foreach ($this->launchableActions as $key => $action) {
			$this->dispatchRequest(array_merge(
				$data, ['actions' => [$key => $action]]
			));
		}
	}

	/**
	 * Get the post data for the action
	 * @return array
	 */
	protected function getData()
	{
		$slug = $this->getAsyncActionIdentifier();

		return [
			$slug => true,
			'_nonce' => $this->createNonce(),
			'action' => $this->getMainAction()
		];
	}

	/**
	 * Dispatch the request.
	 * 
	 * @param  array $data
	 * @return null
	 */
	protected function dispatchRequest($data)
	{
		$args = [
			'timeout'   => 0.01,
			'blocking'  => false,
			'sslverify' => apply_filters('https_local_ssl_verify', true),
			'body'      => $data,
			'headers'   => [
				'cookie' => $this->getCookie(),
			],
		];

		wp_remote_post(admin_url('admin-post.php'), $args);
	}

	/**
	 * Get the unique slug for identifying
	 * the async request for this plugin.
	 * 
	 * @return string Unique identifier
	 */
	protected function getAsyncActionIdentifier()
	{
		$slug = $this->config->get('app.slug');
		
		return '_wpfluent_is_async_request_for_plugin_' . $slug;
	}

	/**
	 * Get the cookie to send with the request
	 * @return string Cookie string
	 */
	protected function getCookie()
	{
		$cookies = [];

		foreach ($_COOKIE as $name => $value) {
			$cookies[] = "$name=" . urlencode(is_array($value) ? serialize($value) : $value);
		}

		return implode('; ', $cookies);
	}

	/**
	 * Check for any launchable actions.
	 * @return boolean
	 */
	protected function hasLaunchableActions()
	{
		return count($this->launchableActions) > 0;
	}

	/**
	 * Create the nonce for the request.
	 * @return string
	 */
	protected function createNonce()
	{
		$i = wp_nonce_tick();

		$action = $this->getMainAction();

		return wp_hash($i . $action, 'nonce');
	}

	/**
	 * Verify the nonce
	 * @return mixed
	 */
	protected function verifyNonce()
	{
		if (!isset($_POST['_nonce'])) {
			throw new \Exception('Invalid Request.');
		}

		$nonce = $_POST['_nonce'];

		$i = wp_nonce_tick();

		$action = $this->getMainAction();

		// Nonce generated 0-12 hours ago
		if (wp_hash($i . $action, 'nonce') == $nonce) {
			return 1;
		}

		// Nonce generated 12-24 hours ago
		if (wp_hash(($i - 1) . $action , 'nonce') == $nonce) {
			return 2;
		}

		// Invalid nonce
		return false;
	}

	/**
	 * Resolve the main action name for the pliugin.
	 * @return string
	 */
	protected function getMainAction() {
		$action = $this->config->get('app.slug');

		$action = "wpfluent_async_$action";

		return $action;
	}
}
