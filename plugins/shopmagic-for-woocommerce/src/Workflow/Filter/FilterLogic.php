<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Filter;

use WPDesk\ShopMagic\DataSharing\DataReceiver;

interface FilterLogic extends DataReceiver {
	/**
	 * Checks if filter allows event to be executed.
	 *
	 * @return bool True if event can be executed.
	 */
	public function passed(): bool;
}
