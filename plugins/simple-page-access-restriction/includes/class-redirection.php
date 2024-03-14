<?php
/**
 * Class - Redirection.
 *
 * @package Simple_Page_Access_Restriction.
 */

namespace Simple_Page_Access_Restriction\Classes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redirection class.
 */
class Redirection {

	/**
	 * The URL.
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Add the hook.
		add_action( 'wp_login', array( $this, 'on_login' ) );
	}

	/**
	 * Filter the URL.
	 *
	 * @param string $location The location.
	 */
	public function filter_url( $location ) {
		// Get the URL.
		$url = $this->get_url();

		// Check the URL.
		if ( ! empty( $url ) ) {
			// Set the location.
			$location = $url;
		}

		// Return the location.
		return $location;
	}

	/**
	 * Get the URL.
	 *
	 * @return string The URL.
	 */
	public function get_url() {
		// Return the URL.
		return $this->url;
	}

	/**
	 * Run on login.
	 */
	public function on_login() {
		// Get the settings.
		$settings = ps_simple_par_get_settings();

		// Get the parameter.
		$parameter = isset( $settings['redirect_parameter'] ) ? $settings['redirect_parameter'] : '';

		// Check the parameter.
		if ( empty( $parameter ) ) {
			return;
		}

		// Set the redirect URL.
		$redirect_url = isset( $_REQUEST[ $parameter ] ) ? sanitize_text_field( wp_unslash( $_REQUEST[ $parameter ] ) ) : '';
		$redirect_url = filter_var( $redirect_url, FILTER_VALIDATE_URL );

		// Check the parameter.
		if ( empty( $redirect_url ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			// Get the referrer URL.
			$referrer_url = isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
			$referrer_url = filter_var( $referrer_url, FILTER_VALIDATE_URL );

			// Check the referrer URL.
			if ( empty( $referrer_url ) ) {
				return;
			}

			// Get the referrer parts.
			$referrer_parts = parse_url( $referrer_url );

			// Check the query.
			if ( empty( $referrer_parts['query'] ) ) {
				return;
			}

			// Set the query parameters.
			$query_parameters = array();

			// Parse the string.
			parse_str( $referrer_parts['query'], $query_parameters );

			// Set the redirect URL.
			$redirect_url = isset( $query_parameters[ $parameter ] ) ? $query_parameters[ $parameter ] : '';
			$redirect_url = filter_var( $redirect_url, FILTER_VALIDATE_URL );
		}

		// Check the redirect URL.
		if ( empty( $redirect_url ) ) {
			return;
		}

		// Get the post id.
		$post_id = url_to_postid( $redirect_url );

		// Check the post id
		if ( empty( $post_id ) ) {
			return;
		}

		// Check if the post is not restricted.
		if ( ! ps_simple_par_is_page_restricted( $post_id ) ) {
			return;
		}

		// Set the URL.
		$this->set_url( $redirect_url );

		// Add the hook (override 3rd party software redirections).
		add_action( 'wp_redirect', array( $this, 'filter_url') );
	}

	/**
	 * Set the URL.
	 *
	 * @param string $url The URL.
	 */
	public function set_url( $url ) {
		// Set the URL.
		$this->url = $url;
	}

}

// New instance.
new Redirection();
