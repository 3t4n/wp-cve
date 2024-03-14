<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Filter\Builtin\OrderFilter;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\ComparisonType;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\ProductSelectType;


final class OrderItems extends OrderFilter {
	public function get_id(): string {
		return 'shopmagic_product_purchased_filter';
	}

	public function get_name(): string {
		return __( 'Order - Items', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation if products in order matches the rule.', 'shopmagic-for-woocommerce' );
	}

	public function passed(): bool {
		$order = $this->get_order();

		$items        = $order->get_items();
		$products_ids = [];
		foreach ( $items as $item ) {
			$products_ids[] = $item['product_id'];
			$products_ids[] = $item['variation_id'];
		}

		return $this->get_type()->passed(
			$this->fields_data->get( ProductSelectType::VALUE_KEY ),
			$this->fields_data->get( ProductSelectType::CONDITION_KEY ),
			$products_ids
		);
	}

	protected function get_type(): ComparisonType {
		return new ProductSelectType();
	}
}
