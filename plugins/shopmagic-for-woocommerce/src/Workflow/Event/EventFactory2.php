<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event;

use WPDesk\ShopMagic\Workflow\Components\Groups;

/**
 * @deprecated 3.0 Left only for compatibility with group constants. Moved to \WPDesk\ShopMagic\Extensions\Elements\Groups
 * @codeCoverageIgnore
 */
interface EventFactory2 {
	/**
	 * @var string
	 */
	public const GROUP_USERS = Groups::USER;

	/**
	 * @var string
	 */
	public const GROUP_CARTS = Groups::CART;

	/**
	 * @var string
	 */
	public const GROUP_ORDERS = Groups::ORDER;

	/**
	 * @var string
	 */
	public const GROUP_SUBSCRIPTION = Groups::SUBSCRIPTION;

	/**
	 * @var string
	 */
	public const GROUP_MEMBERSHIPS = Groups::MEMBERSHIP;

	/**
	 * @var string
	 */
	public const GROUP_PRO = Groups::PRO;

	/**
	 * @var string
	 */
	public const GROUP_FORMS = Groups::FORM;

	/**
	 * @var string
	 */
	public const GROUP_AUTOMATION = Groups::AUTOMATION;
}
