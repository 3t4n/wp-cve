<?php
/**
 * Languages
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta;

/**
 * Class Language
 *
 * @package NovaPoshta
 */
class Language {

	/**
	 * Current site language
	 *
	 * @var string
	 */
	private $current_language;


	/**
	 * Get current language
	 *
	 * @return string
	 */
	public function get_current_language(): string {

		if ( $this->current_language ) {
			return $this->current_language;
		}

		$current_language       = apply_filters( 'shipping_nova_poshta_for_woocommerce_current_language', get_locale() );
		$this->current_language = in_array( $current_language, [ 'uk_UA', 'uk' ], true ) ? 'ua' : 'ru';

		return $this->current_language;
	}

}
