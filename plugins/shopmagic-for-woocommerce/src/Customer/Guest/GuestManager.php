<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Customer\Guest;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectHydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager;
use WPDesk\ShopMagic\Database\DatabaseTable;

/**
 * @extends ObjectManager<Guest>
 */
class GuestManager extends \WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager {

	/** @var GuestMetaManager */
	private $meta_manager;

	public function __construct(
		ObjectRepository $repository,
		GuestMetaManager $meta_manager,
		ObjectHydrator $normalizer,
		?\wpdb $wpdb = null
	) {
		parent::__construct( $repository, $normalizer, $wpdb );
		$this->meta_manager = $meta_manager;
	}

	/**
	 * @param object $item
	 * @phpstan-param Guest $item
	 *
	 * @return bool
	 */
	public function save( object $item ): bool {
		$success = parent::save( $item );

		$this->meta_manager->delete_by_where( [ 'guest_id' => $item->get_raw_id() ] );
		foreach ( $item->get_meta() as $m ) {
			$m->set_guest_id( $item->get_raw_id() );
			$this->meta_manager->save( $m );
		}

		return $success;
	}

	protected function get_columns(): array {
		return [
			'id',
			'email',
			'tracking_key',
			'created',
			'updated',
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function get_name(): string {
		return DatabaseTable::guest();
	}

	/**
	 * Guest has two types of ID: public and private, and here we need
	 * the private one, without safe prefix.
	 */
	protected function get_primary_key_from_object( object $item ): array {
		return [ 'id' => $item->get_raw_id() ];
	}
}
