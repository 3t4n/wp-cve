<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction;

class PostNotFound extends EntityNotFound {

	public static function with_id( $id ): EntityNotFound {
		return new self(
			sprintf(
				esc_html__( 'Post with ID %d does not exists.', 'shopmagic-for-woocommerce' ),
				$id
			)
		);
	}

	public static function invalid_type( int $id ): self {
		return new self(
			sprintf(
				esc_html__( 'Entry returned for ID %d is not of expected post type. Make sure you use correct ID.', 'shopmagic-for-woocommerce' ),
				$id
			)
		);
	}
}
