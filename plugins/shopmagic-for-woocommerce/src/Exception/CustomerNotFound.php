<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Exception;

use RuntimeException;

final class CustomerNotFound extends RuntimeException implements ShopMagicException {
	/**
	 * @param int|string $id
	 *
	 * @return static
	 */
	public static function with_id( $id ): self {
		if ( is_numeric( $id ) ) {
			$hint = esc_html__( 'Are you sure user with this ID exists?', 'shopmagic-for-woocommerce' );
		} else {
			$hint = esc_html__( 'You asked for a guest, which might be deleted.', 'shopmagic-for-woocommerce' );
		}

		return new self(
			sprintf(
				esc_html__(
					'Failed to find customer with ID #%s.', 'shopmagic-for-woocommerce'
				),
				$id
			) . ' ' . $hint
		);
	}
}
