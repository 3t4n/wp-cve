<?php
/**
 * Iubenda Auto Blocking Handler.
 *
 * Handles auto-blocking functionality for scripts.
 *
 * @package Iubenda
 */

/**
 * Handles the automatic blocking of scripts.
 */
class Auto_Blocking {
	/**
	 * Stores autoblocking options.
	 *
	 * @var array An array for storing autoblocking options.
	 */
	public $auto_block_sites_status = array();

	/**
	 * Instance of Iubenda_CS_Product_Service.
	 *
	 * @var Iubenda_CS_Product_Service An instance of the Iubenda_CS_Product_Service class.
	 */
	private $cs_product_service;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->cs_product_service      = new Iubenda_CS_Product_Service();
		$this->auto_block_sites_status = iub_array_get( iubenda()->options, 'cs.frontend_auto_blocking', array() );

		add_action( 'wp_ajax_check_frontend_auto_blocking_status', array( $this, 'check_frontend_auto_blocking_by_code' ) );
	}

	/**
	 * Parses the configuration from the provided script and returns the site ID.
	 *
	 * @param   string $script  The script to parse.
	 *
	 * @return string The site ID.
	 */
	public function get_site_id_from_cs_code( $script ) {
		return $this->retrieve_info_from_script_by_key( $script, 'siteId' );
	}

	/**
	 * Parses the configuration from the provided script and returns the Cookie Policy ID.
	 *
	 * @param   string $script  The script to parse.
	 *
	 * @return string The Cookie Policy ID.
	 */
	public function get_cookie_policy_id_from_cs_code( $script ) {
		return $this->retrieve_info_from_script_by_key( $script, 'cookiePolicyId' );
	}

	/**
	 * Checks if the autoblocking feature is available for the given site ID and updates the status.
	 *
	 * @param   string $site_id  The site ID to check.
	 */
	public function fetch_auto_blocking_status_by_site_id( $site_id ) {
		$this->auto_block_sites_status[ $site_id ] = $this->is_autoblocking_feature_available( $site_id );
	}

	/**
	 * Checks whether the autoblocking feature is available for the given site ID.
	 *
	 * @param   string $site_id  The site ID to check.
	 *
	 * @return bool True if the autoblocking feature is available; otherwise, false.
	 */
	public function is_autoblocking_feature_available( $site_id ) {
		// Build the URL.
		$url = 'https://cs.iubenda.com/autoblocking/' . $site_id . '.js';

		// Set the timeout.
		$timeout = 5;

		// Configure the request parameters.
		$args = array(
			'timeout' => $timeout,
		);

		// Make a remote request.
		$remote_file = wp_remote_get( $url, $args );

		// Retrieve the response body from the remote request.
		$content = wp_remote_retrieve_body( $remote_file );

		// Check length of content must be more than 150 character.
		if ( 150 >= strlen( $content ) ) {
			// Content is too short, return false.
			return false;
		}

		// Check if the content contains the indicator for an unavailable feature.
		return false === strpos( $content, 'Autoblocking not enabled' );
	}

	/**
	 * Checks if the string "iubenda.com/autoblocking/" is present in the provided script.
	 *
	 * @param   string $script  The script to check.
	 *
	 * @return bool True if the string is present; otherwise, false.
	 */
	public function is_autoblocking_script_present( $script ) {
		$script = stripslashes( $script );

		// Check if the string "iubenda.com/autoblocking/" is present in the script.
		// The function returns true if the string is found and false otherwise.
		return false !== strpos( $script, 'iubenda.com/autoblocking/' );
	}

	/**
	 * Process a script and update Autoblocking options based on the provided CS code.
	 *
	 * @param string $script The script to process.
	 */
	public function process_autoblocking_code( $script ) {
		// Get site_id from embed code.
		$site_id_from_cs_code = $this->get_site_id_from_cs_code( $script );

		// Check if the status has already been updated for this site_id.
		if ( isset( $this->auto_block_sites_status[ $site_id_from_cs_code ] ) ) {
			return;
		}

		// Initialize the Autoblocking option for the site_id.
		$this->auto_block_sites_status[ $site_id_from_cs_code ] = false;

		// Check if site_id is empty, and if so, return early.
		if ( empty( $site_id_from_cs_code ) ) {
			return;
		}

		// Check if Autoblocking script is present in the option.
		if ( $this->is_autoblocking_script_present( $script ) ) {
			// Handle the status based on the site_id.
			$this->fetch_auto_blocking_status_by_site_id( $site_id_from_cs_code );
		} else {
			// If Autoblocking script is not present, set the option to false.
			$this->auto_block_sites_status[ $site_id_from_cs_code ] = false;
		}
	}

	/**
	 * Extracts the site ID from a script URL containing "autoblocking" and ending with ".js".
	 *
	 * @param string $script_url The URL of the script.
	 *
	 * @return string|null The autoblocking number if found, or null if no match is found.
	 */
	private function get_site_id_from_script_url( $script_url ) {
		$pattern = '/autoblocking\/(\d+)\.js/';

		if ( preg_match( $pattern, $script_url, $matches ) ) {
			return $matches[1];
		}

		return null;
	}

	/**
	 * Checks if an autoblocking script should be attached based on its source URL.
	 *
	 * @param string $src The source URL of the script.
	 *
	 * @return bool Whether the autoblocking script should be attached or not.
	 */
	public function should_autoblocking_script_attached( $src ) {
		$site_id = $this->get_site_id_from_script_url( $src );

		if ( ! empty( $site_id ) ) {
			// Check if the autoblocking script should be attached based on the site ID.
			return $this->auto_block_sites_status[ $site_id ] ?? false;
		}

		return false;
	}

	/**
	 * Check the frontend auto-blocking status based on the provided code.
	 *
	 * This function is intended to be used as an AJAX callback for checking the auto-blocking status.
	 *
	 * @return void
	 */
	public function check_frontend_auto_blocking_by_code() {
		iub_verify_ajax_request( 'check_frontend_auto_blocking_status', 'iub_nonce' );
		$site_id            = '';
		$configuration_type = iub_get_request_parameter( 'configuration_type' );
		$code               = iub_get_request_parameter( 'code', null, false );
		if ( ! $code ) {
			wp_send_json( false );
		}

		// Check if the configuration_type is simplified.
		if ( 'simplified' === $configuration_type ) {
			$site_id = iub_array_get( iubenda()->options['global_options'], 'site_id' );
		} elseif ( 'manual' === $configuration_type ) {
			// Get site_id from embed code.
			$site_id = $this->get_site_id_from_cs_code( $code );
		}

		if ( ! $site_id ) {
			wp_send_json( false );
		}

		wp_send_json( $this->is_autoblocking_feature_available( $site_id ) );
	}

	/**
	 * Retrieve information from the provided script by a given key.
	 *
	 * This function extracts specific information from a script based on the provided key.
	 * It first attempts to parse the configuration using iubenda()->parse_configuration().
	 * If parsing fails, it tries parsing with $this->cs_product_service->parse_configuration_by_regex().
	 *
	 * @param string $script The script from which to extract information.
	 * @param string $key    The key for the information to retrieve.
	 *
	 * @return string The extracted information, or an empty string if not found.
	 */
	private function retrieve_info_from_script_by_key( string $script, string $key ) {
		// Remove slashes from the script.
		$script = stripslashes( $script );

		// Try to parse the configuration using iubenda()->parse_configuration().
		$parsed_configuration = iubenda()->parse_configuration( $script );
		if ( ! empty( $parsed_configuration ) && isset( $parsed_configuration[ $key ] ) ) {
			return $parsed_configuration[ $key ];
		}

		// If parsing fails, try parsing with $this->cs_product_service->parse_configuration_by_regex().
		$parsed_configuration_by_regex = $this->cs_product_service->parse_configuration_by_regex( $script );
		if ( ! empty( $parsed_configuration_by_regex ) && isset( $parsed_configuration_by_regex[ $key ] ) ) {
			return $parsed_configuration_by_regex[ $key ];
		}

		// Return an empty string if the key is not found.
		return '';
	}
}
