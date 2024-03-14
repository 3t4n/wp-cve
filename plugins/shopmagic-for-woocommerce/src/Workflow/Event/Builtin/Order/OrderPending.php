<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Order;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Event\Builtin\OrderCommonEvent;
use WPDesk\ShopMagic\Workflow\Event\EventMutex;
use WPDesk\ShopMagic\Workflow\Queue\Queue;


final class OrderPending extends OrderCommonEvent {
	/** @var string */
	private const DEFERRED_CHECK_QUEUE_HOOK = 'shopmagic/core/queue/pending-deferred-check';

	/** @var string */
	private const DEFERRED_RUN_HOOK = 'shopmagic/core/event/order_pending/deferred_run';

	/** @var string */
	private const QUEUE_GROUP_NAME = 'shopmagic-automation-internal';
	/** @var string */
	private const STATUS_TO_CHECK = 'pending';

	/** @var Queue */
	private static $queue_client;

	/** @var EventMutex */
	private $event_mutex;

	public function __construct( EventMutex $event_mutex ) {
		$this->event_mutex = $event_mutex;
	}

	public function get_id(): string {
		return 'shopmagic_order_pending_event';
	}

	/**
	 * Check if newly created order still has pending status.
	 * Have to be executed as soon as possible.
	 */
	public static function initialize_pending_on_created_check( Queue $queue ): void {
		self::$queue_client = $queue;

		// and if status was not changed during checkout run event.
		add_action(
			self::DEFERRED_CHECK_QUEUE_HOOK,
			static function ( $order_id, $status_to_check ): void {
				$run_event = static function () use ( $order_id, $status_to_check ): void {
					$order = wc_get_order( $order_id );
					if ( $status_to_check === $order->get_status() ) {
						do_action( self::DEFERRED_RUN_HOOK, $order_id, $order );
					}
				};

				if ( did_action( 'wp_loaded' ) ) {
					$run_event();
				} else {
					add_action( 'wp_loaded', $run_event );
				}
			},
			self::PRIORITY_AFTER_DEFAULT,
			2
		);
	}

	public function get_name(): string {
		return __( 'Order Pending', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when an order is pending payment.', 'shopmagic-for-woocommerce' );
	}

	public function initialize(): void {
		add_action(
			'woocommerce_order_status_pending',
			function ( $order_id, $order ) {
				$this->process_event( $order_id, $order );
			},
			10,
			2
		);
		add_action(
			self::DEFERRED_RUN_HOOK,
			function ( $order_id, $order ) {
				$this->process_event( $order_id, $order );
			},
			10,
			2
		);

		// check if order is created with a given status and this status is not immediately changed.
		add_action(
			'woocommerce_new_order',
			function ( $order_id, $order = null ): void {
				if ( ! $order instanceof \WC_Abstract_Order ) {
					$order = wc_get_order( $order_id );
				}

				if ( $order instanceof \WC_Abstract_Order ) {
					$is_pending_status = $order->get_status() === self::STATUS_TO_CHECK;
					// if status is pending add to queue to check later.
					if ( ! $is_pending_status ) {
						return;
					}
					if ( ! self::$queue_client instanceof Queue ) {
						return;
					}
					if ( ! $this->event_mutex->check_uniqueness_once( self::class, [ 'order_id' => $order_id ] ) ) {
						return;
					}
					self::$queue_client->add(
						self::DEFERRED_CHECK_QUEUE_HOOK,
						[
							$order_id,
							self::STATUS_TO_CHECK,
						],
						self::QUEUE_GROUP_NAME
					);
				}
			},
			self::PRIORITY_AFTER_DEFAULT,
			2
		);
	}

	public function process_event( $order_id, $order ): void {
		if ( $this->event_mutex->check_uniqueness_once( spl_object_hash( $this ), [ 'order_id' => $order_id ] ) ) {
			$this->resources->set( \WC_Order::class, $order );
			$this->resources->set( Customer::class, $this->get_customer( $order ) );

			$this->trigger_automation();
		}
	}
}
