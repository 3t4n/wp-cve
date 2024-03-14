<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

use ShopMagicVendor\WPDesk\Forms\Field\SelectField;


final class BoolType implements ComparisonType {
	/**
	 * @var string
	 */
	public const VALUE_KEY = 'value';

	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		return $expected_value === $actual_value;
	}

	/**
	 * @return mixed[]
	 */
	public function get_conditions(): array {
		return [];
	}

	/**
	 * @return mixed[]
	 */
	public function get_fields(): array {
		return [
			( new SelectField() )
				->set_options(
					[
						'yes' => __( 'Yes', 'shopmagic-for-woocommerce' ),
						'no'  => __( 'No', 'shopmagic-for-woocommerce' ),
					]
				)
				->set_name( 'value' ),
		];
	}
}
