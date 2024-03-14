<?php
/**
 * Iubenda language helper.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Language_Helper
 */
class Language_Helper {

	/**
	 * Get the language code of logged in user profile
	 *
	 * @param bool $lower_case if true lower case.
	 * @return string
	 */
	public function get_user_profile_language_code( $lower_case = false ): string {
		$iub_supported_languages = iubenda()->supported_languages;
		$user_profile_language   = get_bloginfo( 'language' );

		// Check if the current user language is supported by iubenda.
		if ( iub_array_get( $iub_supported_languages, $user_profile_language ) ?? null ) {
			$result = $user_profile_language;
		} else {
			// Remove the country from the language code to check if iubenda supports the current user language without the country.
			$locale = (array) explode( '-', $user_profile_language );
			$result = iub_array_get( $iub_supported_languages, $locale[0] ) ? $locale[0] : null;
		}

		// Fallback to EN if current user language is not supported.
		if ( ! $result ) {
			$result = 'en';
		}

		return $lower_case ? strtolower( $result ) : $result;
	}

	/**
	 * Get the site's default language code
	 *
	 * @param bool $lower_case if true lower case.
	 * @return string
	 */
	public function get_default_website_language_code( $lower_case = false ): string {
		if ( iub_is_polylang_active() && function_exists( 'pll_default_language' ) ) {
			$default_language_local_code = pll_default_language( 'locale' );
			$website_language_code       = (string) iub_array_get( iubenda()->lang_mapping, $default_language_local_code );
		} elseif ( iub_is_wpml_active() ) {
			global $sitepress;
			$website_language_code = $sitepress->get_default_language();

			// Special handling if the default language is pt-pt.
			if ( 'pt-pt' === $website_language_code ) {
				$website_language_code = 'pt';
			}
		} else {
			$website_language_code = iub_array_get( iubenda()->lang_mapping, get_locale() );
		}

		return $lower_case ? strtolower( $website_language_code ) : $website_language_code;
	}

	/**
	 * Get intersect supported local languages intersect with iubenda supported languages
	 *
	 * @return array
	 */
	public function get_local_supported_language() {
		if ( iubenda()->multilang && ! empty( iubenda()->languages ) ) {
			$local_languages = array_keys( iubenda()->languages_locale );
			$local_languages = $this->language_unification_locale_to_iub( $local_languages );

			$iubenda_intersect_supported_langs = array_intersect( $local_languages, array_keys( iubenda()->supported_languages ) );
		} else {
			$iubenda_intersect_supported_langs = array_intersect( array( iub_array_get( iubenda()->lang_mapping, get_locale() ) ), array_values( iubenda()->lang_mapping ) );
		}

		return $iubenda_intersect_supported_langs;
	}

	/**
	 * Unification all languages from locale to iub language
	 *
	 * @param   array $locale_languages locale_languages.
	 *
	 * @return array
	 */
	private function language_unification_locale_to_iub( array $locale_languages ) {
		$iub_languages = array();

		foreach ( $locale_languages as $language ) {
			if ( (bool) iub_array_get( iubenda()->lang_mapping, $language ) ) {
				$iub_languages[] = iub_array_get( iubenda()->lang_mapping, $language );
			} elseif ( strpos( $language, '_' ) ) {
				$iub_languages[] = strstr( $language, '_', true );
			} else {
				$iub_languages[] = $language;
			}
		}

		return $iub_languages;
	}
}
