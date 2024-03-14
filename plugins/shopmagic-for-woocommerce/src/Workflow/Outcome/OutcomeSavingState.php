<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Outcome;

use WPDesk\ShopMagic\Components\Database\Abstraction\ObjectRepository;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMeta;

class OutcomeSavingState implements OutcomeSaver {

	/** @var OutcomeManager */
	private $outcome_manager;

	/** @var ObjectRepository<Outcome> */
	private $outcome_repository;

	public function __construct( OutcomeManager $manager ) {
		$this->outcome_manager    = $manager;
		$this->outcome_repository = $manager->get_repository();
	}

	public function update_result(
		string $unique_id,
		bool $result,
		string $note = null,
		array $context = []
	): void {
		$outcome = $this->find_outcome( $unique_id );
		if ( $outcome === null ) {
			return;
		}
		$outcome->set_finished( true );
		$outcome->set_success( $result );
		$outcome->set_updated( new \DateTimeImmutable() );

		if ( $note ) {
			$note = new OutcomeMeta( $note, $context );
			$note->set_execution_id( $unique_id );

			$outcome->set_note( $note );
		}

		$this->outcome_manager->save( $outcome );
	}

	public function create_outcome(
		Automation $automation,
		DataLayer $data_layer,
		int $action_index
	) {
		$outcome = new Outcome();
		$outcome->set_execution_id( uniqid( 'execute_', true ) );
		$outcome->set_automation_id( $automation->get_id() );
		$outcome->set_automation_name( $automation->get_name() );
		if ( $automation->has_action( $action_index ) ) {
			$outcome->set_action_name( $automation->get_action( $action_index )->get_name() );
			$outcome->set_action_index( $action_index );
		} else {
			$outcome->set_action_name( 'Unknown' );
			$outcome->set_action_index( 0 );
		}
		if ( $data_layer->has( Customer::class ) ) {
			$outcome->set_customer( $data_layer->get( Customer::class ) );
		}
		$this->outcome_manager->save( $outcome );

		return $outcome->get_id();
	}

	public function find_outcome( string $unique_id ): ?Outcome {
		try {
			if ( is_numeric( $unique_id ) ) {
				$outcome = $this->outcome_repository->find( $unique_id );
			} else {
				$outcome = $this->outcome_repository->find_one_by( [ 'execution_id' => $unique_id ] );
			}
		} catch ( CannotProvideItemException $e ) {
			return null;
		}

		return $outcome;
	}

}
