<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}
/**
 * WebTotem reCaptcha class for Wordpress.
 */
class WebTotemCaptcha{

	const WTOTEM_RECAPTCHA_ENDPOINT = 'https://www.google.com/recaptcha/api/siteverify';

	/**
	 * Returns whether or not the authentication CAPTCHA is enabled.
	 *
	 * @return bool
	 */
	public static function isEnabled() {
		$site_key = self::_siteKey();
		$secret = self::_secret();
		$recaptcha = WebTotemOption::getPluginSettings('recaptcha');

		return $recaptcha && !empty($site_key) && !empty($secret);
	}

	/**
	 * Returns CAPTCHA site key.
	 *
	 * @return bool
	 */
	public static function _siteKey() {
		return WebTotemOption::getPluginSettings('recaptcha_v3_site_key');
	}

	/**
	 * Returns CAPTCHA secret code.
	 *
	 * @return bool
	 */
	public static function _secret() {
		return WebTotemOption::getPluginSettings('recaptcha_v3_secret');
	}

	/**
	 * Queries the reCAPTCHA endpoint with the given token, verifies the action matches, and returns the corresponding
	 * score. If validation fails, false is returned. Any other failure (e.g., mangled response or connection dropped) returns 0.0.
	 *
	 * @param string $token
     * @param string $secret
     * @param string $action
	 * @param int $timeout
	 * @return float|false
	 */
	public static function score($token, $secret = false, $action = 'login', $timeout = 20) {
		try {
			$payload = array(
				'secret' => $secret,
				'response' => $token,
				'remoteip' => WebTotem::getUserIP(),
			);

			$response = wp_remote_post(self::WTOTEM_RECAPTCHA_ENDPOINT,
				array(
					'body'    => $payload,
					'headers' => array(
						'Referer' => false,
					),
					'timeout' => $timeout,
					'blocking' => true,
				));

			if (!is_wp_error($response)) {

				$jsonResponse = wp_remote_retrieve_body($response);
				$decoded = @json_decode($jsonResponse, true);

				if (is_array($decoded) && isset($decoded['success']) && isset($decoded['score']) && isset($decoded['action'])) {
					if ($decoded['success'] && $decoded['action'] == $action) {
						return (float) $decoded['score'];
					}
					return 0.0;
				}
			}
		}
		catch (\Exception $e) {
			//Fall through
		}

		return 0.0;
	}

	/**
	 * Get the captcha token provided with the current request
	 * @param string $key if specified, override the default token parameter
	 * @return string|null the captcha token, if present, null otherwise
	 */
	public static function get_token($key = 'wtotem-recaptcha-token') {
		return (isset($_POST[$key]) && is_string($_POST[$key]) && !empty($_POST[$key]) ? $_POST[$key] : null);
	}

}
