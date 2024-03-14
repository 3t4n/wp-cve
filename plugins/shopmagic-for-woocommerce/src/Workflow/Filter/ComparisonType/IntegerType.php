<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

final class IntegerType extends FloatType {
	/**
	 * @inheritDoc
	 */
	public function get_conditions(): array {
		// those conditions are supported illegally by FloatType.
		return array_merge(
			parent::get_conditions(),
			[
				'multiple_of'     => __( 'is a multiple of', 'shopmagic-for-woocommerce' ),
				'not_multiple_of' => __( 'is not a multiple of', 'shopmagic-for-woocommerce' ),
			]
		);
	}
}
