<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectHydrator;
use WPDesk\ShopMagic\Components\Mailer\Email;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Customer\NullCustomer;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;
use WPDesk\ShopMagic\Workflow\Automation\Automation;

/**
 * @implements ObjectHydrator<TrackedEmail>
 * @implements ObjectDehydrator<TrackedEmail>
 */
class TrackedEmailHydrator implements ObjectHydrator, ObjectDehydrator {

	/** @var CustomerRepository */
	private $customer_repository;

	public function __construct( CustomerRepository $customer_repository ) {
		$this->customer_repository = $customer_repository;
	}

	public function fresh( Email $email, Automation $automation, ?Customer $customer = null ): TrackedEmail {
		$tracked_email = new TrackedEmail();
		$tracked_email->set_recipient_email( $email->to );
		$tracked_email->set_automation_id( $automation->get_id() );
		$tracked_email = $tracked_email
			->with_fresh_uuid()
			->mark_dispatched();

		$tracked_email->set_customer( $customer );

		return $tracked_email;
	}

	public function new_click( TrackedEmail $tracked_email, string $uri ): TrackedEmailClick {
		$click = new TrackedEmailClick();
		$click->set_tracked_email( $tracked_email );
		$click->mark_click( $uri );

		return $click;
	}

	public function denormalize( array $payload ): object {
		$tracked_email = new TrackedEmail();
		$tracked_email->set_id( isset( $payload['id'] ) ? (int) $payload['id'] : null );
		$tracked_email->set_message_id( $payload['message_id'] );
		$tracked_email->set_recipient_email( $payload['recipient_email'] );
		$tracked_email->set_automation_id( (int) $payload['automation_id'] );
		$tracked_email->set_dispatched_at( new \DateTime( $payload['dispatched_at'] ) );

		try {
			if ( isset( $payload['customer_id'] ) ) {
				$customer = $this->customer_repository->find( $payload['customer_id'] );
			} else {
				$customer = new NullCustomer();
			}
		} catch ( CustomerNotFound $e ) {
			$customer = new NullCustomer();
		}

		$tracked_email->set_customer( $customer );

		if ( $payload['clicked_at'] ) {
			$tracked_email->set_clicked_at( new \DateTime( $payload['clicked_at'] ) );
		}

		if ( $payload['opened_at'] ) {
			$tracked_email->set_opened_at( new \DateTime( $payload['opened_at'] ) );
		}

		return $tracked_email;
	}

	public function supports_denormalization( array $data ): bool {
		$required_keys = [ 'id', 'message_id', 'recipient_email', 'automation_id', 'dispatched_at' ];

		return count( array_intersect( array_keys( $data ), $required_keys ) ) === count( $required_keys );
	}

	/** @param TrackedEmail $object */
	public function normalize( object $object ): array {
		$customer_id = null;
		$customer    = $object->get_customer();
		if ( $customer instanceof Customer && ! $customer->is_guest() ) {
			$customer_id = $customer->get_id();
		}
		return [
			'id'              => $object->get_id(),
			'message_id'      => $object->get_message_id(),
			'automation_id'   => $object->get_automation_id(),
			'customer_id'     => $customer_id,
			'recipient_email' => $object->get_recipient_email(),
			'dispatched_at'   => $object->get_dispatched_at()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
			'opened_at'       => $object->get_opened_at() ? $object->get_opened_at()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ) : null,
			'clicked_at'      => $object->get_clicked_at() ? $object->get_clicked_at()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ) : null,
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof TrackedEmail;
	}
}
