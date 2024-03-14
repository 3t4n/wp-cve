<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest\Interceptor;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectPersister;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Customer\Guest\GuestInOrderContextTrait;

/**
 * Catches guests from newly created orders and puts them into our guest repository.
 */
final class GuestOrderUpdate implements HookProvider {
	use GuestInOrderContextTrait;

	/** @var ObjectPersister<Guest> */
	private $persister;

	/** @var ObjectRepository<Guest> */
	private $repository;

	public function __construct( ObjectPersister $persister ) {
		$this->persister = $persister;
		$this->repository = $persister->get_repository();
	}

	public function hooks(): void {
		add_action(
			'woocommerce_before_order_object_save',
			[ $this, 'update_guest' ]
		);
	}

	/**
	 * @param \WC_Order $order
	*/
	public function update_guest( $order ): void {
		if ( ! array_key_exists( 'billing_email', $order->get_changes() ) ) {
			return;
		}

		$old_data = $order->get_data();
		$old_email = $old_data['billing']['email'];
		$new_email = $order->get_billing_email();
		try {
			$guest = $this->repository->find_one_by(
				[ 'email' => $old_email ]
			);
			$guest->set_email( $new_email );
			$this->persister->save( $guest );
			$order->update_meta_data( 'shopmagic_guest_id', $guest->get_raw_id() );
		} catch ( \Exception $e ) {
		}
	}
}
