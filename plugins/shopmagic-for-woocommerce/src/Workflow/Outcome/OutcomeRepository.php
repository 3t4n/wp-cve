<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Outcome;

use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectDehydrator;
use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectRepository;
use WPDesk\ShopMagic\Database\DatabaseTable;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMetaRepository;

/**
 * @extends ObjectRepository<Outcome>
 */
class OutcomeRepository extends ObjectRepository {

	/** @var OutcomeMetaRepository */
	private $meta_repository;

	public function __construct(
		OutcomeMetaRepository $meta_repository,
		ObjectDehydrator $denormalizer,
		?\wpdb $wpdb = null
	) {
		$this->meta_repository = $meta_repository;
		parent::__construct( $denormalizer, $wpdb );
	}

	public function find_by( array $criteria, array $order = [], int $offset = 0, ?int $limit = null ): Collection {
		$outcomes = parent::find_by( $criteria, $order, $offset, $limit );

		return $outcomes->map( function ( Outcome $outcome ) {
			try {
				$outcome->set_note(
					$this->meta_repository->find_one_by(
						[ 'execution_id' => $outcome->get_id() ]
					)
				);
			} catch ( CannotProvideItemException $e ) {
				// This outcome doesn't have any notes associated.
			}

			return $outcome;
		} );
	}

	protected function get_name(): string {
		return DatabaseTable::automation_outcome();
	}

	public function count_automations_for_customer_with_time(
		int $automation_id,
		string $customer_id,
		int $in_days
	): int {
		$newer_than = WordPressFormatHelper::datetime_as_mysql( time() - $in_days * DAY_IN_SECONDS );
		$table      = DatabaseTable::automation_outcome();
		$statement  = $this->wpdb->prepare( "
		SELECT COUNT(*) FROM {$table} WHERE automation_id = %d AND customer_id = '%s' AND created >= '%s'
		",
			$automation_id,
			$customer_id,
			$newer_than
		);

		return (int) $this->wpdb->get_var( $statement );
	}
}
