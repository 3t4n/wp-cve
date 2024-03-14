<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Customer;

/**
 * Wrapper for \WP_User
 */
final class UserAsCustomer implements Customer {

	/** @var \WP_User */
	private $user;

	public function __construct( \WP_User $user ) {
		$this->user = $user;
	}

	public function is_guest(): bool {
		return false;
	}

	public function get_id(): string {
		return (string) $this->user->ID;
	}

	public function get_username(): string {
		return $this->user->user_login;
	}

	public function get_first_name(): string {
		return ( empty( $this->user->first_name ) ? '' : $this->user->first_name );
	}

	public function get_last_name(): string {
		return ( empty( $this->user->last_name ) ? '' : $this->user->last_name );
	}

	public function get_full_name(): string {
		return ( empty( $this->user->display_name ) ? '' : $this->user->display_name );
	}

	public function get_email(): string {
		return ( empty( $this->user->user_email ) ? '' : $this->user->user_email );
	}

	public function get_phone(): string {
		$phone = get_user_meta( $this->user->ID, 'billing_phone', true );

		return empty( $phone ) ? '' : $phone;
	}

	public function get_language(): string {
		return get_user_meta( $this->user->ID, Customer::USER_LANGUAGE_META, true ) ?: '';
	}
}
