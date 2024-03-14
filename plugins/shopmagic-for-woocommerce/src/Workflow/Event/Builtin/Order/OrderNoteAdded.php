<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Order;

use WC_Order;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\NullCustomer;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareInterface;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareTrait;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WP_Comment;

final class OrderNoteAdded extends Event implements CustomerAwareInterface {
	use CustomerAwareTrait;

	/** @var int */
	private $is_customer_note = 0;

	public function get_id(): string {
		return 'shopmagic_order_note_added';
	}

	public function get_group_slug(): string {
		return Groups::ORDER;
	}

	public function get_name(): string {
		return __( 'Order Note Added', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when a note is added to an order. Covers both system and admin notes.', 'shopmagic-for-woocommerce' );
	}

	/**
	 * @return string[]
	 */
	public function get_provided_data_domains(): array {
		return array_merge(
			parent::get_provided_data_domains(),
			[ \WP_Comment::class, \WC_Order::class, Customer::class ]
		);
	}

	public function initialize(): void {
		add_filter(
			'woocommerce_new_order_note_data',
			function ( $data, array $args ) {
				return $this->catch_order_note_filter( $data, $args );
			},
			20,
			2
		);
		add_action(
			'wp_insert_comment',
			function ( int $comment_id, \WP_Comment $comment ) {
				$this->process_event( $comment_id, $comment );
			},
			20,
			2
		);
	}

	/**
	 * @param       $data
	 *
	 * @return mixed
	 * @internal
	 */
	public function catch_order_note_filter( $data, array $args ) {
		$this->is_customer_note = $args['is_customer_note'];
		return $data;
	}

	public function process_event( int $comment_id, \WP_Comment $comment ): void {
		$this->resources->set( \WP_Comment::class, $comment );
		if ( $comment->comment_type !== 'order_note' ) {
			return;
		}
		if ( get_post_type( (int) $comment->comment_post_ID ) !== 'shop_order' ) {
			return;
		}

		$order = $this->get_order();

		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		// Must manually set prop for OrderNoteType filter because meta field is added after the comment is inserted.
		if ( $this->is_customer_note === 1 ) {
			add_comment_meta( $comment_id, 'is_customer_note', 1 );
		}

		$this->resources->set( \WC_Order::class, $order );
		$this->resources->set( Customer::class, $this->get_customer( $order ) );

		$this->trigger_automation();
	}

	/**
	 * @return bool|\WC_Order|\WC_Order_Refund
	 */
	private function get_order() {
		return wc_get_order( $this->get_order_note()->comment_post_ID );
	}

	private function get_order_note(): \WP_Comment {
		return $this->resources->get( \WP_Comment::class );
	}

	/**
	 * @return array{order_note_id: string} Normalized event data required for Queue serialization.
	 */
	public function jsonSerialize(): array {
		return [
			'order_note_id' => $this->get_order_note()->comment_ID,
		];
	}

	/**
	 * @param array{order_note_id: numeric-string} $serialized_json
	 */
	public function set_from_json( array $serialized_json ): void {
		$this->resources->set( \WP_Comment::class, get_comment( $serialized_json['order_note_id'] ) );
		$order = $this->get_order();

		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		$this->resources->set( \WC_Order::class, $order );
		$this->resources->set( Customer::class, $this->get_customer( $order ) );
	}

	private function get_customer( \WC_Order $order ): Customer {
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
}
