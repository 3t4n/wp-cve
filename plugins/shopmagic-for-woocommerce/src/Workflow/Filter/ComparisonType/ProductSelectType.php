<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Filter\ComparisonType;

use ShopMagicVendor\WPDesk\Forms\Field\ProductSelect;
use ShopMagicVendor\WPDesk\Forms\Field\SelectField;


final class ProductSelectType extends SelectManyToManyType {
	/**
	 * @var string
	 */
	public const VALUE_KEY = 'products_ids';

	protected function get_select_field(): SelectField {
		return ( new class extends ProductSelect {
			public function has_serializer(): bool {
				return false;
			}
		} )
			->set_name( self::VALUE_KEY )
			->set_placeholder( __( 'Search for a product...', 'shopmagic-for-woocommerce' ) );
	}
}
