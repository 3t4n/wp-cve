<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Extensions;

use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Filter\Filter;
use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;

interface Extension {
	/** @return array<string|int, class-string<Action>|Action> */
	public function get_actions(): array;

	/** @return array<string|int, class-string<Filter>|Filter> */
	public function get_filters(): array;

	/** @return array<class-string<Placeholder>|Placeholder> */
	public function get_placeholders(): array;

	/** @return array<string|int, class-string<Event>|Event> */
	public function get_events(): array;
}
