<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

use ShopMagicVendor\WPDesk\Forms\Field\InputTextField;
use ShopMagicVendor\WPDesk\Forms\Field\SelectField;


abstract class AbstractType implements ComparisonType {
	/**
	 * @var string
	 */
	public const VALUE_KEY = 'value';

	/**
	 * @var string
	 */
	public const CONDITION_KEY = 'condition';

	abstract public function passed( $expected_value, string $compare_type, $actual_value ): bool;

	public function get_fields(): array {
		return [
			( new SelectField() )
				->set_name( self::CONDITION_KEY )
				->set_options( $this->get_conditions() ),
			( new InputTextField() )
				->set_name( self::VALUE_KEY )
				->set_placeholder( __( 'value', 'shopmagic-for-woocommerce' ) ),
		];
	}
}
