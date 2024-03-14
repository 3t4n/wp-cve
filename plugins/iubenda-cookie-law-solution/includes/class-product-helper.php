<?php
/**
 * Iubenda product helper.
 *
 * Includes all products functions.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Helper
 */
class Product_Helper {

	/**
	 * Get cs embed code by lang id.
	 *
	 * @param string $lang_id language slug.
	 *
	 * @return string
	 */
	public function get_cs_embed_code_by_lang( $lang_id ) {
		$code = iub_array_get( iubenda()->options['cs'], "manual_code_{$lang_id}" );
		if ( ! $code ) {
			$code = iub_array_get( iubenda()->options['cs'], "code_{$lang_id}" );
		}

		return html_entity_decode( iubenda()->parse_code( $code ) );
	}

	/**
	 * Get pp embed code by lang.
	 *
	 * @param string $lang_id language slug.
	 *
	 * @return string
	 */
	public function get_pp_embed_code_by_lang( $lang_id ) {
		return $this->get_embed_code( 'pp', $lang_id );
	}

	/**
	 * Get tc embed code by lang.
	 *
	 * @param string $lang_id language slug.
	 *
	 * @return string
	 */
	public function get_tc_embed_code_by_lang( $lang_id ) {
		return $this->get_embed_code( 'tc', $lang_id );
	}

	/**
	 * Get all languages if website is multilingual and return default if website is single language.
	 *
	 * @return array|string[]
	 */
	public function get_languages() {
		if ( iubenda()->multilang && ! empty( iubenda()->languages ) ) {
			return iubenda()->languages;
		}

		return array( 'default' => 'default_language' );
	}


	/**
	 * Get embed code by service key for specifc language slug
	 *
	 * @param string $service_key Iubenda service key.
	 * @param string $lang_id language slug.
	 *
	 * @return string
	 */
	private function get_embed_code( $service_key, $lang_id ) {
		$code = iub_array_get( iubenda()->options[ $service_key ], "code_{$lang_id}" );

		return html_entity_decode( iubenda()->parse_code( $code ) );
	}

	/**
	 * Check Iubenda CS code exists on current lang or not
	 *
	 * @return bool
	 */
	public function check_iub_cs_code_exists_current_lang() {
		return $this->check_iub_code_exists_current_lang( 'cs' );
	}

	/**
	 * Check Iubenda $key code exists on current lang or not
	 *
	 * @param string $service_key Iubenda service key.
	 *
	 * @return bool
	 */
	private function check_iub_code_exists_current_lang( $service_key ) {
		// Check if there is multi-language plugin installed and activated.
		if ( iubenda()->multilang === true && defined( 'ICL_LANGUAGE_CODE' ) && isset( iubenda()->options['cs'][ 'code_' . ICL_LANGUAGE_CODE ] ) ) {
			$iubenda_code = iubenda()->options[ $service_key ][ 'code_' . ICL_LANGUAGE_CODE ];

			// no code for current language, use default.
			if ( ! $iubenda_code ) {
				$iubenda_code = iubenda()->options[ $service_key ]['code_default'];
			}
		} else {
			$iubenda_code = iubenda()->options[ $service_key ]['code_default'];
		}

		// Return true if code is not empty.
		return boolval( ! empty( $iubenda_code ) );
	}

	/**
	 * Getting public id for current language if stored in database.
	 *
	 * @return array|ArrayAccess|false|mixed
	 */
	public function get_public_id_for_current_language() {
		// Checking if there is no public id for current language.
		$lang_id = $this->get_lang_id_for_current_language();

		$public_id = (string) iub_array_get( iubenda()->options['global_options'], "public_ids.{$lang_id}" );
		if ( empty( $public_id ) ) {
			return false;
		}

		return $public_id;
	}

	/**
	 * Check privacy policy and terms and condition services status and position and codes
	 *
	 * @return bool
	 */
	public function check_pp_tc_status_and_position() {
		$pp_status               = (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true';
		$pp_position             = (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) === 'automatic';
		$tc_status               = (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true';
		$tc_position             = (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) === 'automatic';
		$quick_generator_service = new Quick_Generator_Service();

		if ( ! ( $pp_status && $pp_position && boolval( $quick_generator_service->pp_button() ) ) && ! ( $tc_status && $tc_position && boolval( $quick_generator_service->tc_button() ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if iubenda service is enabled
	 *
	 * @param string $service_key Iubenda service key.
	 *
	 * @return bool
	 */
	private function is_iub_service_enabled( $service_key ) {
		return boolval( (string) iub_array_get( iubenda()->settings->services, "{$service_key}.status" ) === 'true' );
	}

	/**
	 * Check if CS service is enabled
	 */
	public function is_cs_service_enabled() {
		return $this->is_iub_service_enabled( 'cs' );
	}

	/**
	 * Check if the CS service is in simplified mode.
	 *
	 * @return bool Returns true if the CS service is in simplified mode, false otherwise.
	 */
	public function is_cs_service_simplified() {
		// Get the configuration type from the 'cs' option in the iubenda options.
		$configuration_type = iub_array_get( iubenda()->options['cs'], 'configuration_type' );

		// Check if the configuration type is equal to 'simplified' and return the result.
		return 'simplified' === (string) $configuration_type;
	}

	/**
	 * Check if PP service is enabled
	 */
	public function is_pp_service_enabled() {
		return $this->is_iub_service_enabled( 'pp' );
	}

	/**
	 * Check if TC service is enabled
	 */
	public function is_tc_service_enabled() {
		return $this->is_iub_service_enabled( 'tc' );
	}

	/**
	 * Check if CONS service is enabled
	 */
	public function is_cons_service_enabled() {
		return $this->is_iub_service_enabled( 'cons' );
	}

	/**
	 * Check if iubenda service is configured
	 *
	 * @param string $service_key Iubenda service key.
	 *
	 * @return bool
	 */
	private function is_iub_service_configured( $service_key ) {
		return boolval( (string) iub_array_get( iubenda()->settings->services, "{$service_key}.configured" ) === 'true' );
	}

	/**
	 * Check if CS service is configured
	 */
	public function is_cs_service_configured() {
		return $this->is_iub_service_configured( 'cs' );
	}

	/**
	 * Check if PP service is configured
	 */
	public function is_pp_service_configured() {
		return $this->is_iub_service_configured( 'pp' );
	}

	/**
	 * Check if TC service is configured
	 */
	public function is_tc_service_configured() {
		return $this->is_iub_service_configured( 'tc' );
	}

	/**
	 * Check if CONS service is configured
	 */
	public function is_cons_service_configured() {
		return $this->is_iub_service_configured( 'cons' );
	}

	/**
	 * Get the language id for current language.
	 *
	 * @return string
	 */
	public function get_lang_id_for_current_language() {
		if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
			$lang_id = iubenda()->lang_current;
		} else {
			$lang_id = 'default';
		}

		return $lang_id;
	}
}
