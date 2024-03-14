<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin;

use WC_Order;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\NullCustomer;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Exception\ReferenceNoLongerAvailableException;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareInterface;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareTrait;
use WPDesk\ShopMagic\Workflow\Event\Event;

abstract class OrderCommonEvent extends Event implements CustomerAwareInterface {
	use CustomerAwareTrait;

	/** @var int */
	public const PRIORITY_AFTER_DEFAULT = 100;
	/** @var string */
	private const ORDER_ID = 'order_id';

	/** @var \WC_Order|\WC_Order_Refund */
	protected $order;

	public function get_group_slug(): string {
		return Groups::ORDER;
	}

	public function get_provided_data_domains(): array {
		return array_merge(
			parent::get_provided_data_domains(),
			[ \WC_Order::class, Customer::class ]
		);
	}

	/**
	 * @param mixed                                         $_
	 * @param \WC_Order|\WC_Order_Refund|\WC_Abstract_Order $order
	 *
	 * @internal
	 */
	protected function process_event( $_, $order ): void {
		if ( $order instanceof WC_Order ) {
			$this->resources->set( WC_Order::class, $order );
			$this->resources->set( Customer::class, $this->get_customer( $order ) );
		} elseif ( $order instanceof \WC_Order_Refund ) {
			$this->resources->set( \WC_Order_Refund::class, $order );
		}

		$this->trigger_automation();
	}

	protected function get_customer( \WC_Order $order ): Customer {
		try {
			return $this->customer_repository->find_by_email( $order->get_billing_email() );
		} catch ( CustomerNotFound $e ) {
			try {
				$user = $order->get_user();
				if ( $user instanceof \WP_User ) {
					return $this->customer_repository->find_by_email( $user->user_email );
				}
			} catch ( CustomerNotFound $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
				// Fallthrough if not found.
			}
		}
		$this->logger->warning(
			'There is no customer associated with order #{id}.',
			[ 'id' => $order->get_id() ]
		);
		return new NullCustomer();
	}

	protected function get_order(): WC_Order {
		return $this->resources->get( WC_Order::class );
	}

	/**
	 * @return array{order_id: numeric-string|int, customer_id: string} Normalized event data required for Queue serialization.
	 */
	public function jsonSerialize(): array {
		return [
			self::ORDER_ID => $this->get_order()->get_id(),
			'customer_id'  => $this->resources->get( Customer::class )->get_id(),
		];
	}

	/**
	 * @param array{order_id: numeric-string, customer_id: string} $serialized_json
	 *
	 * @throws ReferenceNoLongerAvailableException When serialized object reference is no longer valid. i.e. order no
	 *                                             longer exists.
	 */
	public function set_from_json( array $serialized_json ): void {
		try {
			$order = wc_get_order( $serialized_json[ self::ORDER_ID ] );
			if ( $order instanceof \WC_Order ) {
				$this->resources->set( \WC_Order::class, $order );
				$this->resources->set( Customer::class, $this->get_customer( $order ) );
			} elseif ( $order instanceof \WC_Order_Refund ) {
				$this->resources->set( \WC_Order_Refund::class, $order );
			}
		} catch ( \InvalidArgumentException $e ) {
			throw new ReferenceNoLongerAvailableException(
				sprintf(
					// translators: %d: ID of an order.
					esc_html__(
						'Order #%d no longer exists.',
						'shopmagic-for-woocommerce'
					),
					$serialized_json[ self::ORDER_ID ]
				)
			);
		}
	}
}
