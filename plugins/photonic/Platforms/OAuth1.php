<?php

namespace Photonic_Plugin\Platforms;

use Photonic_Plugin\Core\Photonic;
use WP_Error;

require_once 'Base.php';
require_once 'Authenticator.php';

abstract class OAuth1 extends Base {
	use Authenticator;

	public $base_url;

	/**
	 * Access Token URL
	 *
	 * @abstract
	 * @return string
	 */
	abstract public function access_token_URL(): string;

	/**
	 * Authenticate URL
	 *
	 * @abstract
	 * @return string
	 */
	abstract public function authenticate_URL(): string;

	/**
	 * Authorize URL
	 *
	 * @return string
	 */
	abstract public function authorize_URL(): string;

	/**
	 * Request Token URL
	 *
	 * @return string
	 */
	abstract public function request_token_URL(): string;

	/**
	 * Method to validate that the stored token is indeed authenticated.
	 *
	 * @return array|WP_Error
	 */
	abstract public function check_access_token();

	/**
	 * Get the authorize URL
	 *
	 * @param $token
	 * @param bool $sign_in
	 * @return string
	 */
	public function get_authorize_URL($token, $sign_in = true): string {
		if (empty($sign_in)) {
			return $this->authorize_URL() . "?oauth_token={$token['oauth_token']}";
		}
		else {
			return $this->authenticate_URL() . "?oauth_token={$token['oauth_token']}";
		}
	}

	/**
	 * Takes a token response from a request token call, then puts it in an appropriate array.
	 *
	 * @param $response
	 * @return array
	 */
	public function parse_token($response): array {
		$body  = $response['body'];
		$token = Base::parse_parameters($body);
		return $token;
	}

	/**
	 * Generates the signature for the OAuth call.
	 * See here for the signature-generation methodology: https://www.wackylabs.net/2011/12/oauth-and-flickr-part-2/
	 *
	 * @param null $api_call
	 * @param null $api_args
	 * @param null $method
	 * @param array $oauth_token
	 * @return string
	 */
	protected function generate_signature($api_call = null, $api_args = null, $method = null, $oauth_token = []): string {
		$encoded_key = Authenticator::urlencode_rfc3986($this->api_secret) . '&';
		if (isset($oauth_token['oauth_token_secret'])) {
			$encoded_key .= Authenticator::urlencode_rfc3986($oauth_token['oauth_token_secret']);
		}

		$method = is_null($method) ? 'POST' : $method;
		$params = [
			'oauth_consumer_key'     => $this->api_key,
			'oauth_nonce'            => $this->nonce,
			'oauth_signature_method' => $this->oauth_signature_method(),
			'oauth_timestamp'        => $this->oauth_timestamp,
			'oauth_version'          => $this->oauth_version,
		];

		if (isset($oauth_token['oauth_token'])) {
			$params['oauth_token'] = $oauth_token['oauth_token'];
		}

		if (isset($oauth_token['oauth_verifier'])) {
			$params['oauth_verifier'] = $oauth_token['oauth_verifier'];
		}

		$params = (!empty($api_args)) ? array_merge($params, $api_args) : $params;
		if ('smug' === $this->provider && (false !== stripos($api_call, '?_expand=') || false !== stripos($api_call, '?_config='))) {
			if (false !== stripos($api_call, '?_expand=')) {
				$params['_expand'] = substr($api_call, stripos($api_call, '?_expand=') + 9);
			}
			if (false !== stripos($api_call, '?_config=')) {
				$params['_config'] = substr($api_call, stripos($api_call, '?_config=') + 9);
			}
		}

		$end_point = 'smug' === $this->provider ? remove_query_arg(['_expand', '_config'], $api_call) : $api_call;

		ksort($params);
		$string      = Authenticator::build_query($params);
		$base_string = $method . '&' . Authenticator::urlencode_rfc3986($end_point) . '&' . Authenticator::urlencode_rfc3986($string);
		$sig         = base64_encode(hash_hmac('sha1', $base_string, $encoded_key, true));

		$this->signature_parameters = [
			'parameters'  => $params,
			'base_string' => $base_string,
			'key'         => $encoded_key,
		];
		return $sig;
	}

