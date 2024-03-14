<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\NullCustomer;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Exception\ReferenceNoLongerAvailableException;
use WPDesk\ShopMagic\Workflow\Components\Groups;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareInterface;
use WPDesk\ShopMagic\Workflow\Event\CustomerAwareTrait;
use WPDesk\ShopMagic\Workflow\Event\Event;

abstract class UserCommonEvent extends Event implements CustomerAwareInterface {
	use CustomerAwareTrait;

	/** @var int */
	protected $user_id;
	/** @var Customer */
	protected $customer;

	public function get_group_slug(): string {
		return Groups::CUSTOMER;
	}

	public function jsonSerialize(): array {
		return [
			'customer_id'    => $this->resources->get( Customer::class )->get_id(),
			'guest'          => $this->resources->get( Customer::class )->is_guest(),
			'customer_email' => $this->resources->get( Customer::class )->get_email(),
		];
	}

	public function set_from_json( array $serialized_json ): void {
		$user_id = $serialized_json['user_id'] ?? null;
		if ( ! empty( $user_id ) ) {
			try {
				$this->resources->set( Customer::class, $this->customer_repository->find( $user_id ) );
				return;
			} catch ( CustomerNotFound $e ) {
				throw new ReferenceNoLongerAvailableException( esc_html__( sprintf( 'User %s no longer exists.',
					$serialized_json['user_id'] ),
					'shopmagic-for-woocommerce' ) );
			}
		}

		$customer_id = $serialized_json['customer_id'];
		if ( ! empty( $customer_id ) ) {
			$this->resources->set( Customer::class, $this->customer_repository->find( $customer_id ) );
		} else { // For saved events without customer data.
			$this->resources->set( Customer::class, new NullCustomer() );
		}
	}

	public function get_provided_data_domains(): array {
		return array_merge(
			parent::get_provided_data_domains(),
			[ Customer::class ]
		);
	}

	protected function get_customer(): Customer {
		return $this->resources->get( Customer::class );
	}
}
