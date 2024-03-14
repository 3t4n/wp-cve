<?php

namespace Photonic_Plugin\Platforms;

use Photonic_Plugin\Core\Photonic;

require_once 'Base.php';
require_once 'Authenticator.php';

abstract class OAuth2 extends Base {
	use Authenticator;

	public $scope;
	public $response_type;
	public $client_id;
	public $client_secret;
	public $state;
	public $access_token;
	public $auth_error;
	public $soon_limit;

	protected function __construct() {
		$this->soon_limit = 30;
		parent::__construct();
	}

	abstract public function authentication_URL();

	abstract public function access_token_URL();

	abstract public function renew_token($token);

	abstract protected function set_token_validity($validity);

	public function redirect_url() {
		return get_site_url();
	}

	public function get_authorization_url($args = []) {
		$url        = add_query_arg('test', 'test');
		$url        = remove_query_arg('test', $url);
		$parameters = array_merge(
			[
				'response_type' => $this->response_type,
				'redirect_uri'  => $this->redirect_url(),
				'client_id'     => $this->client_id,
				'scope'         => $this->scope,
				'access_type'   => 'offline',
				'state'         => md5($this->client_secret . $this->provider) . '::' . rawurlencode($url),
			],
			$args
		);
		return $this->authentication_URL() . "?" . Authenticator::build_query($parameters);
	}

	/**
	 * Takes an OAuth request token and exchanges it for an access token.
	 *
	 * @param $request_token
	 */
	public function get_access_token($request_token) {
		$code       = $request_token['code'];
		$state_args = explode('::', $request_token['state']);

		if (md5($this->client_secret . $this->provider) === $state_args[0]) {
			$url      = urldecode($state_args[1]);
			$response = Photonic::http(
				$this->access_token_URL(),
				'POST',
				[
					'code'          => $code,
					'grant_type'    => 'authorization_code',
					'client_id'     => $this->client_id,
					'client_secret' => $this->client_secret,
					'redirect_uri'  => $this->redirect_url(),
				]
			);

			if (is_wp_error($response)) {
				$url = add_query_arg('error', $response->get_error_code(), $url);
			}
			elseif (empty($response)) {
				$url = add_query_arg('error', 'null', $url);
			}
		}
		else {
			$url = remove_query_arg(['token', 'code', 'state']);
		}
		wp_safe_redirect($url);
		exit();
	}

	/**
	 * @param $base_token
	 */
	public function authenticate($base_token) {
		$photonic_authentication = get_option('photonic_authentication');
		if (!isset($photonic_authentication)) {
			$photonic_authentication = [];
		}

		$transient_token = get_transient('photonic_' . $this->provider . '_token');
		if (empty($transient_token) && !isset($photonic_authentication[$this->provider]) && !empty($base_token)) {
			// Nothing is in the authentication option, but there is a token in overall Photonic Options.
			// Refresh it if required, and save it as a transient via renew_token.
			list($token, $error) = $this->renew_token($base_token);
		}
		elseif ($transient_token || isset($photonic_authentication[$this->provider])) {
			$token = empty($transient_token) ? $photonic_authentication[$this->provider] : $transient_token;
			if (!empty($token)) {
				if (empty($transient_token) && !($this->is_token_expired($token) || $this->is_token_expiring_soon($this->soon_limit) > 0)) {
					set_transient('photonic_' . $this->provider . '_token', $token, $token['oauth_token_expires']);
					$this->set_token_validity(true);
				}
				elseif ($this->is_token_expired($token) || $this->is_token_expiring_soon($this->soon_limit) > 0) {
					list($token, $error) = $this->renew_token($base_token);
				}
				else {
					$this->set_token_validity(true);
				}
			}
			else {
				list($token, $error) = $this->renew_token($base_token);
			}
		}

		if (!empty($token)) {
			$this->access_token = $token['oauth_token'];
			$this->auth_error   = '';
		}
		else {
			$this->set_token_validity(false);
			if (!empty($error)) {
				$this->auth_error = $error;
			}
			else {
				$this->auth_error = '';
			}
		}
	}

	public function is_token_expired($token) {
		if (empty($token)) {
			return true;
		}
		if (!isset($token['oauth_token']) || !isset($token['oauth_token_created']) || !isset($token['oauth_token_expires'])) {
			return true;
		}
		if (!isset($token['client_id']) || (isset($token['client_id']) && $token['client_id'] !== $this->client_id)) {
			return true;
		}
		$current = time();
		if ($token['oauth_token_created'] + $token['oauth_token_expires'] < $current) {
			return true;
		}
		return false;
	}

	/**
	 * Checks if a token will expire soon. This is used to trigger a refresh for sources such as Instagram. Google uses a separate "Refresh Token",
	 * so this is not applicable to it. The <code>soon_limit</code> defines how many days is "soon", and a refresh is triggered if the current date
	 * is in the "soon" range. E.g. If you have a soon limit of 30 days, and your token expires in 15 days when you load the page, this method will
	 * return <code>true</code>.
	 *
	 * For cases where the token does not exist yet, the method returns <code>null</code>.
	 *
	 * @param $soon_limit int Number of days to check the expiry limit for.
	 * @return int|null If there is no token, return null. Otherwise, if there are < $soon_limit days left, return 1, if token is expired return -1, and if there is time return 0.
	 */
	abstract public function is_token_expiring_soon($soon_limit);

	/**
	 * Takes a token response from a request token call, then puts it in an appropriate array.
	 *
	 * @param $response
	 * @return array
	 */
	public function parse_token($response): array {
		$token = [];
		if (!is_wp_error($response) && is_array($response)) {
			$body = $response['body'];
			$body = json_decode($body);
			if (empty($body->error)) {
				$token['oauth_token']         = $body->access_token;
				$token['oauth_token_type']    = $body->token_type;
				$token['oauth_token_created'] = time();
				$token['oauth_token_expires'] = $body->expires_in;
				$this->set_token_validity(true);
			}
			else {
				$this->set_token_validity(false);
			}
		}
		return $token;
	}
}
