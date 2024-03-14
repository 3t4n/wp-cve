<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Outcome;

use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectHydrator;
use WPDesk\ShopMagic\Customer\CustomerFactory;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Customer\NullCustomer;
use WPDesk\ShopMagic\Exception\CustomerNotFound;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;

/**
 * @implements ObjectHydrator<Outcome>
 * @implements ObjectDehydrator<Outcome>
 * Normalizer and denormalizer in database context.
 */
class OutcomeHydrator implements ObjectHydrator, ObjectDehydrator {
	private const CUSTOMER_ID = 'customer_id';
	private const GUEST_ID    = 'guest_id';

	/** @var CustomerRepository */
	private $customer_repository;

	public function __construct(
		CustomerRepository $customer_repository
	) {
		$this->customer_repository = $customer_repository;
	}

	public function denormalize( array $payload ): object {
		try {
			if ( isset( $payload[ self::CUSTOMER_ID ] ) ) {
				$customer_id = $payload[ self::CUSTOMER_ID ];
			} elseif ( isset( $payload[ self::GUEST_ID ] ) ) {
				$customer_id = CustomerFactory::id_to_guest_id( (int) $payload[ self::GUEST_ID ] );
			} else {
				$customer_id = null;
			}

			if ( is_null( $customer_id ) ) {
				$customer = new NullCustomer();
			} else {
				$customer = $this->customer_repository->find( $customer_id );
			}
		} catch ( CustomerNotFound $e ) {
			$customer = new NullCustomer();
		}
		$outcome = new Outcome();
		$outcome->set_id( isset( $payload['id'] ) ? (int) $payload['id'] : null );
		$outcome->set_execution_id( $payload['execution_id'] );
		$outcome->set_automation_id( (int) $payload['automation_id'] );
		$outcome->set_automation_name( $payload['automation_name'] );
		$outcome->set_action_index( (int) $payload['action_index'] );
		$outcome->set_action_name( $payload['action_name'] );
		$outcome->set_customer( $customer );
		$outcome->set_success( $payload['success'] === '1' );
		$outcome->set_finished( $payload['finished'] === '1' );
		$outcome->set_created( new \DateTimeImmutable( $payload['created'] ?? 'now' ) );
		$outcome->set_updated( new \DateTimeImmutable( $payload['updated'] ?? 'now' ) );

		return $outcome;
	}

	public function supports_denormalization( array $data ): bool {
		return true;
	}

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( Outcome::class, $object );
		}

		$database_fields = [
			'id'              => $object->get_id(),
			'execution_id'    => $object->get_execution_id(),
			'automation_id'   => $object->get_automation_id(),
			'automation_name' => $object->get_automation_name(),
			'action_index'    => $object->get_action_index(),
			'action_name'     => $object->get_action_name(),
			'customer_email'  => $object->get_customer_email(),
			'success'         => $object->get_success(),
			'finished'        => $object->is_finished(),
			'created'         => $object->get_created()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
			'updated'         => $object->get_updated()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
		];

		$customer_is_guest = $object->get_customer()->is_guest();
		$customer_id       = $object->get_customer()->get_id();
		if ( $customer_is_guest ) {
			$database_fields['guest_id'] = CustomerFactory::convert_customer_guest_id_to_number( $customer_id );
		} else {
			$database_fields['customer_id'] = $customer_id;
		}

		return $database_fields;
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof Outcome;
	}
}
