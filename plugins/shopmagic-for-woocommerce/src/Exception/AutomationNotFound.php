<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Exception;

use WPDesk\ShopMagic\Components\Database\Abstraction\EntityNotFound;
use WPDesk\ShopMagic\Components\Database\Abstraction\PostNotFound;

/**
 * Thrown when referenced Automation (e.g. by ID) cannot be found.
 */
final class AutomationNotFound extends PostNotFound {

	public static function with_id( $id ): EntityNotFound {
		return new self(
			sprintf(
				esc_html__('Automation with ID %d does not exists.', 'shopmagic-for-woocommerce'),
				$id
			)
		);
	}

	public static function invalid_type( int $id ): PostNotFound {
		return new self(
			sprintf(
				esc_html__('Entry returned for ID %d is not an automation. Make sure you use correct ID.', 'shopmagic-for-woocommerce'),
				$id
			)
		);
	}
}
