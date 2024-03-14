<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\WooCommerceOrderNoteBasedPlaceholder;


final class OrderNoteAuthor extends WooCommerceOrderNoteBasedPlaceholder {

	public function get_slug(): string {
		return 'author';
	}

	public function get_description(): string {
		return esc_html__( 'Display the author of order note.', 'shopmagic-for-woocommerce' );
	}

	public function value( array $parameters ): string {
		if ( $this->resources->has( \WP_Comment::class ) ) {
			return $this->resources->get( \WP_Comment::class )->comment_author;
		}

		return '';
	}
}
