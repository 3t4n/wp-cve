<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin;

use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;

abstract class WooCommerceOrderNoteBasedPlaceholder extends Placeholder {
	public function get_group_slug(): string {
		return 'order_note';
	}

	public function get_required_data_domains(): array {
		return [ \WP_Comment::class, \WC_Order::class ];
	}

	protected function get_order_note(): \WP_Comment {
		return $this->resources->get( \WP_Comment::class );
	}
}
