<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\DataSharing;

use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * Object can announce that it requires some data with ::get_required_data_domains.
 * Object will get these data from ::set_provided_data.
 *
 * Object may require some data to function properly.
 */
interface DataReceiver {
	/**
	 * This object required instances of these classes.
	 *
	 * @return class-string[] Set of class names that object requires.
	 */
	public function get_required_data_domains(): array;

	/**
	 * Set data corresponding go ::get_required_data_domains.
	 *
	 * @return void
	 */
	public function set_provided_data( DataLayer $resources ): void;
}
