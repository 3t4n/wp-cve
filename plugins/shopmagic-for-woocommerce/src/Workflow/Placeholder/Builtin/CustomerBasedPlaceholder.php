<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Placeholder\Builtin;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;

abstract class CustomerBasedPlaceholder extends Placeholder {

	public function get_required_data_domains(): array {
		return [ Customer::class ];
	}

}
