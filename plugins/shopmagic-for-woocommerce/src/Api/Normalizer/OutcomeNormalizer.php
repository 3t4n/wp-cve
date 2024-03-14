<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

use WPDesk\ShopMagic\Workflow\Outcome\Outcome;

/** @implements Normalizer<Outcome> */
class OutcomeNormalizer implements Normalizer {

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object(Outcome::class, $object);
		}

		if ( $object->get_note() !== null ) {
			$note = [
				'note'    => $object->get_note()->get_note(),
				'context' => $object->get_note()->get_context(),
			];
		}

		return [
			'id'         => $object->get_id(),
			'status'     => $object->get_status(),
			'automation' => [
				'id'   => $object->get_automation_id(),
				'name' => $object->get_automation_name(),
			],
			'customer'   => [
				'id'    => $object->get_customer()->get_id(),
				'email' => $object->get_customer()->get_email(),
				'guest' => $object->get_customer()->is_guest(),
			],
			'error'      => $note ?? null,
			'action'     => $object->get_action_name(),
			'updated'    => $object->get_updated()->format( \DateTimeInterface::ATOM ),
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof Outcome;
	}
}
