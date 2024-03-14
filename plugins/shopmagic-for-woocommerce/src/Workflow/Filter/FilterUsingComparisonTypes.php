<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Filter;

use WPDesk\ShopMagic\DataSharing\Traits\StandardWooCommerceDataProviderAccessors;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\ComparisonType;

abstract class FilterUsingComparisonTypes extends Filter {
	use StandardWooCommerceDataProviderAccessors;

	/**
	 * Using this method the filter can declare what comparison types are available.
	 * i.e. for integer numbers and date stamps there will be different possibilities.
	 */
	abstract protected function get_type(): ComparisonType;

	final public function get_fields(): array {
		return $this->get_type()->get_fields();
	}
}
