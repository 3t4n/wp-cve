<?php

namespace Photonic_Plugin\Platforms;

use Photonic_Plugin\Components\Stack_Trace;
use Photonic_Plugin\Core\Photonic;
use WP_Error;

require_once PHOTONIC_PATH . '/Components/Printable.php';
require_once PHOTONIC_PATH . '/Components/Stack_Trace.php';
require_once PHOTONIC_PATH . '/Components/Header.php';
require_once PHOTONIC_PATH . '/Components/Error.php';

/**
 * Gallery processor class to be extended by individual processors. This class has an abstract method called <code>get_gallery_images</code>
 * that has to be defined by each inheriting processor.
 *
 * This is also where the OAuth support is implemented. The URLs are defined using abstract functions, while a handful of utility functions are defined.
 * Most utility functions have been adapted from the OAuth PHP package distributed here: https://code.google.com/p/oauth-php/.
 */
abstract class Base {
	public $api_key, $api_secret, $provider, $user_agent, $nonce, $oauth_timestamp, $signature_parameters, $link_lightbox_title,
		$oauth_version, $oauth_done, $show_more_link, $is_server_down, $is_more_required, $gallery_index, $common_parameters,
		$doc_links, $password_protected, $token, $token_secret, $show_buy_link, $stack_trace;

	protected function __construct() {
		global $photonic_enable_popup, $photonic_thumbnail_style;

		$this->nonce                             = self::nonce();
		$this->user_agent                        = 'PhotonicWP/' . PHOTONIC_VERSION . '; ' . get_home_url();
		$this->oauth_timestamp                   = time();
		$this->oauth_version                     = '1.0';
		$this->show_more_link                    = false;
		$this->is_server_down                    = false;
		$this->is_more_required                  = true;
		$this->gallery_index                     = 0;

		$bypass_popup                      = empty($photonic_enable_popup) || 'off' === $photonic_enable_popup || 'hide' === $photonic_enable_popup;
		$this->common_parameters                 = [
			'columns'        => 'auto',
			'layout'         => !empty($photonic_thumbnail_style) ? $photonic_thumbnail_style : 'square',
			'display'        => 'local', // local: on the same page, lightbox: in a lightbox, modal: in a modal popup, template: using the page template
			'popup'          => $bypass_popup ? 'hide' : $photonic_enable_popup,    // While popups are not possible for slideshows, this shortcode value is needed in Platform sub-classes, to determine whether a `gallery_url` should be associated with an album thumbnail.
			'filter'         => '',
			'filter_type'    => 'include',
			'more'           => '',
			'panel'          => '',
			'custom_classes' => '',
			'alignment'      => '',
		];
		$this->common_parameters['photo_layout'] = $this->common_parameters['layout'];

		$this->doc_links          = [];
		$this->password_protected = esc_html__('This album is password-protected. Please provide a valid password.', 'photonic');
		$this->show_buy_link      = false;
		$this->stack_trace        = [];

		$this->add_hooks();
	}

	final public static function get_instance() {
		static $instances = array();
		$called_class = get_called_class();

		if (!isset($instances[$called_class])) {
			$instances[$called_class] = new $called_class();
		}
		return $instances[$called_class];
	}

	/**
	 * Main function that fetches the images associated with the shortcode. This is implemented by all sub-classes.
	 *
	 * @abstract
	 * @param array $attr
	 * @return array
	 */
	abstract public function get_gallery_images($attr = []): array;

	/**
	 * Generates a nonce for use in signing calls.
	 *
	 * @static
	 * @return string
	 */
	public static function nonce(): string {
		$mt   = microtime();
		$rand = mt_rand();
		return md5($mt . $rand);
	}

	/**
	 * Takes a string of parameters in an HTML encoded string, then returns an array of name-value pairs, with the parameter
	 * name and the associated value.
	 *
	 * @static
	 * @param $input
	 * @return array
	 */
	public static function parse_parameters($input): array {
		if (!isset($input) || !$input) {
			return [];
		}

		$pairs = explode('&', $input);

		$parsed_parameters = [];
		foreach ($pairs as $pair) {
			$split     = explode('=', $pair, 2);
			$parameter = sanitize_text_field(urldecode($split[0]));
			$value     = isset($split[1]) ? sanitize_text_field(urldecode($split[1])) : '';

			if (isset($parsed_parameters[$parameter])) {
				// We have already recieved parameter(s) with this name, so add to the list
				// of parameters with this name
				if (is_scalar($parsed_parameters[$parameter])) {
					// This is the first duplicate, so transform scalar (string) into an array
					// so we can add the duplicates
					$parsed_parameters[$parameter] = [$parsed_parameters[$parameter]];
				}

				$parsed_parameters[$parameter][] = $value;
			}
			else {
				$parsed_parameters[$parameter] = $value;
			}
		}
		return $parsed_parameters;
	}

