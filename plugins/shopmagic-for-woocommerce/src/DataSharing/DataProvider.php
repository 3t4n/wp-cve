<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\DataSharing;

use WPDesk\ShopMagic\Workflow\Event\DataLayer;

interface DataProvider {
	/**
	 * List of classes that a provider can provide.
	 *
	 * @return class-string[]
	 */
	public function get_provided_data_domains(): array;

	/**
	 * Object instances promised in get_provided_data_domains.
	 */
	public function get_provided_data(): DataLayer;
}
