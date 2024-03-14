<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Customer;

/**
 * Wrapper for \WP_User with fallback to \WC_Order data.
 */
final class UserInOrderContextAsCustomer implements Customer {

	/** @var \WP_User */
	private $user;

	/** @var \WC_Order */
	private $order;

	/** @var \WPDesk\ShopMagic\Customer\UserAsCustomer */
	private $user_customer;

	public function __construct( \WP_User $user, \WC_Order $order ) {
		$this->user          = $user;
		$this->order         = $order;
		$this->user_customer = new UserAsCustomer( $this->user );
	}

	public function is_guest(): bool {
		return false;
	}

	public function get_id(): string {
		return $this->user_customer->get_id();
	}

	public function get_username(): string {
		return $this->user_customer->get_username();
	}

	public function get_first_name(): string {
		$fallback = (string) $this->order->get_billing_first_name();
		$value    = $this->user_customer->get_first_name();

		return ( empty( $value ) ? $fallback : $value );
	}

	public function get_last_name(): string {
		$fallback = (string) $this->order->get_billing_last_name();
		$value    = $this->user_customer->get_last_name();

		return ( empty( $value ) ? $fallback : $value );
	}

	public function get_full_name(): string {
		$fallback = $this->order->get_billing_first_name() . ' ' . $this->order->get_billing_last_name();
		$value    = $this->user_customer->get_full_name();

		return ( empty( $value ) ? $fallback : $value );
	}

	public function get_email(): string {
		$fallback = (string) $this->order->get_billing_email();
		$value    = $this->user_customer->get_email();

		return ( empty( $value ) ? $fallback : $value );
	}

	public function get_phone(): string {
		$fallback = $this->order->get_billing_phone();
		$value    = $this->user_customer->get_phone();

		return ( empty( $value ) ? $fallback : $value );
	}

	public function get_language(): string {
		return get_user_meta( $this->user->ID, Customer::USER_LANGUAGE_META, true ) ?: '';
	}
}
