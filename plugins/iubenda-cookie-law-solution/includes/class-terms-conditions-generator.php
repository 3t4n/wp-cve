<?php
/**
 * Iubenda terms and conditions generator class.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Terms_Conditions_Generator
 */
class Terms_Conditions_Generator {

	/**
	 * Generate TC code
	 *
	 * @param string $language language.
	 * @param string $cookie_policy_id cookie_policy_id.
	 * @param string $button_style button_style.
	 *
	 * @return string
	 */
	public function handle( string $language, $cookie_policy_id, $button_style ) {
		// Return if there is no public id.
		if ( ! $cookie_policy_id ) {
			return null;
		}
		$terms_button_title = 'Terms and Conditions';

		// Handle if the website is single language.
		if ( 'default' === $language ) {
			$language = iubenda()->lang_current ?? iubenda()->lang_default;
		}

		// If the language has translation in iubenda plugin.
		$language_code = array_search( strtolower( $language ), array_map( 'strtolower', iubenda()->lang_mapping ), true ) ?? null;
		if ( $language_code ) {
			$terms_button_title = __iub_trans( 'Terms and Conditions', $language_code );
		}

		$tc_configuration = '
		<a href="https://www.iubenda.com/terms-and-conditions/' . $cookie_policy_id . '" class="iubenda-' . $button_style . ' no-brand iubenda-noiframe iubenda-embed iubenda-noiframe " title="' . $terms_button_title . '">' . $terms_button_title . '</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
		';

		return $tc_configuration;
	}
}
