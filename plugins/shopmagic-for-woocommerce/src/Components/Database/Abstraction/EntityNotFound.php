<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction;

use WPDesk\ShopMagic\Exception\CannotProvideItemException;

class EntityNotFound extends CannotProvideItemException implements \WPDesk\ShopMagic\Exception\ShopMagicException {

	public static function failing_criteria( array $criteria, ?array $order = null ): self {
		$hint = '';
		if ( ! empty( $criteria ) ) {
			$hint = 'Where: ' . json_encode( $criteria );
		}

		if ( ! empty( $order ) ) {
			$hint = ' Order: ' . json_encode( $order );
		}

		return new self(
			sprintf(
				esc_html__( 'Failed to match any item in repository with requested criteria: [ %s ]', 'shopmagic-for-woocommerce' ),
				$hint
			)
		);
	}


	public static function with_id( $id ): self {
		return new self(
			sprintf(
				esc_html__(
					'Repository has no reference to item identified by `%s`. Are you sure this item exists?',
					'shopmagic-for-woocommerce'
				),
				$id
			)
		);
	}

}
