<?php

namespace WPDesk\ShopMagic\Workflow\Outcome;

use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

interface OutcomeSaver {
	public function update_result( string $unique_id, bool $result, string $note = null, array $context = [] ): void;

	public function create_outcome( Automation $automation, DataLayer $data_layer, int $action_index );

	public function find_outcome( string $unique_id ): ?Outcome;
}
