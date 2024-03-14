<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

use ShopMagicVendor\WPDesk\Forms\Field\SelectField;


class SelectManyToManyType extends AbstractType {
	/**
	 * @var string
	 */
	public const VALUE_KEY = 'value';

	/** @var mixed[] */
	protected $options = [];

	/**
	 * @param mixed[] $options
	 */
	public function __construct( array $options = [] ) {
		$this->options = $options;
	}

	public function get_fields(): array {
		return [
			( new SelectField() )
				->set_name( AbstractType::CONDITION_KEY )
				->set_options( $this->get_conditions() ),
			$this->get_select_field(),
		];
	}

	/**
	 * @return array{matches_any: mixed, matches_all: mixed, matches_none: mixed}
	 */
	public function get_conditions(): array {
		return [
			'matches_any'  => __( 'matches any', 'shopmagic-for-woocommerce' ),
			'matches_all'  => __( 'matches all', 'shopmagic-for-woocommerce' ),
			'matches_none' => __( 'matches none', 'shopmagic-for-woocommerce' ),
		];
	}

	protected function get_select_field(): SelectField {
		return ( new SelectField() )
			->set_multiple()
			->set_options( $this->options )
			->set_name( self::VALUE_KEY );
	}

	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		if ( $compare_type === 'matches_none' ) {
			return array_intersect( $expected_value, $actual_value ) === [];
		}

		if ( $compare_type === 'matches_all' ) {
			return \count( array_intersect( $expected_value, $actual_value ) ) === ( \is_array( $expected_value ) || $expected_value instanceof \Countable ? \count( $expected_value ) : 0 );
		}

		return array_intersect( $expected_value, $actual_value ) !== [];
	}
}
