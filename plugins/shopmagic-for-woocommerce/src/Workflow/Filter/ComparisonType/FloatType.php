<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

class FloatType extends AbstractType {
	/**
	 * @var string
	 */
	private const IS = 'is';

	/**
	 * @inheritDoc
	 */
	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		$actual_value   = (float) str_replace( ',', '.', (string) $actual_value );
		$expected_value = (float) str_replace( ',', '.', (string) $expected_value );

		switch ( $compare_type ) {

			case self::IS:
				return $actual_value === $expected_value;

			case 'is_not':
				return $actual_value !== $expected_value;

			case 'greater_than':
				return $actual_value > $expected_value;

			case 'less_than':
				return $actual_value < $expected_value;

		}
		// validate 'multiple of' compares, only accept integers.
		if ( ! $this->is_whole_number( $actual_value ) ) {
			return false;
		}
		if ( ! $this->is_whole_number( $expected_value ) ) {
			return false;
		}

		$actual_value   = (int) $actual_value;
		$expected_value = (int) $expected_value;
		if ( $compare_type == 'multiple_of' ) {
			return $actual_value % $expected_value === 0;
		}
		if ( $compare_type == 'not_multiple_of' ) {
			return $actual_value % $expected_value !== 0;
		}

		return false;
	}

	/**
	 * @param $number
	 *
	 * @return bool
	 */
	private function is_whole_number( float $number ) {
		return floor( $number ) === $number;
	}

	/**
	 * @inheritDoc
	 */
	public function get_conditions(): array {
		return [
			self::IS       => __( self::IS, 'shopmagic-for-woocommerce' ),
			'is_not'       => __( 'is not', 'shopmagic-for-woocommerce' ),
			'greater_than' => __( 'is greater than', 'shopmagic-for-woocommerce' ),
			'less_than'    => __( 'is less than', 'shopmagic-for-woocommerce' ),
		];
	}
}
