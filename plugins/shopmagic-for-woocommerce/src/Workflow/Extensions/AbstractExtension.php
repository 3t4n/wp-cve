<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Extensions;

abstract class AbstractExtension implements Extension {

	public function get_actions(): array {
		return [];
	}

	public function get_filters(): array {
		return [];
	}

	public function get_placeholders(): array {
		return [];
	}

	public function get_events(): array {
		return [];
	}

}
