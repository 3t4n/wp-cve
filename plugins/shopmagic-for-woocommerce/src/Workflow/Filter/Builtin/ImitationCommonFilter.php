<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Filter\Builtin;

use WPDesk\ShopMagic\Workflow\Filter\Filter;

/**
 * Base class for filters showing info about PRO upgrade.
 */
abstract class ImitationCommonFilter extends Filter {

	public function passed(): bool {
		return false;
	}
}
