<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

use ShopMagicVendor\WPDesk\Forms\Field\DatePickerField;
use WPDesk\ShopMagic\FormField\Field\SelectField;


final class DateType extends AbstractType {
	/**
	 * @var string
	 */
	private const Y_M_D = 'Y-m-d';

	/**
	 * @inheritDoc
	 */
	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		if ( is_null( $actual_value ) ) {
			return false;
		}

		if ( $actual_value instanceof \DateTimeInterface ) {
			$actual_value->setTimezone( wp_timezone() );
			$actual_value = $actual_value->format( self::Y_M_D );
		}

		$actual_value   = date( self::Y_M_D, is_numeric( $actual_value ) ? $actual_value : strtotime( $actual_value ) );
		$expected_value = date( self::Y_M_D, is_numeric( $expected_value ) ? $expected_value : strtotime( $expected_value ) );

		switch ( $compare_type ) {
			case 'is_after':
				return $actual_value > $expected_value;

			case 'is_before':
				return $actual_value < $expected_value;

			case 'is_on':
				return $actual_value === $expected_value;

			case 'is_not_on':
				return $actual_value !== $expected_value;
			default:
				return false;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function get_fields(): array {
		return [
			( new SelectField() )
				->set_name( self::CONDITION_KEY )
				->set_options( $this->get_conditions() ),
			( new DatePickerField() )
				->set_name( self::VALUE_KEY ),
		];
	}

	/**
	 * @inheritDoc
	 * @return array{is_after: mixed, is_before: mixed, is_on: mixed, is_not_on: mixed}
	 */
	public function get_conditions(): array {
		$compare_types = [];

		$compare_types['is_after']  = __( 'Is after', 'shopmagic-for-woocommerce' );
		$compare_types['is_before'] = __( 'Is before', 'shopmagic-for-woocommerce' );
		$compare_types['is_on']     = __( 'Is on', 'shopmagic-for-woocommerce' );
		$compare_types['is_not_on'] = __( 'Is not on', 'shopmagic-for-woocommerce' );

		return $compare_types;
	}
}
