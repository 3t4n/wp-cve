<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin;

use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;

abstract class WooCommerceOrderBasedPlaceholder extends Placeholder {

	public function get_group_slug(): string {
		return 'order';
	}

	public function get_required_data_domains(): array {
		return [ \WC_Order::class ];
	}

	protected function get_order(): \WC_Order {
		return $this->resources->get( \WC_Order::class );
	}
}
