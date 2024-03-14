<?php
/**
 * Iubenda quick generator service.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Quick_Generator_Service
 */
class Quick_Generator_Service {

	/**
	 * Quick generator service response
	 *
	 * @var array
	 */
	public $qg_response = array();

	/**
	 * Quick_Generator_Service constructor.
	 */
	public function __construct() {
		$this->qg_response = array_filter( (array) get_option( Iubenda_Settings::IUB_QG_RESPONSE, array() ) );
	}

	/**
	 * Get mapped language on locale.
	 *
	 * @param   string $iub_lang_code  iub_lang_code.
	 *
	 * @return array
	 */
	protected function get_mapped_language_on_local( $iub_lang_code ) {
		$result        = array();
		$iub_lang_code = strtolower( str_replace( '-', '_', $iub_lang_code ) );

		foreach ( iubenda()->languages_locale as $wordpress_locale => $lang_code ) {
			// lower case and replace - with underscore..
			$lower_wordpress_locale = strtolower( str_replace( '-', '_', $wordpress_locale ) );
			// Map after all both codes becomes lower case and underscore..

			// Map en iubenda language to WordPress languages en_us..
			if ( 'en' === $iub_lang_code && 'en_us' === $lower_wordpress_locale ) {
				$result[] = $lang_code;
				continue;
			}

			// Map iubenda language to WordPress languages.
			if ( $iub_lang_code === $lower_wordpress_locale ) {
				$result[] = $lang_code;
				continue;
			}

			// Map pt iubenda language to pt-pt.
			if ( 'pt' === $iub_lang_code && 'pt_pt' === $lower_wordpress_locale ) {
				$result[] = $lang_code;
				continue;
			}

			// Cases iubenda languages without _ mapped to.
			if ( strstr( $lower_wordpress_locale, '_', true ) === $iub_lang_code && 'pt' !== $iub_lang_code ) {
				$result[] = $lang_code;
				continue;
			}
			// Map any XX_ iubenda language to any WordPress languages starts with XX_.
			if ( ( strpos( $iub_lang_code, '_' ) === 0 && strstr( $iub_lang_code, '_', true ) ) && ( strpos( $lower_wordpress_locale, '_' ) === 0 && strstr( $lower_wordpress_locale, '_', true ) ) ) {
				$result[] = $lang_code;
				continue;
			}

			if ( $lower_wordpress_locale === $iub_lang_code ) {
				$result[] = $lang_code;
				continue;
			}
		}

		return $result;
	}

