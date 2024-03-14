<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin\Customer;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\UserAsCustomer;
use WPDesk\ShopMagic\Workflow\Event\Builtin\UserCommonEvent;

/**
 * Trigger when new user is created in WordPress.
 */
final class CustomerAccountCreated extends UserCommonEvent {
	public function get_id(): string {
		return 'shopmagic_new_account_event';
	}

	public function get_name(): string {
		return __( 'Customer Account Created', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return __( 'Run automation when a new customer account is created in WordPress.', 'shopmagic-for-woocommerce' );
	}

	public function initialize(): void {
		add_action(
			'user_register',
			function ( int $user_id ): void {
				$this->process_event( $user_id );
			}
		);
	}

	public function process_event( int $user_id ): void {
		$this->resources->set( Customer::class, new UserAsCustomer( new \WP_User( $user_id ) ) );
		$this->trigger_automation();
	}
}
