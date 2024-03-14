<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Outcome\Meta;

use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * Representation of Outcome Logs table.
 */
final class OutcomeMetaManager extends ObjectManager {

	protected function get_name(): string {
		return DatabaseTable::outcome_logs();
	}

	protected function get_columns(): array {
		return [
			'id',
			'execution_id',
			'note',
			'created',
			'note_context',
		];
	}
}
