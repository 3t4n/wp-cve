<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

final class NullType implements ComparisonType {
	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		return true;
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
		return [];
	}
}
