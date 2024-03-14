<?php
/**
 * Iubenda service rating.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Service_Rating
 */
class Service_Rating {
	/**
	 * Radar API configuration
	 *
	 * @var array|false|mixed|void
	 */
	public $radar_api_configuration = array();

	/**
	 * Service_Rating constructor.
	 */
	public function __construct() {
		$this->radar_api_configuration = (array) get_option( 'iubenda_radar_api_configuration', array() );
	}

	/**
	 * Is cookie solution activated ?
	 *
	 * @return bool
	 */
	public function is_cookie_solution_activated() {
		if ( (string) iub_array_get( iubenda()->settings->services, 'cs.status' ) === 'true' ) {
			return true;
		}

		if ( $this->is_service_detected_or_installed_by_radar( 'cp' ) === true ) {
			return true;
		}

		return false;
	}

	/**
	 * Is cookie solution activated ?
	 *
	 * @return bool
	 */
	public function is_cookie_solution_automatically_parse_enabled() {
		if ( (bool) iub_array_get( iubenda()->options, 'cs.parse' ) === true ) {
			return true;
		}

		if ( $this->is_service_detected_or_installed_by_radar( 'cp' ) === true ) {
			return true;
		}

		return false;
	}

	/**
	 * Is privacy policy activated ?
	 *
	 * @return bool
	 */
	public function is_privacy_policy_activated() {
		if ( (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true' ) {
			return true;
		}

		if ( $this->is_service_detected_or_installed_by_radar( 'pp' ) === true ) {
			return true;
		}

		return false;
	}

	/**
	 * Is terms and conditions activated ?
	 *
	 * @return bool
	 */
	public function is_terms_conditions_activated() {
		if ( (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true' ) {
			return true;
		}

		if ( $this->is_service_detected_or_installed_by_radar( 'tc' ) === true ) {
			return true;
		}

		return false;
	}

	/**
	 * Is $service installed by radar ?
	 *
	 * @param string $service_key Iubenda service key.
	 *
	 * @return array|ArrayAccess|false|mixed|null
	 */
	private function is_service_installed_by_radar( $service_key ) {
		if ( (string) iub_array_get( $this->radar_api_configuration, 'status' ) === 'completed' ) {
			return iub_array_get( $this->radar_api_configuration, 'result.meta.' . $service_key . '_installed' );
		}

		return false;
	}

	/**
	 * Is cookie solution activated ?
	 *
	 * @param string $service_key Iubenda service key.
	 *
	 * @return bool
	 */
	private function is_service_detected_by_radar( $service_key ) {
		if ( (string) iub_array_get( $this->radar_api_configuration, 'status' ) === 'completed' ) {
			return boolval( iub_array_get( $this->radar_api_configuration, 'result.meta.detected_' . $service_key ) );
		}

		return false;
	}

	/**
	 * Check service status by $service_key.
	 *
	 * @param string $service_key Iubenda service key.
	 *
	 * @return bool
	 */
	public function check_service_status( $service_key ) {
		if ( 'cs' === $service_key ) {
			return $this->is_cookie_solution_activated();
		}
		if ( 'cons' === $service_key ) {
			return $this->is_cookie_solution_automatically_parse_enabled();
		}
		if ( 'pp' === $service_key ) {
			return $this->is_privacy_policy_activated();
		}
		if ( 'tc' === $service_key ) {
			return $this->is_terms_conditions_activated();
		}

		return false;
	}

	/**
	 * Services percentage
	 *
	 * @return float|int
	 */
	public function services_percentage() {
		$services['pp']   = $this->is_privacy_policy_activated();
		$services['cs']   = $this->is_cookie_solution_activated();
		$services['cons'] = ( $this->is_cookie_solution_activated() && $this->is_cookie_solution_automatically_parse_enabled() );
		$services['tc']   = $this->is_terms_conditions_activated();

		return ( count( array_filter( $services ) ) / count( $services ) ) * 100;
	}

	/**
	 * Rating calculation components.
	 *
	 * @return array[]
	 */
	public function rating_calculation_components() {
		return array(
			'cs'   => array(
				'status'    => $this->is_cookie_solution_activated(),
				'label'     => __( 'Set up a Consent Banner', 'iubenda' ),
				'paragraph' => __( 'This accounts for 25&#37; of your score. Your Consent Banner should inform your users about your use of cookies and similar tracking technologies, and their rights in this regard. You may need a banner if either the GDPR, USPR or ePrivacy apply to you.', 'iubenda' ),
			),
			'cons' => array(
				'status'    => boolval( $this->is_cookie_solution_activated() && $this->is_cookie_solution_automatically_parse_enabled() ),
				'label'     => __( 'Only track users that give consent', 'iubenda' ),
				'paragraph' => __( 'This accounts for 25&#37; of your score. If you’re based in Europe or have Europe-based users, you likely need to block cookies from running until you receive user consent. To do this, select “Native Blocking”.', 'iubenda' ),
			),
			'pp'   => array(
				'status'    => $this->is_privacy_policy_activated(),
				'label'     => __( 'Set up a privacy policy', 'iubenda' ),
				'paragraph' => __( 'This accounts for 25&#37; of your score. A privacy policy is a requirement under most privacy laws around the world. This document typically includes legally required disclosures about the type of personal data you process, why you need to process it, how the processing is done and the user’s rights under applicable law.', 'iubenda' ),
			),
			'tc'   => array(
				'status'    => $this->is_terms_conditions_activated(),
				'label'     => __( 'Set up terms and conditions', 'iubenda' ),
				'paragraph' => __( 'This accounts for 25&#37; of your score. Terms and conditions help to protect you, the website owner, from potential liabilities and more. Furthermore, if you run an e-commerce site or app, having this document may be legally required as Terms typically contain legally mandatory disclosures. Terms are legally binding documents, and therefore it’s important to ensure that they actually fit your specific scenario.', 'iubenda' ),
			),
		);
	}

	/**
	 * Is service detected or installed by radar by $service_key
	 *
	 * @param   string $service_key  Iubenda service key.
	 *
	 * @return bool
	 */
	private function is_service_detected_or_installed_by_radar( string $service_key ) {
		if ( $this->is_service_installed_by_radar( $service_key ) === true || $this->is_service_detected_by_radar( $service_key ) === true ) {
			return true;
		}

		return false;
	}
}
