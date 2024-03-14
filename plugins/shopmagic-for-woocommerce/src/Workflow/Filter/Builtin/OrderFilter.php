<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\Builtin;

use WPDesk\ShopMagic\Workflow\Event\EventFactory2;
use WPDesk\ShopMagic\Workflow\Filter\FilterUsingComparisonTypes;

abstract class OrderFilter extends FilterUsingComparisonTypes {

	public function get_group_slug(): string {
		return EventFactory2::GROUP_ORDERS;
	}

	public function get_required_data_domains(): array {
		return [ \WC_Order::class ];
	}
}
