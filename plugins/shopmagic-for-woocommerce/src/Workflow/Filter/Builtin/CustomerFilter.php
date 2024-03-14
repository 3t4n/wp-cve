<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\Builtin;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Filter\FilterUsingComparisonTypes;

abstract class CustomerFilter extends FilterUsingComparisonTypes {

	public function get_group_slug(): string {
		return Groups::CUSTOMER;
	}

	public function get_required_data_domains(): array {
		return [ Customer::class ];
	}

	protected function get_customer(): Customer {
		return $this->resources->get( Customer::class );
	}
}
