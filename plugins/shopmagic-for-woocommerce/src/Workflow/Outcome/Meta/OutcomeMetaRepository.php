<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Outcome\Meta;

use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectRepository;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * @extends ObjectRepository<OutcomeMeta>
 */
class OutcomeMetaRepository extends \WPDesk\ShopMagic\Components\Database\Abstraction\ObjectRepository {

	protected function get_name(): string {
		return DatabaseTable::outcome_logs();
	}
}
