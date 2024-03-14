<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

use ShopMagicVendor\WPDesk\Forms\Field\SelectField;


final class SelectManyToOneType extends AbstractType {

	/** @var mixed[] */
	private $options = [];

	/**
	 * @param mixed[] $options
	 */
	public function __construct( array $options ) {
		$this->options = $options;
	}

	/**
	 * @param string|float $expected_value
	 * @param array        $actual_value As it's many to one, array of arrays is expected
	 */
	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		switch ( $compare_type ) {
			case 'all_are':
				foreach ( $actual_value as $value ) {
					if ( ! \in_array( $expected_value, $value, false ) ) {
						return false;
					}
				}

				return true;

			case 'none_is':
				foreach ( $actual_value as $value ) {
					if ( \in_array( $expected_value, $value, false ) ) {
						return false;
					}
				}

				return true;

			case 'any_is':
				foreach ( $actual_value as $value ) {
					if ( \in_array( $expected_value, $value, false ) ) {
						return true;
					}
				}

				return false;
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
			( new SelectField() )
				->set_multiple()
				->set_name( self::VALUE_KEY )
				->set_options( $this->options ),
		];
	}

	/**
	 * @inheritDoc
	 * @return array{any_is: mixed, all_are: mixed, none_is: mixed}
	 */
	public function get_conditions(): array {
		return [
			'any_is'  => __( 'any is', 'shopmagic-for-woocommerce' ),
			'all_are' => __( 'all are', 'shopmagic-for-woocommerce' ),
			'none_is' => __( 'none is', 'shopmagic-for-woocommerce' ),
		];
	}
}
