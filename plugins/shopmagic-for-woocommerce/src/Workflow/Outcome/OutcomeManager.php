<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Outcome;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager;
use WPDesk\ShopMagic\Database\DatabaseTable;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMetaManager;

/**
 * @extends ObjectManager<Outcome>
 */
class OutcomeManager extends \WPDesk\ShopMagic\Components\Database\Abstraction\ObjectManager {

	/** @var OutcomeMetaManager */
	private $meta_manager;

	public function __construct(
		ObjectRepository $repository,
		OutcomeMetaManager $meta_manager,
		DAO\ObjectHydrator $normalizer,
		?\wpdb $wpdb = null
	) {
		parent::__construct( $repository, $normalizer, $wpdb );
		$this->meta_manager = $meta_manager;
	}

	/**
	 * @param Outcome|object $item
	 *
	 * @return bool
	 */
	public function save( object $item ): bool {
		$success = parent::save( $item );

		if ( $item->get_note() ) {
			$this->meta_manager->save( $item->get_note() );
		}

		return $success;
	}

	/**
	 * @param Outcome|object $item
	 *
	 * @return int|void
	 */
	public function delete( object $item ) {
		$return = parent::delete( $item );

		if ( $item->get_note() ) {
			$this->meta_manager->delete( $item->get_note() );
		}

		return $return;
	}

	protected function get_columns(): array {
		return [
			'id',
			'execution_id',
			'automation_id',
			'automation_name',
			'action_index',
			'action_name',
			'customer_id',
			'guest_id',
			'customer_email',
			'success',
			'finished',
			'created',
			'updated',
		];
	}

	protected function get_name(): string {
		return DatabaseTable::automation_outcome();
	}
}
