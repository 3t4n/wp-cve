<?php

namespace WPDesk\ShopMagic\Workflow\Extensions\Builtin;

use WPDesk\ShopMagic\Workflow\Extensions\Extension;

final class StagingExtension implements Extension {

	public function get_actions(): array {
		return apply_filters( 'shopmagic/core/actions', [] );
	}

	public function get_filters(): array {
		return apply_filters( 'shopmagic/core/filters', [] );
	}

	public function get_events(): array {
		return apply_filters( 'shopmagic/core/events', [] );
	}

	public function get_placeholders(): array {
		return apply_filters( 'shopmagic/core/placeholders', [] );
	}
}
