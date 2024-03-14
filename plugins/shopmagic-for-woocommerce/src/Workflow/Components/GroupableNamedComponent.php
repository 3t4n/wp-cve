<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Components;

/**
 * Some UI elements may be grouped.
 */
interface GroupableNamedComponent extends NamedComponent {

	/**
	 * Use common name for elements sharing the group.
	 */
	public function get_group_slug(): string;
}
