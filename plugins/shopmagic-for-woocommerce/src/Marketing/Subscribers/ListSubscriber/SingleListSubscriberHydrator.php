<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber;

use DateTimeImmutable;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectHydrator;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceList;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\CommunicationListPersistence;

/**
 * @implements ObjectDehydrator<SingleListSubscriber>
 * @implements ObjectHydrator<SingleListSubscriber>
 */
final class SingleListSubscriberHydrator implements ObjectDehydrator, ObjectHydrator {

	public function denormalize( array $payload ): object {
		$object = new SingleListSubscriber();
		$object->set_id( isset( $payload['id'] ) ? (int) $payload['id'] : null );
		$object->set_list_id( $payload['list_id'] );
		$object->set_email( $payload['email'] );
		$object->set_type( $payload['type'] === '1' );
		$object->set_active( $payload['active'] === '1' );
		$object->set_created( new DateTimeImmutable( $payload['created'] ?? 'now' ) );
		$object->set_updated( new DateTimeImmutable( $payload['updated'] ?? 'now' ) );

		return $object;
	}

	public function create_for_email_and_list(
		string $email,
		int $list_id
	): SingleListSubscriber {
		$persistence = new CommunicationListPersistence( $list_id );
		$type = $persistence->get_fallback( CommunicationListPersistence::FIELD_TYPE_KEY, AudienceList::TYPE_OPTIN );

		return $this->denormalize( [
			'list_id' => $list_id,
			'email'   => $email,
			'active'  => true,
			'type'    => $type === 'opt_in',
		] );
	}

	public function supports_denormalization( array $data ): bool {
		return true;
	}

	/**
	 * @param SingleListSubscriber|object $object
	 *
	 * @return string[]
	 */
	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( SingleListSubscriber::class, $object );
		}

		return [
			'id'      => $object->get_id(),
			'list_id' => $object->get_list_id(),
			'email'   => $object->get_email(),
			'active'  => $object->is_active(),
			'type'    => $object->get_type(),
			'created' => $object->get_created()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
			'updated' => $object->get_updated()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof SingleListSubscriber;
	}
}
