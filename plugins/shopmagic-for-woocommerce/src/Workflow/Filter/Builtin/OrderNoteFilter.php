<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\Builtin;

use WPDesk\ShopMagic\Workflow\Event\EventFactory2;
use WPDesk\ShopMagic\Workflow\Filter\FilterUsingComparisonTypes;

abstract class OrderNoteFilter extends FilterUsingComparisonTypes {
	public function get_group_slug(): string {
		return EventFactory2::GROUP_ORDERS;
	}

	public function get_required_data_domains(): array {
		return [ \WP_Comment::class ];
	}

	protected function get_order_note(): \WP_Comment {
		return $this->resources[ \WP_Comment::class ];
	}
}
