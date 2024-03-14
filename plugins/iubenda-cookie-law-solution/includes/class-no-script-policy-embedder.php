<?php
/**
 * Iubenda no script policy embedder.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class No_Script_Policy_Embedder
 */
class No_Script_Policy_Embedder {
	const DOCUMENT_NOT_EXISTS = 'document_not_exists';
	const DOCUMENT_EXISTS     = 'document_exists';

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'wp_body_open', array( $this, 'add_cookie_policy_tag_to_body' ), 0 );
		add_action( 'iubenda_verify_cookie_policy_existence', array( $this, 'check_cookie_policies_url_status' ) );
	}

	/**
	 * Add the cookie policy tag to the body section.
	 */
	public function add_cookie_policy_tag_to_body() {
		$product_helper = new Product_Helper();

		// Check if the Cookie Solution is in simplified mode and the Privacy Policy service is enabled.
		$cs_is_enabled    = $product_helper->is_cs_service_enabled();
		$cs_is_simplified = $product_helper->is_cs_service_simplified();
		$pp_is_enabled    = $product_helper->is_pp_service_enabled();
		if ( ! $cs_is_enabled || ! $cs_is_simplified || ! $pp_is_enabled ) {
			// Return early if the cookie policy tag should not be added.
			return;
		}

		try {
			$lang_id = $product_helper->get_lang_id_for_current_language();

			/**
			 * Transient status will be equal:
			 * 1. (string) DOCUMENT_EXISTS if the url exists and accessible.
			 * 2. (string) DOCUMENT_NOT_EXISTS if the url not exists and non accessible.
			 * 3. (bool) False if the transient not exists.
			 */
			$status = get_transient( "iubenda_{$lang_id}_cookie_policy_document_status" );

			if ( self::DOCUMENT_EXISTS === $status ) {
				// The transient with this name exists, and it's DOCUMENT_EXISTS. URL does exist and is accessible.
				$public_id = $product_helper->get_public_id_for_current_language();

				if ( $public_id ) {
					$url = "https://www.iubenda.com/privacy-policy/{$public_id}/cookie-policy";
					echo '<noscript><p><a target="_blank" href="' . $url . '">Cookie policy</a></p></noscript>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			} elseif ( self::DOCUMENT_NOT_EXISTS === $status ) {
				// Schedule the cron job to run once after 1 month.
				if ( ! wp_next_scheduled( 'iubenda_verify_cookie_policy_existence' ) ) {
					$next_run_the_event = time() + ( MONTH_IN_SECONDS * 1 );
					wp_schedule_single_event( $next_run_the_event, 'iubenda_verify_cookie_policy_existence' );
				}
			} elseif ( is_bool( $status ) && ! $status ) {
				// The transient with this name does not exist, add it to the cron job.
				if ( ! wp_next_scheduled( 'iubenda_verify_cookie_policy_existence' ) ) {
					wp_schedule_single_event( time(), 'iubenda_verify_cookie_policy_existence' );
				}
			}
		} catch ( Exception $e ) {
			iub_caught_exception( $e );
		} catch ( Error $e ) {
			iub_caught_exception( $e );
		}
	}

	/**
	 * Check cookie policies URL status.
	 */
	public function check_cookie_policies_url_status() {
		try {
			// Get the public IDs from the global options.
			$public_ids = (array) iub_array_get( iubenda()->options, 'global_options.public_ids' );

			// Loop through each public ID.
			foreach ( $public_ids as $lang_id => $public_id ) {
				$cookie_policy_url = "https://www.iubenda.com/privacy-policy/{$public_id}/cookie-policy";

				// Send an HTTP GET request to the cookie policy URL.
				$response = wp_remote_get( $cookie_policy_url );

				// Check if there was an error accessing the URL.
				if ( is_wp_error( $response ) ) {
					set_transient( "iubenda_{$lang_id}_cookie_policy_document_status", self::DOCUMENT_NOT_EXISTS, MONTH_IN_SECONDS );

					// Error occurred while accessing the URL, continue to the next code.
					continue;
				}

				$response_code = wp_remote_retrieve_response_code( $response );

				// Check if the response code is 200 (URL is accessible and exists).
				if ( 200 === $response_code ) {
					set_transient( "iubenda_{$lang_id}_cookie_policy_document_status", self::DOCUMENT_EXISTS, 6 * MONTH_IN_SECONDS );
				} else {
					set_transient( "iubenda_{$lang_id}_cookie_policy_document_status", self::DOCUMENT_NOT_EXISTS, MONTH_IN_SECONDS );
				}
			}
		} catch ( Exception $e ) {
			iub_caught_exception( $e );
		} catch ( Error $e ) {
			iub_caught_exception( $e );
		}
	}
}
