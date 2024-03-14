<?php
/**
 * Iubenda privacy policy generator class.
 *
 * @package  Iubenda
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Privacy_Policy_Generator
 */
class Privacy_Policy_Generator {

	/**
	 * Generate PP code
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
		$privacy_title = 'Privacy Policy';

		// Handle if the website is single language.
		if ( 'default' === $language ) {
			$language = iubenda()->lang_current ?? iubenda()->lang_default;
		}

		// If the language has translation in iubenda plugin.
		$language_code = array_search( strtolower( $language ), array_map( 'strtolower', iubenda()->lang_mapping ), true ) ?? null;
		if ( $language_code ) {
			$privacy_title = __iub_trans( 'Privacy Policy', $language_code );
		}

		$pp_configuration = '
        <a href="https://www.iubenda.com/privacy-policy/' . $cookie_policy_id . '" class="iubenda-' . $button_style . ' no-brand iubenda-noiframe iubenda-embed iubenda-noiframe " title="' . $privacy_title . '">' . $privacy_title . '</a><script type="text/javascript">(function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);</script>
        ';

		return $pp_configuration;
	}
}
