<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

use ShopMagicVendor\WPDesk\Forms\FieldProvider;

interface ComparisonType extends FieldProvider {
	/**
	 * @param mixed  $expected_value
	 * @param mixed  $actual_value
	 */
	public function passed( $expected_value, string $compare_type, $actual_value ): bool;

	/**
	 * @return string[]
	 */
	public function get_conditions(): array;
}