	public function get_header_display($args): array {
		if (!isset($args['headers'])) {
			return [
				'thumbnail' => 'inherit',
				'title'     => 'inherit',
				'counter'   => 'inherit',
			];
		}
		elseif (empty($args['headers'])) {
			return [
				'thumbnail' => 'none',
				'title'     => 'none',
				'counter'   => 'none',
			];
		}
		else {
			$header_array = explode(',', $args['headers']);
			return [
				'thumbnail' => in_array('thumbnail', $header_array, true) ? 'show' : 'none',
				'title'     => in_array('title', $header_array, true) ? 'show' : 'none',
				'counter'   => in_array('counter', $header_array, true) ? 'show' : 'none',
			];
		}
	}

	public function get_hidden_headers($arg_headers, $setting_headers): array {
		return [
			'thumbnail' => 'inherit' === $arg_headers['thumbnail'] ? $setting_headers['thumbnail'] : 'none' === $arg_headers['thumbnail'],
			'title'     => 'inherit' === $arg_headers['title'] ? $setting_headers['title'] : 'none' === $arg_headers['title'],
			'counter'   => 'inherit' === $arg_headers['counter'] ? $setting_headers['counter'] : 'none' === $arg_headers['counter'],
		];
	}

	/**
	 * Wraps an error message in appropriately-styled markup for display in the front-end
	 *
	 * @param $message
	 * @return string
	 */
	public function error($message): string {
		return "<div class='photonic-error photonic-{$this->provider}-error' id='photonic-{$this->provider}-error-{$this->gallery_index}'>\n\t<span class='photonic-error-icon photonic-icon'>&nbsp;</span>\n\t<div class='photonic-message'>\n\t\t$message\n\t</div>\n</div>\n";
	}

	/**
	 * Retrieves the error messages from a WP_Response object and formats them in a display-ready markup.
	 *
	 * @param WP_Error $response
	 * @param bool $server_msg
	 * @return string
	 */
	public function wp_error_message($response, $server_msg = true): string {
		$ret = '';
		if ($server_msg) {
			$ret = $this->get_server_error() . "<br/>\n";
		}
		if (is_wp_error($response)) {
			$messages = $response->get_error_messages();
			$ret      .= '<strong>' . esc_html(sprintf(_n('%s Message:', '%s Messages:', count($messages), 'photonic'), count($messages))) . "</strong><br/>\n";
			foreach ($messages as $message) {
				$ret .= $message . "<br>\n";
			}
		}
		return $ret;
	}

	public function push_to_stack($event) {
		global $photonic_performance_logging;
		if (empty($photonic_performance_logging)) {
			return;
		}

		if (!isset($this->stack_trace[$this->gallery_index])) {
			$stack_trace = new Stack_Trace();
		}
		else {
			$stack_trace = $this->stack_trace[$this->gallery_index];
		}

		$stack_trace->add_to_first_open_event($event);
		$this->stack_trace[$this->gallery_index] = $stack_trace;
	}

	public function pop_from_stack() {
		global $photonic_performance_logging;
		if (empty($photonic_performance_logging)) {
			return;
		}

		if (isset($this->stack_trace[$this->gallery_index])) {
			/** @var Stack_Trace $stack_trace */
			$stack_trace = $this->stack_trace[$this->gallery_index];
			$stack_trace->pop_from_first_open_event();
			$this->stack_trace[$this->gallery_index] = $stack_trace;
		}
	}

	protected function get_gallery_url($short_code, $meta): string {
		global $photonic_alternative_shortcode, $photonic_gallery_template_page;

		$shortcode_tag   = $photonic_alternative_shortcode ?: 'gallery';
		$shortcode_parts = [];
		foreach ($short_code as $attr => $value) {
			if (is_array($value)) {
				continue;
			}
			$shortcode_parts[] = $attr . '="' . esc_attr($value) . '"';
		}
		$raw_shortcode = '[' . $shortcode_tag . ' ' . implode(' ', $shortcode_parts) . ']';

		$gallery_url = add_query_arg(
			[
				'photonic_gallery'       => base64_encode($raw_shortcode), // Encode the shortcode to avoid issues in passing the data around in URLs. This is decoded in Template.php
				'photonic_gallery_title' => rawurlencode($meta['title']),
			],
			get_page_link($photonic_gallery_template_page)
		);
		return $gallery_url;
	}

	public function ssl_verify_peer(&$handle) {
		// Photonic mostly uses wp_remote_get, but for bulk requests, it needs to use the 'WpOrg\Requests\Hooks' class, which needs 'curl.before_multi_add'.
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); // phpcs:ignore WordPress.WP.AlternativeFunctions
	}

	public function get_server_error(): string {
		return sprintf(esc_html__('There was an error connecting to %s. Please try again later.', 'photonic'), $this->provider);
	}

	/**
	 * Helper execution, implemented by child classes
	 *
	 * @param array $args
	 * @return string
	 */
	public function execute_helper($args = []): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		// Blank method, to be overridden by child classes
		return '';
	}

	public function add_hooks() {
		// Blank method, implemented by child classes, if required
	}

	public function increment_gallery_index() {
		$this->gallery_index++;
	}
}
