<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

use ShopMagicVendor\WPDesk\Forms\Field\SelectField;


final class SelectOneToOneType extends AbstractType {

	/** @var mixed[] */
	private $options = [];
	/**
	 * @var string
	 */
	private const IS = 'is';

	/**
	 * @param mixed[] $options
	 */
	public function __construct( array $options ) {
		$this->options = $options;
	}

	/**
	 * @inheritDoc
	 */
	public function passed( $expected_value, string $compare_type, $actual_value ): bool {
		if ( $compare_type === self::IS ) {
			return $expected_value === $actual_value;
		}

		return $expected_value !== $actual_value;
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
				->set_name( self::VALUE_KEY )
				->set_options( $this->options ),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_conditions(): array {
		return [
			self::IS => __( self::IS, 'shopmagic-for-woocommerce' ),
			'is_not' => __( 'is not', 'shopmagic-for-woocommerce' ),
		];
	}
}
