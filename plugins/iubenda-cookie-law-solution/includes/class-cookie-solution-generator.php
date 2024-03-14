<?php
/**
 * Iubenda cookie solution generator class.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cookie_Solution_Generator
 */
class Cookie_Solution_Generator {

	/**
	 * Generate CS code
	 *
	 * @param   string      $language          language.
	 * @param   string|null $site_id           site_id.
	 * @param   string      $cookie_policy_id  cookie_policy_id.
	 * @param   array       $args              args.
	 *
	 * @return string
	 */
	public function handle( string $language, $site_id, $cookie_policy_id, $args = array() ) {
		// Return if there is no public ID or site ID.
		if ( ! $cookie_policy_id || ! $site_id ) {
			return null;
		}

		// Handle if the website is single language.
		if ( 'default' === $language ) {
			$language = ! empty( iubenda()->lang_current ) ? iubenda()->lang_current : iubenda()->lang_default;
		}

		// Special handling if the language is pt-pt.
		if ( 'pt-pt' === strtolower( $language ) ) {
			$language = 'pt';
		}

		// Special handling if the language has a country to replace the country code with capital letters.
		if ( strpos( $language, '-' ) ) {
			$language    = explode( '-', $language );
			$language[1] = strtoupper( $language[1] );
			$language    = implode( '-', $language );
		}

		// No need to sanitize because we just build the embed code.
		$before_configuration = '
            <script type="text/javascript">
            var _iub = _iub || [];
            _iub.csConfiguration =';
		$after_configuration  = '</script>';

		$cs_configuration                                 = array(
			'floatingPreferencesButtonDisplay' => 'bottom-right',
			'lang'                             => $language,
			'siteId'                           => $site_id,
			'cookiePolicyId'                   => $cookie_policy_id,
			'whitelabel'                       => false,
		);
		$cs_configuration['banner']['closeButtonDisplay'] = false;

		$legislation             = (array) iub_array_get( $args, 'legislation' );
		$legislation_gdpr_status = (bool) iub_array_get( $legislation, 'gdpr' );
		$legislation_uspr_status = (bool) iub_array_get( $legislation, 'uspr' );
		$legislation_lgpd_status = (bool) iub_array_get( $legislation, 'lgpd' );
		$legislation_all_status  = (bool) iub_array_get( $legislation, 'all' );
		$tcf_status              = (bool) iub_array_get( $args, 'tcf' );
		$require_consent         = (string) iub_array_get( $args, 'require_consent' );
		$explicit_reject         = (bool) iub_array_get( $args, 'explicit_reject' );
		$explicit_accept         = (bool) iub_array_get( $args, 'explicit_accept' );

		if ( $legislation_gdpr_status || $legislation_lgpd_status || $legislation_all_status ) {
			$cs_configuration['perPurposeConsent']            = true;
			$cs_configuration['banner']['listPurposes']       = true;
			$cs_configuration['banner']['explicitWithdrawal'] = true;

			if ( $explicit_reject || $tcf_status ) {
				$cs_configuration['banner']['rejectButtonDisplay'] = true;
			}

			if ( $explicit_accept || $tcf_status ) {
				$cs_configuration['banner']['acceptButtonDisplay']    = true;
				$cs_configuration['banner']['customizeButtonDisplay'] = true;
			}
		}

		// If legislation is USPR or All.
		if ( $legislation_uspr_status || $legislation_all_status ) {
			$cs_configuration['enableUspr'] = true;
		}

		// If legislation is LGDP or All.
		if ( $legislation_lgpd_status || $legislation_all_status ) {
			$cs_configuration['enableLgpd'] = true;
		}

		// If GDPR and All options is not selected, only if USPR or/and LGDP selected.
		if ( ! $legislation_gdpr_status && ! $legislation_all_status ) {
			$cs_configuration['enableGdpr'] = false;
		}

		// If legislation is GDPR or ALL.
		if ( $legislation_gdpr_status || $legislation_all_status ) {

			// If Require Consent is Worldwide.
			if ( 'worldwide' === $require_consent ) {
				$cs_configuration['googleAdditionalConsentMode'] = true;
			}
		}

		if ( $legislation_uspr_status || $legislation_lgpd_status || $legislation_all_status ) {
			$cs_configuration['invalidateConsentWithoutLog'] = true;
		}

		// conditions on TCF is enabled.
		if ( $tcf_status && ( $legislation_gdpr_status || $legislation_all_status ) ) {
			$cs_configuration['enableTcf']                    = true;
			$cs_configuration['banner']['closeButtonRejects'] = true;
			$cs_configuration['tcfPurposes']['1']             = true;
			$cs_configuration['tcfPurposes']['2']             = esc_attr( 'consent_only' );
			$cs_configuration['tcfPurposes']['3']             = esc_attr( 'consent_only' );
			$cs_configuration['tcfPurposes']['4']             = esc_attr( 'consent_only' );
			$cs_configuration['tcfPurposes']['5']             = esc_attr( 'consent_only' );
			$cs_configuration['tcfPurposes']['6']             = esc_attr( 'consent_only' );
			$cs_configuration['tcfPurposes']['7']             = esc_attr( 'consent_only' );
			$cs_configuration['tcfPurposes']['8']             = esc_attr( 'consent_only' );
			$cs_configuration['tcfPurposes']['9']             = esc_attr( 'consent_only' );
			$cs_configuration['tcfPurposes']['10']            = esc_attr( 'consent_only' );

			// No need to sanitize because we just build the embed code.
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$after_configuration .= '<script type="text/javascript" src="//cdn.iubenda.com/cs/tcf/stub-v2.js"></script><script type="text/javascript" src="//cdn.iubenda.com/cs/tcf/safe-tcf-v2.js"></script>
            ';
		}

		if ( $legislation_uspr_status || $legislation_all_status ) {
			// No need to sanitize because we just build the embed code.
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$after_configuration .= '<script type="text/javascript" src="//cdn.iubenda.com/cs/gpp/stub.js"></script>';
		}

		$cs_configuration['banner']['position'] = str_replace( 'full-', '', iub_array_get( $args, 'position' ) );

		$banner_style = (string) iub_array_get( $args, 'banner_style' );
		if ( 'light' === $banner_style ) {
			$cs_configuration['banner']['style']                       = 'light';
			$cs_configuration['banner']['textColor']                   = '#000000';
			$cs_configuration['banner']['backgroundColor']             = '#FFFFFF';
			$cs_configuration['banner']['customizeButtonCaptionColor'] = '#4D4D4D';
			$cs_configuration['banner']['customizeButtonColor']        = '#DADADA';
		} else {
			$cs_configuration['banner']['style'] = 'dark';
		}

		$background_overlay = iub_array_get( $args, 'background_overlay' );
		if ( $background_overlay ) {
			$cs_configuration['banner']['backgroundOverlay'] = true;
		}

		// No need to sanitize because we just build the embed code.
		// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		$after_configuration .= '<script type="text/javascript" src="//cdn.iubenda.com/cs/iubenda_cs.js" charset="UTF-8" async></script>';

		$autoblocking_feature_status = iubenda()->iub_auto_blocking->is_autoblocking_feature_available( $site_id );
		if ( $autoblocking_feature_status ) {
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$after_configuration .= '<script type="text/javascript" src="//iubenda.com/autoblocking/' . $site_id . '.js" charset="UTF-8" async></script>';
		} else {
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$after_configuration .= '<script type="text/javascript" src="//iubenda.com/sync/' . $site_id . '.js" charset="UTF-8" async></script>';
		}

		return $before_configuration . wp_json_encode( $cs_configuration ) . '; ' . $after_configuration;
	}
}