	/**
	 * Quick generator API
	 */
	public function quick_generator_api() {
		iub_verify_ajax_request( 'iub_quick_generator_callback_nonce', 'iub_nonce' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$body = iub_array_get( $_POST, 'payload' );
		$user = iub_array_get( $body, 'user' );

		$public_ids       = array();
		$privacy_policies = array();
		$site_id          = null;

		$multi_lang = ( iubenda()->multilang && ! empty( iubenda()->languages ) );

		foreach ( iub_array_get( $body, 'privacy_policies', array() ) ?? array() as $key => $privacy_policy ) {
			// getting site id to save it into Iubenda global option.
			if ( ! $site_id ) {
				$site_id = sanitize_key( iub_array_get( $privacy_policy, 'site_id' ) );
			}

			if ( $multi_lang ) {
				$local_lang_codes = $this->get_mapped_language_on_local( $privacy_policy['lang'] );
				if ( $local_lang_codes ) {
					foreach ( $local_lang_codes as $local_lang_code ) {
						$privacy_policies[ $local_lang_code ] = $privacy_policy;

						// getting public id to save it into Iubenda global option default lang.
						$public_ids[ $local_lang_code ] = sanitize_key( iub_array_get( $privacy_policy, 'public_id' ) );
					}
				}

				// Getting supported local languages intersect with iubenda supported languages.
				$iubenda_intersect_supported_languages = ( new Language_Helper() )->get_local_supported_language();

				// Fallback to default language if no supported local languages intersect with iubenda supported languages.
				if ( empty( $iubenda_intersect_supported_languages ) ) {
					$public_ids[ iubenda()->lang_default ] = sanitize_key( iub_array_get( $privacy_policy, 'public_id' ) );
				}
			} else {
				$privacy_policies['default'] = $privacy_policy;

				// getting public id to save it into Iubenda global option default lang.
				$public_ids['default'] = sanitize_key( iub_array_get( $privacy_policy, 'public_id' ) );
			}
		}

		$configuration = array(
			'website'          => $site_id,
			'user'             => array(
				'id'    => sanitize_key( iub_array_get( $user, 'id' ) ),
				'email' => sanitize_email( iub_array_get( $user, 'email' ) ),
			),
			'privacy_policies' => $privacy_policies,
		);

		iubenda()->iub_update_options( Iubenda_Settings::IUB_QG_RESPONSE, $configuration );
		iubenda()->iub_update_options(
			'iubenda_global_options',
			array(
				'site_id'    => $site_id,
				'public_ids' => $public_ids,
			)
		);

		iubenda()->notice->add_notice( 'iub_legal_documents_generated_success' );

		wp_send_json(
			array(
				'status'   => 'done',
				'redirect' => admin_url( 'admin.php?page=iubenda&view=integrate-setup' ),
			)
		);
	}

	/**
	 * Integrate setup.
	 */
	public function integrate_setup() {
		iub_verify_ajax_request( 'iub_integrate_setup', 'iub_nonce' );

		// Saving iubenda plugin settings.
		( new Iubenda_Plugin_Setting_Service() )->plugin_settings_save_options();

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( (string) iub_array_get( $_POST, 'cookie_law' ) === 'on' ) {
			// Saving CS data with CS function.
			( new Iubenda_CS_Product_Service() )->saving_cs_options();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( (string) iub_array_get( $_POST, 'privacy_policy' ) === 'on' ) {
			// Saving PP data with PP function.
			( new Iubenda_PP_Product_Service() )->saving_pp_options();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( (string) iub_array_get( $_POST, 'cookie_law' ) === 'on' || (string) iub_array_get( $_POST, 'privacy_policy' ) === 'on' ) {
			// add notice that`s notice user the integration has been done successfully.
			iubenda()->notice->add_notice( 'iub_products_integrated_success' );
		}

		// Encourage user to verify his account.
		iubenda()->notice->add_notice( 'iub_user_needs_to_verify_his_account' );

		wp_send_json( array( 'status' => 'done' ) );
	}

	/**
	 * Auto detect forms
	 */
	public function auto_detect_forms() {
		iub_verify_ajax_request( 'iub_auto_detect_forms_nonce', 'iub_nonce' );
		iubenda()->forms->autodetect_forms();

		require_once IUBENDA_PLUGIN_PATH . 'views/partials/auto-detect-forms.php';
		wp_die();
	}

	/**
	 * Add footer
	 */
	public function add_footer() {
		if ( (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true' && (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) === 'automatic' ) {
			echo esc_html( $this->pp_button() );
		}
		if ( (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true' && (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) === 'automatic' ) {
			echo esc_html( $this->tc_button() );
		}
	}

	/**
	 * TC button shortcode
	 *
	 * @return array|ArrayAccess|mixed|string|null
	 */
	public function tc_button_shortcode() {
		if ( ( (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true' ) && ( (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) === 'manual' ) ) {
			return $this->tc_button();
		}

		return '[iub-tc-button]';
	}

	/**
	 * TC button
	 *
	 * @return array|ArrayAccess|mixed|null
	 */
	public function tc_button() {
		if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
			$code = (string) iub_array_get( iubenda()->options, 'tc.code_' . iubenda()->lang_current );
		} else {
			$code = (string) iub_array_get( iubenda()->options, 'tc.code_default' );
		}

		// Clean the code if has tampered script.
		$iubenda_code_extractor = new Iubenda_Code_Extractor();
		try {
			if ( ! empty( $code ) && $iubenda_code_extractor->has_tampered_scripts( $code ) ) {
				$code = $iubenda_code_extractor->clean_tampered_scripts( $code );
			}
		} catch ( Exception $e ) {
			iub_caught_exception( $e );
		} catch ( Error $e ) {
			iub_caught_exception( $e );
		}

		return $code;
	}

	/**
	 * PP button shortcode
	 *
	 * @return array|ArrayAccess|mixed|string|null
	 */
	public function pp_button_shortcode() {
		if ( (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true' && (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) !== 'automatic' ) {
			return $this->pp_button();
		}

		return '[iub-pp-button]';
	}

	/**
	 * PP button
	 *
	 * @return array|ArrayAccess|mixed|string|null
	 */
	public function pp_button() {
		$privacy_policy_generator = new Privacy_Policy_Generator();

		if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
			$code = (string) iub_array_get( iubenda()->options, 'pp.code_' . iubenda()->lang_current );
		} else {
			$code = (string) iub_array_get( iubenda()->options, 'pp.code_default' );
		}

		if ( empty( $code ) ) {
			if ( iubenda()->multilang && ! empty( iubenda()->lang_current ) ) {
				$public_id = iub_array_get( iubenda()->options['global_options'], 'public_ids.' . iubenda()->lang_current );
			} else {
				$public_id = iub_array_get( iubenda()->options['global_options'], 'public_ids.default' );
			}

			$code = $privacy_policy_generator->handle( 'default', $public_id, iub_array_get( iubenda()->options, 'pp.button_style' ) );
		}

		// Clean the code if has tampered script.
		$iubenda_code_extractor = new Iubenda_Code_Extractor();
		try {
			if ( ! empty( $code ) && $iubenda_code_extractor->has_tampered_scripts( $code ) ) {
				$code = $iubenda_code_extractor->clean_tampered_scripts( $code );
			}
		} catch ( Exception $e ) {
			iub_caught_exception( $e );
		} catch ( Error $e ) {
			iub_caught_exception( $e );
		}

		return $code;
	}

	/**
	 * Saving CS options from ajax request
	 */
	public function cs_ajax_save() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		iub_verify_ajax_request( 'iub_save_cs_options_nonce', 'iub_cs_nonce' );
		( new Iubenda_CS_Product_Service() )->saving_cs_options( false );

		wp_send_json( array( 'status' => 'done' ) );
	}

	/**
	 * Saving PP options from ajax request
	 */
	public function pp_ajax_save() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		iub_verify_ajax_request( 'iub_save_pp_options_nonce', 'iub_pp_nonce' );
		( new Iubenda_PP_Product_Service() )->saving_pp_options();

		wp_send_json( array( 'status' => 'done' ) );
	}

	/**
	 * Saving plugin settings options from ajax request
	 */
	public function plugin_settings_ajax_save() {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		iub_verify_ajax_request( 'iub_save_plugin_settings_options_nonce', 'iub_plugin_settings_nonce' );
		( new Iubenda_Plugin_Setting_Service() )->plugin_settings_save_options( false );

		wp_send_json( array( 'status' => 'done' ) );
	}

	/**
	 * Saving Cons options from ajax request
	 */
	public function cons_ajax_save() {
		iub_verify_ajax_request( 'iub_save_cons_options_nonce', 'iub_cons_nonce' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$public_api_key = iub_array_get( $_POST, 'iubenda_consent_solution.public_api_key' ) ? sanitize_text_field( wp_unslash( iub_array_get( $_POST, 'iubenda_consent_solution.public_api_key' ) ) ) : '';

		if ( empty( $public_api_key ) ) {
			wp_send_json(
				array(
					'status'       => 'error',
					'responseText' => esc_html__( 'invalid public API key', 'iubenda' ),
				)
			);
		}

		$product_option['configured']     = 'true';
		$product_option['public_api_key'] = $public_api_key;

		// Merge old cons options with new options.
		$old_options = iubenda()->options['cons'];
		$new_options = array_merge( $old_options, $product_option );

		// Update Database and current instance with new TC options.
		iubenda()->options['cons'] = $new_options;
		iubenda()->iub_update_options( 'iubenda_consent_solution', $new_options );

		iubenda()->options['activated_products']['iubenda_consent_solution'] = 'true';
		iubenda()->iub_update_options( 'iubenda_activated_products', iubenda()->options['activated_products'] );

		wp_send_json( array( 'status' => 'done' ) );
	}

	/**
	 * Saving TC options from ajax request
	 */
	public function tc_ajax_save() {
		iub_verify_ajax_request( 'iub_save_tc_options_nonce', 'iub_tc_nonce' );
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		( new Iubenda_TC_Product_Service() )->saving_tc_options();

		wp_send_json( array( 'status' => 'done' ) );
	}

	/**
	 * Add amp permission error
	 */
	public function add_amp_permission_error() {
		iubenda()->notice->add_notice( 'iub_amp_file_creation_fail' );
	}
}
