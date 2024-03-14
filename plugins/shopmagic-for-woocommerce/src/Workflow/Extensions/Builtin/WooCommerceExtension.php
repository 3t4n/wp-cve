<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Extensions\Builtin;

use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderCancelled;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderCompleted;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderFailed;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderNew;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderNoteAdded;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderOnHold;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderPaid;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderPending;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderProcessing;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderRefunded;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderStatusChanged;
use WPDesk\ShopMagic\Workflow\Event\DeferredStateCheck\OrderStatusDeferredEvent;
use WPDesk\ShopMagic\Workflow\Event\EventMutex;
use WPDesk\ShopMagic\Workflow\Extensions\AbstractExtension;
use WPDesk\ShopMagic\Workflow\Filter\Builtin\Order\OrderItems;
use WPDesk\ShopMagic\Workflow\Filter\Builtin\Order\OrderNoteContent;
use WPDesk\ShopMagic\Workflow\Filter\Builtin\Order\OrderNoteType;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Order;
use WPDesk\ShopMagic\Workflow\Placeholder\Builtin\Product;
use WPDesk\ShopMagic\Workflow\Placeholder\Helper\PlaceholderUTMBuilder;
use WPDesk\ShopMagic\Workflow\Placeholder\TemplateRendererForPlaceholders;

final class WooCommerceExtension extends AbstractExtension {

	/** @var EventMutex */
	private $event_mutex;

	/** @var TemplateRendererForPlaceholders */
	private $renderer;

	public function __construct( EventMutex $event_mutex, TemplateRendererForPlaceholders $renderer ) {
		$this->event_mutex = $event_mutex;
		$this->renderer    = $renderer;
	}

	public function get_events(): array {
		return [
			OrderNew::class,
			new OrderStatusDeferredEvent( new OrderPending( $this->event_mutex ), 'pending' ),
			new OrderStatusDeferredEvent( new OrderProcessing(), 'processing' ),
			new OrderStatusDeferredEvent( new OrderCancelled(), 'cancelled' ),
			new OrderStatusDeferredEvent( new OrderCompleted(), 'completed' ),
			new OrderStatusDeferredEvent( new OrderFailed(), 'failed' ),
			new OrderStatusDeferredEvent( new OrderOnHold(), 'on-hold' ),
			new OrderStatusDeferredEvent( new OrderRefunded(), 'refunded' ),
			OrderStatusChanged::class,
			OrderPaid::class,
			OrderNoteAdded::class,
		];
	}

	public function get_filters(): array {
		return [
			OrderItems::class,
			OrderNoteType::class,
			OrderNoteContent::class,
		];
	}

	public function get_placeholders(): array {
		$utm_builder = new PlaceholderUTMBuilder();

		return [
			Order\OrderBillingAddress2::class,
			Order\OrderBillingAddress::class,
			Order\OrderBillingCity::class,
			Order\OrderBillingCompany::class,
			Order\OrderBillingCountry::class,
			Order\OrderBillingEmail::class,
			Order\OrderBillingFirstName::class,
			Order\OrderBillingFormattedAddress::class,
			Order\OrderBillingLastName::class,
			Order\OrderBillingPhone::class,
			Order\OrderBillingPostCode::class,
			Order\OrderBillingState::class,
			Order\OrderCustomerId::class,

			Order\OrderShippingAddress2::class,
			Order\OrderShippingAddress::class,
			Order\OrderShippingCity::class,
			Order\OrderShippingCompany::class,
			Order\OrderShippingCountry::class,
			Order\OrderShippingFirstName::class,
			Order\OrderShippingFormattedAddress::class,
			Order\OrderShippingLastName::class,
			Order\OrderShippingPhone::class,
			Order\OrderShippingMethod::class,
			Order\OrderShippingPostCode::class,
			Order\OrderShippingState::class,

			Order\OrderCustomerNote::class,

			new Order\OrderCrossSells( $this->renderer, $utm_builder ),
			new Order\OrderProductsOrdered( $this->renderer, $utm_builder ),
			new Order\OrderRelatedProducts( $this->renderer, $utm_builder ),
			Order\OrderAdminUrl::class,
			Order\OrderDateCompleted::class,
			Order\OrderDateCreated::class,
			Order\OrderDatePaid::class,
			Order\OrderDetails::class,
			Order\OrderDownloads::class,
			Order\OrderId::class,
			Order\OrderMeta::class,
			Order\OrderNumber::class,
			Order\OrderPaymentMethod::class,
			Order\OrderPaymentUrl::class,
			Order\OrderProductsSku::class,
			Order\OrderTotal::class,

			Order\OrderNoteAuthor::class,
			Order\OrderNoteContent::class,

			Product\ProductId::class,
			Product\ProductLink::class,
			Product\ProductMeta::class,
			Product\ProductName::class,
		];
	}
}
