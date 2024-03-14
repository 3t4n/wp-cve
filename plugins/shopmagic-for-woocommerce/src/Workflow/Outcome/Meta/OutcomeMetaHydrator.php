<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Outcome\Meta;

use DateTimeImmutable;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;

/**
 * Creates singular outcome log item.
 */
final class OutcomeMetaHydrator implements DAO\ObjectHydrator, DAO\ObjectDehydrator {

	/**
	 * @param OutcomeMeta|object $object
	 *
	 * @return array|bool[]|int[]|string[]
	 */
	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( OutcomeMeta::class, $object );
		}

		return [
			'id'           => $object->get_id(),
			'execution_id' => $object->get_execution_id(),
			'note'         => $object->get_note(),
			'note_context' => json_encode( $object->get_context() ),
			'created'      => $object->get_created_date()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof OutcomeMeta;
	}

	public function denormalize( array $payload ): object {
		$outcome_meta = new OutcomeMeta(
			(string) ( $payload['note'] ?? '' ),
			json_decode( $payload['note_context'] ?: '[]', true )
		);

		$outcome_meta->set_id( isset( $payload['id'] ) ? (int) $payload['id'] : null );
		$outcome_meta->set_created_date( new DateTimeImmutable( $payload['created'] ?: 'now' ) );
		$outcome_meta->set_execution_id( $payload['execution_id'] );

		return $outcome_meta;
	}

	public function supports_denormalization( array $data ): bool {
		$expected_keys = [ 'id', 'execution_id', 'note', 'context', 'created' ];

		return count( array_intersect( array_keys( $data ), $expected_keys ) ) === count( $expected_keys );
	}
}
