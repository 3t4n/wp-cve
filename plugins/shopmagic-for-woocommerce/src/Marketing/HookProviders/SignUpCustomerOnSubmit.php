<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\HookProviders;

use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\CustomerSubscriberService;

/**
 * Conditionally sign up customer after order submission or user registration.
 * This covers both opt-in and opt-out types of lists.
 */
final class SignUpCustomerOnSubmit implements HookProvider {
	use HookTrait;

	/** @var AudienceListRepository */
	private $newsletter_repository;

	/** @var CustomerSubscriberService */
	private $subscriber_service;

	public function __construct(
		CustomerSubscriberService $subscriber_service,
		AudienceListRepository $newsletter_repository
	) {
		$this->subscriber_service    = $subscriber_service;
		$this->newsletter_repository = $newsletter_repository;
	}

	public function hooks(): void {
		$this->add_action(
			'woocommerce_checkout_order_processed',
			[ $this, 'save_checkout_optins' ],
			20,
			3
		);
		$this->add_action(
			'woocommerce_checkout_order_processed',
			[ $this, 'save_checkout_optouts' ],
			20,
			3
		);
		$this->add_action( 'user_register', [ $this, 'sign_up_user_to_opt_out_list' ] );
	}

	private function save_checkout_optins( $order_id ): void {
		if ( ! isset( $_POST['shopmagic_optin'] ) ) {
			return;
		}

		$order = wc_get_order( $order_id );

		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		foreach ( $this->newsletter_repository->find_checkout_viewable_items() as $type ) {
			if (
				isset( $_POST['shopmagic_optin'][ $type->get_id() ] ) &&
				$_POST['shopmagic_optin'][ $type->get_id() ] === 'yes'
			) {
				$this->subscriber_service->subscribe( $order->get_billing_email(), $type->get_id() );
			}
		}
	}

	private function save_checkout_optouts( $order_id ): void {
		$order = wc_get_order( $order_id );

		if ( ! $order instanceof \WC_Order ) {
			return;
		}

		foreach ( $this->newsletter_repository->find_opt_out_lists() as $type ) {
			$this->subscriber_service->subscribe( $order->get_billing_email(), $type->get_id() );
		}
	}

	private function sign_up_user_to_opt_out_list( int $user_id ): void {
		$user = get_user_by( 'id', $user_id );
		if ( ! $user instanceof \WP_User ) {
			return;
		}

		foreach ( $this->newsletter_repository->find_opt_out_lists() as $opt_out ) {
			$this->subscriber_service->subscribe( $user->user_email, $opt_out->get_id() );
		}
	}

}