	/**
	 * Gets an OAuth request token using the API Key and API Secret provided in the plugin's back-end options.
	 * Once a token has been successfully got, the user is sent to an Authorization page where he can allow access for your site.
	 *
	 * @param null $oauth_callback
	 * @return array
	 */
	public function get_request_token($oauth_callback): array {
		$method = 'GET';

		$signature  = $this->generate_signature($this->request_token_URL(), ['oauth_callback' => $oauth_callback], $method);
		$parameters = [
			'oauth_version'          => $this->oauth_version,
			'oauth_nonce'            => $this->nonce,
			'oauth_timestamp'        => $this->oauth_timestamp,
			'oauth_callback'         => $oauth_callback,
			'oauth_consumer_key'     => $this->api_key,
			'oauth_signature_method' => $this->oauth_signature_method(),
			'oauth_signature'        => $signature,
		];

		$end_point = $this->request_token_URL();
		if ('GET' === $method) {
			$end_point  .= '?' . Authenticator::build_query($parameters);
			$parameters = null;
		}

		$response = Photonic::http($end_point, $method, $parameters);
		$token    = $this->parse_token($response);

		return $token;
	}

	/**
	 * Takes an OAuth request token and exchanges it for an access token.
	 *
	 * @param $request_token
	 * @return array
	 */
	public function get_access_token($request_token): array {
		$method     = 'GET';
		$signature  = $this->generate_signature($this->access_token_URL(), [], $method, $request_token);
		$parameters = [
			'oauth_consumer_key'     => $this->api_key,
			'oauth_nonce'            => $this->nonce,
			'oauth_signature'        => $signature,
			'oauth_signature_method' => $this->oauth_signature_method(),
			'oauth_timestamp'        => $this->oauth_timestamp,
			'oauth_token'            => $request_token['oauth_token'],
			'oauth_version'          => $this->oauth_version,
		];

		if (isset($request_token['oauth_verifier'])) {
			$parameters['oauth_verifier'] = $request_token['oauth_verifier'];
		}

		$end_point = $this->access_token_URL();

		if ('GET' === $method) {
			$end_point  .= '?' . Authenticator::build_query($parameters);
			$parameters = null;
		}

		$response = Photonic::http($end_point, $method, $parameters);
		$token    = $this->parse_token($response);

		return $token;
	}

	/**
	 * Takes the response for the "Check access token", then tries to determine whether the check was successful or not.
	 *
	 * @param $response
	 * @return bool
	 */
	public function is_access_token_valid($response): bool {
		if (is_wp_error($response)) {
			return false;
		}

		$body = $response['body'];
		$body = json_decode($body);

		if (!isset($body->stat) || 'fail' === $body->stat) {
			return false;
		}
		return true;
	}

	/**
	 * Checks if authentication has been enabled and the user has authenticated. If so, it signs the call, then adds the additional parameters to it.
	 * This method also attaches the oauth_signature to the parameters.
	 *
	 * @param $api_method
	 * @param $method
	 * @param $parameters
	 * @return mixed
	 */
	public function sign_call($api_method, $method, $parameters) {
		if (isset($this->token) && isset($this->token_secret)) {
			$token = ['oauth_token' => $this->token, 'oauth_token_secret' => $this->token_secret];
		}

		if (isset($token)) {
			$this->nonce           = $this->nonce();
			$this->oauth_timestamp = time();
			$signature             = $this->generate_signature($api_method, $parameters, $method, $token);
			if (isset($this->signature_parameters) && isset($this->signature_parameters['parameters'])) {
				$this->signature_parameters['parameters']['oauth_signature'] = $signature;
				return $this->signature_parameters['parameters'];
			}
		}
		return $parameters;
	}

	public function set_oauth_done() {
		if (!empty($this->token) && !empty($this->token_secret)) {
			$token_response   = $this->check_access_token();
			$this->oauth_done = $this->is_access_token_valid($token_response);
		}
	}
}
