<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Exception;

final class ItemNotExists extends \OutOfBoundsException implements ShopMagicException {

	/**
	 * @param class-string $resource_type
	 * @param string $needle
	 *
	 * @return static
	 */
	public static function resource_not_found( string $resource_type, string $needle ): self {
		return new ItemNotExists(
			sprintf(
				__("There is no %s named '%s'. Make sure all required extensions are enabled.", 'shopmagic-for-woocommerce' ),
				(new \ReflectionClass($resource_type))->getShortName(),
				$needle
			)
		);
	}

}
