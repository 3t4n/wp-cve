<?php

namespace Photonic_Plugin\Platforms;

trait Authenticator {
	abstract public function get_access_token($token);

	public function oauth_signature_method(): string {
		return 'HMAC-SHA1';
	}

	/**
	 * Encodes the URL as per RFC3986 specs. This replaces some strings in addition to the ones done by a rawurlencode.
	 * This has been adapted from the OAuth for PHP project.
	 *
	 * @static
	 * @param $input
	 * @return array|mixed|string
	 */
	public static function urlencode_rfc3986($input) {
		if (is_array($input)) {
			return array_map(['Photonic_Plugin\Platforms\Authenticator', 'urlencode_rfc3986'], $input);
		}
		elseif (is_scalar($input)) {
			return str_replace(
				'+',
				' ',
				str_replace('%7E', '~', rawurlencode($input))
			);
		}
		else {
			return '';
		}
	}

	/**
	 * Takes an array of parameters, then parses it and generates a query string. Prior to generating the query string the parameters are sorted in their natural order.
	 * Without sorting the signatures between this application and the provider might differ.
	 *
	 * @static
	 * @param $params
	 * @return string
	 */
	public static function build_query($params): string {
		if (!$params) {
			return '';
		}
		$keys   = array_map(['Photonic_Plugin\Platforms\Authenticator', 'urlencode_rfc3986'], array_keys($params));
		$values = array_map(['Photonic_Plugin\Platforms\Authenticator', 'urlencode_rfc3986'], array_values($params));
		$params = array_combine($keys, $values);

		// Sort by keys (natsort)
		uksort($params, 'strnatcmp');
		$pairs = [];
		foreach ($params as $key => $value) {
			if (is_array($value)) {
				natsort($value);
				foreach ($value as $v2) {
					$pairs[] = ('' === $v2) ? "$key=0" : "$key=$v2";
				}
			}
			else {
				$pairs[] = ('' === $value) ? "$key=0" : "$key=$value";
			}
		}

		$string = implode('&', $pairs);
		return $string;
	}

	/**
	 * Takes a token response from a request token call, then puts it in an appropriate array.
	 *
	 * @param $response
	 * @return array
	 */
	abstract public function parse_token($response): array;

	/**
	 * Saves the token in the authentication options. Used only by Flickr and SmugMug; invoked via the Authentication screen.
	 * Transients don't need to be saved as Flickr and SmugMug don't need them.
	 *
	 * @param $token
	 */
	public function save_token($token) {
		if (current_user_can('edit_theme_options') && check_ajax_referer($this->provider . '-request-token-' . $this->api_secret)) {
			$photonic_authentication = get_option('photonic_authentication');
			if (!isset($photonic_authentication)) {
				$photonic_authentication = [];
			}
			$photonic_authentication[$this->provider] = $token;
			update_option('photonic_authentication', $photonic_authentication);
		}
	}

	/**
	 * Fetches a token from authentication options
	 *
	 * @return array|null
	 */
	public function get_cached_token() {
		$transient_token         = get_transient('photonic_' . $this->provider . '_token');
		$photonic_authentication = get_option('photonic_authentication');
		if (false !== $transient_token) {
			return $transient_token;
		}

		if (!empty($photonic_authentication) && isset($photonic_authentication[$this->provider])) {
			$transient_expiry = $photonic_authentication[$this->provider]['oauth_token_created'] + $photonic_authentication[$this->provider]['oauth_token_expires'] - time();
			set_transient('photonic_' . $this->provider . '_token', $photonic_authentication[$this->provider], $transient_expiry);
			return $photonic_authentication[$this->provider];
		}
		return null;
	}
}
