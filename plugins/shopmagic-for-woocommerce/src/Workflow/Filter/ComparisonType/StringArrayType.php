<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

/**
 * Can be used when you want to check if single value is one of set given by string.
 */
final class StringArrayType extends AbstractType {
	/**
	 * @var string
	 */
	public const DELIMITER = ',';

	/**
	 * @inheritDoc
	 */
	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		$actual_value   = (string) $actual_value;
		$expected_value = explode( self::DELIMITER, $expected_value );
		if ( $compare_type == 'matches_any' ) {
			return \in_array( $actual_value, $expected_value, false );
		}
		if ( $compare_type == 'matches_none' ) {
			return ! \in_array( $actual_value, $expected_value, false );
		}

		return false;
	}

	/**
	 * @inheritDoc
	 * @return array{matches_any: mixed, matches_none: mixed}
	 */
	public function get_conditions(): array {
		return [
			'matches_any'  => __( 'matches any', 'shopmagic-for-woocommerce' ),
			'matches_none' => __( 'matches none', 'shopmagic-for-woocommerce' ),
		];
	}
}
