<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Extensions\Builtin;

use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Imitation\CartAdEvent;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Imitation\ManualTriggerProEvent;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Imitation\MembershipsProEvent;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Imitation\SubscriptionProEvent;
use WPDesk\ShopMagic\Workflow\Extensions\AbstractExtension;
use WPDesk\ShopMagic\Workflow\Filter\Builtin\Imitation\OrderItemCategoryProEvent;

final class PaidPromotionExtension extends AbstractExtension {

	/** @var bool */
	private $is_pro_active;

	public function __construct( bool $is_pro_active = false ) {
		$this->is_pro_active = $is_pro_active;
	}

	public function get_events(): array {
		$events = [];

		if ( ! $this->is_pro_active && WordPressPluggableHelper::is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
			$events['subscription_status_changed'] = new SubscriptionProEvent();
		}

		if ( ! $this->is_pro_active && WordPressPluggableHelper::is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
			$events['membership_status_changed'] = new MembershipsProEvent();
		}

		if ( ! WordPressPluggableHelper::is_plugin_active( 'shopmagic-abandoned-carts/shopmagic-abandoned-carts.php' ) ) {
			$events['cart_abandoned_event'] = new CartAdEvent();
		}

		if ( ! $this->is_pro_active && ! WordPressPluggableHelper::is_plugin_active( 'shopmagic-manual-actions/shopmagic-manual-actions.php' ) ) {
			$events['shopmagic_order_manual_trigger'] = new ManualTriggerProEvent();
		}

		return $events;
	}

	/**
	 * @return mixed[]
	 */
	public function get_filters(): array {
		$filters = [];

		if ( ! $this->is_pro_active && ! WordPressPluggableHelper::is_plugin_active( 'shopmagic-advanced-filters/shopmagic-advanced-filters.php' ) ) {
			$filters['order_item_category'] = new OrderItemCategoryProEvent();
		}

		return $filters;
	}
}
