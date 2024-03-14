<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\MailTracking;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectHydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Database\DatabaseTable;

class TrackedEmailObjectManager extends \WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager {

	/** @var TrackedClickObjectManager */
	private $click_manager;

	public function __construct(
		ObjectRepository $repository,
		TrackedClickObjectManager $click_manager,
		ObjectHydrator $normalizer,
		?\wpdb $wpdb = null
	) {
		$this->click_manager = $click_manager;
		parent::__construct( $repository, $normalizer, $wpdb );
	}

	/**
	 * @param TrackedEmail $item
	 *
	 * @return bool
	 */
	public function save( object $item ): bool {
		$this->wpdb->query( 'START TRANSACTION' );
		foreach ( $item->get_clicks() as $click ) {
			$this->click_manager->save( $click );
		}

		$result = parent::save( $item );

		$this->wpdb->query( 'COMMIT' );
		return $result;
	}

	protected function get_columns(): array {
		return [
			'id',
			'message_id',
			'automation_id',
			'customer_id',
			'recipient_email',
			'dispatched_at',
			'opened_at',
			'clicked_at',
		];
	}

	protected function get_name(): string {
		return DatabaseTable::tracked_emails();
	}
}
