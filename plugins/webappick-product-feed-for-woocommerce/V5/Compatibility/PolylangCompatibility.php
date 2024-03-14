<?php
/**
 * Polylang Compatibility
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Compatibility
 * @category   MyCategory
 */

namespace CTXFeed\V5\Compatibility;

/**
 * Class PolylangCompatibility
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Compatibility
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   MyCategory
 */
class PolylangCompatibility {

	/**
	 * PolylangCompatibility Constructor.
	 */
	public function __construct() {
		add_action( 'before_woo_feed_get_product_information', array( $this, 'switch_language' ), 10, 1 );
		add_action( 'after_woo_feed_get_product_information', array( $this, 'restore_language' ), 10, 1 );

		add_action( 'before_woo_feed_generate_batch_data', array( $this, 'switch_language' ), 10, 1 );
		add_action( 'after_woo_feed_generate_batch_data', array( $this, 'restore_language' ), 10, 1 );
	}

	/**
	 * Switch language before feed generation
	 *
	 * @param \CTXFeed\V5\Utility\Config $config Feed config.
	 * @param bool                       $cookie_lang Switch cookie language.
	 */
	public function switch_language( $config, $cookie_lang = true ) {// phpcs:ignore
		$language_code = $config->get_feed_language();

		if ( !defined( 'POLYLANG_BASENAME' ) && !function_exists( 'PLL' ) ) {
            return;
        }

        if ( pll_current_language() === $language_code ) {
            return;
        }

        PLL()->curlang = PLL()->model->get_language( $language_code );
	}

	/**
	 * Restore language after feed generation
	 *
	 * @param \CTXFeed\V5\Utility\Config $config Feed config.
	 * @param bool                       $cookie_lang Restore cookie language.
	 */
	public function restore_language( $config, $cookie_lang = true ) {// phpcs:ignore
		$language_code = pll_default_language();

		if ( !defined( 'POLYLANG_BASENAME' ) && !function_exists( 'PLL' ) ) {
            return;
        }

        if ( pll_current_language() === $language_code ) {
            return;
        }

        PLL()->curlang = PLL()->model->get_language( $language_code );
	}

}
