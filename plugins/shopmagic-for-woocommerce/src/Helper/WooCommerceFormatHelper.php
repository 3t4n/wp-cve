<?php
declare(strict_types=1);


namespace WPDesk\ShopMagic\Helper;

/**
 * Idea behind this helper is to facilitate various formatting methods that depends on WooCommerce.
 *
 * Access should be static to lower complexity. It can be refactored in the future when DI container is introduced.
 */
final class WooCommerceFormatHelper {
	/**
	 * @param string $shortcut ie. PL
	 *
	 * @return string ie. Poland
	 */
	public static function country_full_name( string $shortcut ): string {
		$countries = WC()->countries->get_countries();

		return $countries[ $shortcut ] ?? $shortcut;
	}
}
