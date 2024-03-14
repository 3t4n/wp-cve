<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Order;

use WC_Order;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Event\Builtin\OrderCommonEvent;
use WPDesk\ShopMagic\Workflow\Event\EventMutex;

final class OrderNew extends OrderCommonEvent {
	use HookTrait;

	/** @var EventMutex */
	private $event_mutex;

	public function __construct( EventMutex $event_mutex ) {
		$this->event_mutex = $event_mutex;
	}

	public function get_id(): string {
		return 'shopmagic_order_new_event';
	}

	public function get_name(): string {
		return __( 'New Order', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when any new order is created. Use this event if the status of the processed order does not matter.', 'shopmagic-for-woocommerce' );
	}

	public function initialize(): void {
		$this->add_action(
			'woocommerce_new_order',
			[ $this, 'process_event' ],
			self::PRIORITY_AFTER_DEFAULT,
			2
		);
		$this->add_action(
			'woocommerce_api_create_order',
			[ $this, 'process_event' ],
			self::PRIORITY_AFTER_DEFAULT,
			2
		);
	}

	public function shutdown(): void {
		$this->remove_action(
			'woocommerce_new_order',
			[ $this, 'process_event' ],
			self::PRIORITY_AFTER_DEFAULT,
			2
		);
		$this->remove_action(
			'woocommerce_api_create_order',
			[ $this, 'process_event' ],
			self::PRIORITY_AFTER_DEFAULT,
			2
		);
	}

	public function process_event( $order_id, $order ): void {
		if ( $this->event_mutex->check_uniqueness_once( spl_object_hash( $this ), [ 'order_id' => $order_id ] ) ) {
			$this->resources->set( WC_Order::class, $order );
			$this->resources->set( Customer::class, $this->get_customer( $order ) );

			$this->trigger_automation();
		}
	}
}
