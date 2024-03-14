<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectHydrator;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriber;

/**
 * @implements ObjectDehydrator<SingleListSubscriber>
 * @implements ObjectHydrator<SingleListSubscriber>
 */
class SubscriberHydrator implements ObjectHydrator, ObjectDehydrator {

	/** @var AudienceListRepository */
	private $list_repository;

	public function __construct( AudienceListRepository $list_repository ) {
		$this->list_repository = $list_repository;
	}

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( SingleListSubscriber::class, $object );
		}

		try {
			$list        = $this->list_repository->find( $object->get_list_id() );
			$list_fields = [
				'id'   => $list->get_id(),
				'name' => $list->get_name(),
			];
		} catch ( \Exception $e ) {
			$list_fields = null;
		}

		return [
			'id'      => $object->get_id(),
			'list'    => $list_fields,
			'email'   => $object->get_email(),
			'active'  => $object->is_active(),
			'type'    => $object->get_type(),
			'created' => $object->get_created()->format( \DateTimeInterface::ATOM ),
			'updated' => $object->get_updated()->format( \DateTimeInterface::ATOM ),
			//'_links'  => [
			//	'list' => [ 'href' => RestRequestUtil::get_url( '/lists/' . $list->get_id() ) ],
			//],
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof SingleListSubscriber;
	}

	public function denormalize( array $payload ): object {
		if ( ! $this->supports_denormalization( $payload ) ) {
			throw InvalidArgumentException::invalid_payload( SingleListSubscriber::class );
		}

		$subscriber = new SingleListSubscriber();
		$subscriber->set_id( (int) $payload['id'] );
		$subscriber->set_list_id( (int) $payload['list_id'] );
		$subscriber->set_email( $payload['email'] );
		$subscriber->set_active( (int) $payload['active'] === 1 );
		$subscriber->set_type( (int) $payload['type'] === 1 );
		$subscriber->set_created( new \DateTimeImmutable( $payload['created'] ) );
		$subscriber->set_updated( new \DateTimeImmutable( $payload['updated'] ) );

		return $subscriber;
	}

	public function supports_denormalization( array $payload ): bool {
		$keys         = array_keys( $payload );
		$allowed_keys = [ 'id', 'list_id', 'email', 'active', 'type', 'created', 'updated' ];

		return count( array_intersect( $keys, $allowed_keys ) ) === count( $allowed_keys );
	}
}
