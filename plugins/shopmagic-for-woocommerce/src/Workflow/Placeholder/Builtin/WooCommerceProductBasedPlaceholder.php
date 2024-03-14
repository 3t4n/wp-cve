<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin;

use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;

abstract class WooCommerceProductBasedPlaceholder extends Placeholder {

	public function get_required_data_domains(): array {
		return [ \WC_Product::class ];
	}

	protected function get_product(): \WC_Product {
		return $this->resources->get( \WC_Product::class );
	}
}
