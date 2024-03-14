<?php

namespace CTXFeed\V5\Compatibility;

/**
 * Class SitePressCompatibility
 * Compatibility with WPML
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Compatibility
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class SitePressCompatibility {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Return original post id for WPML Translated Product.
		add_filter( 'woo_feed_original_post_id', array( $this, 'original_post_id' ), 10, 1 );

		add_action( 'before_woo_feed_generate_batch_data', array( $this, 'switch_language' ), 10, 1 );
		add_action( 'after_woo_feed_generate_batch_data', array( $this, 'restore_language' ), 10, 1 );

		add_action( 'before_woo_feed_get_product_information', array( $this, 'switch_language' ), 10, 1 );
		add_action( 'after_woo_feed_get_product_information', array( $this, 'restore_language' ), 10, 1 );
	}

	/**
	 * Get WPML Original Post I'd.
	 * If WPML is not installed, then return the same id.
	 *
	 * @param int $product_id Translated Product ID.
	 *
	 * @return int
	 */
	public function original_post_id( $product_id ) {
		$lang = apply_filters( 'wpml_default_language', '' );

		/**
		 * Get translation of specific language for element id.
		 *
		 * @param int $elementId translated object id
		 * @param string $element_type object type (post type). If set to 'any' wpml will try to detect the object type
		 * @param bool|false $return_original_if_missing return the original if missing.
		 * @param string|null $language_code Language code to get the translation. If set to 'null', wpml will use current language.
		 */
		return apply_filters( 'wpml_object_id', $product_id, 'any', true, $lang );
	}

	/**
	 * Switch language before feed generation
	 *
	 * @param \CTXFeed\V5\Utility\Config $config Feed config.
	 * @param bool $cookie_lang Switch cookie language.
	 * @param string $switch_lang Language to switch.
	 */
	public function switch_language( $config, $cookie_lang = true, $switch_lang = null ) {
		if ( ! $switch_lang ) {
			$switch_lang = $config->get_feed_language();
		}
		// WPML Switch Language.
		global $sitepress;

		if ( $sitepress->get_current_language() === $switch_lang ) {
			return;
		}

		$sitepress->switch_lang( $switch_lang, $cookie_lang );
	}

	/**
	 * Restore language after feed generation
	 *
	 * @param \CTXFeed\V5\Utility\Config $config Feed config.
	 * @param bool $cookie_lang Restore cookie language.
	 */
	public function restore_language( $config, $cookie_lang = true ) {// phpcs:ignore
		global $sitepress;
		$language_code = $sitepress->get_default_language();

		if ( empty( $language_code ) ) {
			return;
		}

		$this->switch_language( $config, $cookie_lang, $language_code );
	}

}
