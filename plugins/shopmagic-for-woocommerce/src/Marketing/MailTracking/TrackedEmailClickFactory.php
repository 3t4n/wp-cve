<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;

/**
 * @implements DAO\ObjectHydrator<TrackedEmailClick>
 */
class TrackedEmailClickFactory implements DAO\ObjectHydrator, DAO\ObjectDehydrator {

	/** @var TrackedEmailRepository */
	private $repository;

	public function __construct( TrackedEmailRepository $repository ) {
		$this->repository = $repository;
	}

	/** @param TrackedEmailClick|object $item */
	public function normalize( object $item ): array {
		return [
			'id'           => $item->get_id(),
			'message_id'   => $item->get_tracked_email()->get_message_id(),
			'original_uri' => $item->get_original_uri(),
			'clicked_at'   => $item->get_clicked_at()->format( WordPressFormatHelper::MYSQL_DATETIME_FORMAT ),
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof TrackedEmailClick;
	}

	public function denormalize( array $payload ): object {
		$tracked_email       = $this->repository->find_one_by( [ 'message_id' => $payload['message_id'] ] );
		$tracked_email_click = new TrackedEmailClick();
		$tracked_email_click->set_tracked_email( $tracked_email );
		$tracked_email_click->set_clicked_at( new \DateTime( $payload['clicked_at'] ) );
		$tracked_email_click->set_original_uri( $payload['original_uri'] );
		$tracked_email_click->set_last_inserted_id( (int) $payload['id'] );

		return $tracked_email_click;
	}

	public function supports_denormalization( array $data ): bool {
		return true;
	}
}
