<?php

namespace CTXFeed\V5\Compatibility;

class WPMLTranslation {

	public function __construct(){
		add_action( 'before_woo_feed_generate_batch_data', [$this,'woo_feed_switch_language'], 10, 1 );
		add_action( 'after_woo_feed_generate_batch_data', [$this,'woo_feed_restore_language'], 10, 1 );

		add_action( 'before_woo_feed_get_product_information', [$this,'woo_feed_switch_language'], 10, 1 );
		add_action( 'after_woo_feed_get_product_information', [$this,'woo_feed_restore_language'], 10, 1 );
	}

	/**
	 * Restore Default Language.
	 * Switches WPML's query language to site's default language
	 *
	 * @return void
	 * @see SitePress::get_default_language()
	 * @see woo_feed_switch_language()
	 * @global SitePress $sitepress
	 */
	public function woo_feed_restore_language($language_code) {
		if ( is_array( $language_code ) && isset( $language_code['feedLanguage'] ) ) {
			$language_code = $language_code['feedLanguage'];
		}else {
			$language_code = '';
		}
		if ( class_exists( 'SitePress', false ) ) {
			// WPML restore Language.
			global $sitepress;
			$language_code = $sitepress->get_default_language();
		}
		/**
		 * Filter to hijack Default Language code before restore
		 *
		 * @param string $language_code
		 */
		//$language_code = apply_filters( 'woo_feed_restore_language', $language_code );
		if ( ! empty( $language_code ) ) {
			$this->woo_feed_switch_language( $language_code );
		}
	}

	/**
	 * Switch Current language.
	 * Switches WPML's query language
	 *
	 * @param array|string $language_code The language code to switch to Or the feed config to get the language code
	 *                                    If set to null it restores the original language
	 *                                    If set to 'all' it will query content from all active languages
	 *                                    Defaults to null
	 * @param bool|string $cookie_lang Optionally also switch the cookie language
	 *                                    to the value given. default is true.
	 *
	 * @return void
	 * @global SitePress $sitepress
	 * @see SitePress::switch_lang()
	 * @see SitePress::get_current_language()
	 */
	public function woo_feed_switch_language( $language_code, $cookie_lang = true ) {

		if ( is_array( $language_code ) && isset( $language_code['feedLanguage'] ) ) {
			$language_code = $language_code['feedLanguage'];
		} else {
			if ( isset( $language_code->feed_info ) && is_array( $language_code->feed_info ) && isset( $language_code->feed_info['feedLanguage'] ) ) {
				$language_code = $language_code->feed_info['feedLanguage'];
			}

		}

		if ( ! empty( $language_code ) ) {
			if ( class_exists( 'SitePress', false ) ) {
				// WPML Switch Language.
				global $sitepress;
                if( is_array( $language_code ) ) {
                    $language_code = $sitepress->get_current_language();
                }
				if ( $sitepress->get_current_language() !== $language_code ) {
					$sitepress->switch_lang( $language_code, $cookie_lang );
				}
			}

			// when polylang plugin is activated
			if ( defined( 'POLYLANG_BASENAME' ) || function_exists( 'PLL' ) ) {
				if ( pll_current_language() !== $language_code ) {
					PLL()->curlang = PLL()->model->get_language( $language_code );
				}
			}
		}
	}
}
