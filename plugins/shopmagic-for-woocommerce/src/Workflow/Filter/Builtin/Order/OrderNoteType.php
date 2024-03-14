<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\Builtin\Order;

use WPDesk\ShopMagic\Workflow\Filter\Builtin\OrderNoteFilter;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\ComparisonType;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\SelectOneToOneType;


final class OrderNoteType extends OrderNoteFilter {
	/** @var string */
	private const PARAM_PRIVATE = 'private';

	/** @var string */
	private const PARAM_CUSTOMER = 'customer';

	public function get_id(): string {
		return 'order_note_type';
	}

	public function get_name(): string {
		return __( 'Order Note - Type', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation if order note is internal or for customer.', 'shopmagic-for-woocommerce' );
	}

	public function passed(): bool {
		$order_note      = $this->get_order_note();
		$order_note_type = get_comment_meta( $order_note->comment_ID, 'is_customer_note', true ) ? self::PARAM_CUSTOMER : self::PARAM_PRIVATE;

		return $this->get_type()->passed(
			$this->fields_data->get( SelectOneToOneType::VALUE_KEY ),
			$this->fields_data->get( SelectOneToOneType::CONDITION_KEY ),
			$order_note_type
		);
	}

	protected function get_type(): ComparisonType {
		return new SelectOneToOneType(
			[
				self::PARAM_PRIVATE  => __( 'Private Note', 'shopmagic-for-woocommerce' ),
				self::PARAM_CUSTOMER => __( 'Customer Note', 'shopmagic-for-woocommerce' ),
			]
		);
	}
}
