<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Filter\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Filter\Builtin\OrderNoteFilter;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\ComparisonType;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\StringType;


final class OrderNoteContent extends OrderNoteFilter {
	public function get_id(): string {
		return 'order_note_content';
	}

	public function get_name(): string {
		return __( 'Order Note - Content', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__( 'Run automation if note content matches the rule.', 'shopmagic-for-woocommerce' );
	}

	public function passed(): bool {
		return $this->get_type()->passed(
			$this->fields_data->get( StringType::VALUE_KEY ),
			$this->fields_data->get( StringType::CONDITION_KEY ),
			$this->get_order_note()->comment_content
		);
	}

	protected function get_type(): ComparisonType {
		return new StringType();
	}
}
