<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator\ExecutionCreator;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMeta;
use WPDesk\ShopMagic\Workflow\Outcome\Outcome;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeManager;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeSaver;
use WPDesk\ShopMagic\Workflow\Validator\WorkflowValidator;

class Workflow implements Runner {

	/** @var Automation|null */
	private $automation;

	/** @var ExecutionCreator */
	private $execution_creator;

	/** @var WorkflowValidator */
	private $validator;

	/** @var OutcomeSaver */
	private $outcome_saver;

	/** @var DataLayer */
	private $data_layer;

	/** @var LoggerInterface */
	private $logger;

	/** @var OutcomeManager */
	private $outcome_manager;

	public function __construct(
		WorkflowValidator $validator,
		ExecutionCreator $execution_creator,
		OutcomeSaver $outcome_saver,
		OutcomeManager $outcome_manager,
		LoggerInterface $logger
	) {
		$this->validator         = $validator;
		$this->execution_creator = $execution_creator;
		$this->outcome_saver     = $outcome_saver;
		$this->outcome_manager   = $outcome_manager;
		$this->logger            = $logger;
	}

	public function run( DataLayer $data_layer ): void {
		try {
			$this->data_layer = $data_layer;
			$this->do_run( $data_layer );
		} catch ( \Throwable $e ) {
			$this->logger->alert(
				'Automation execution prevented by critical error. Reason: {error}',
				[ 'error' => $e->getMessage() ]
			);

			$this->save_failure( $data_layer, $e );
		}
	}

	private function do_run( DataLayer $data_layer ): void {
		$this->setup( $data_layer );

		$this->logger->debug( 'Validating automation with set of validators.' );
		if ( $this->validator->valid( $data_layer ) ) {
			$this->logger->debug( 'Validation succeeded. Proceeding to dispatch actions.' );
			$this->execute_actions();
		} else {
			$this->logger->debug( 'Automation validation failed. Cleaning up and shutting down ShopMagic.' );
		}

		$this->cleanup();
	}

	private function setup( DataLayer $data_layer ): void {
		$this->validator->set_provided_data( $data_layer );

		do_action( 'shopmagic/core/automation/setup', $this );
	}

	private function execute_actions(): void {
		$this->logger->debug( sprintf( 'Executing all actions for automation %d.', $this->automation->get_id() ) );
		foreach ( array_values( $this->automation->get_actions() ) as $index => $action ) {
			if ( $this->should_execute_action( $action ) ) {
				$this->logger->debug( sprintf( 'Preparing action `%s` for dispatch.', get_class( $action ) ) );
				$this->delegate_for_execution( $action, $index );
				$this->logger->debug( sprintf( 'Action `%s` dispatched.', get_class( $action ) ) );
			} else {
				$this->logger->info( sprintf( 'Action `%s` skipped.', get_class( $action ) ) );
			}
		}
		$this->logger->debug(
			'Finished executing all actions for automation #{id}.',
			[ 'id' => $this->automation->get_id() ]
		);
	}

	private function should_execute_action( Action $action ): bool {
		return apply_filters( 'shopmagic/core/automation/should_execute_action',
			true,
			$this->automation,
			$this->automation->get_event(),
			$action
		);
	}

	private function delegate_for_execution( Action $action, int $index ): void {
		$executor  = $this->execution_creator->create_executor(
			$this->automation,
			$this->automation->get_event(),
			$action
		);
		$unique_id = $this->outcome_saver->create_outcome( $this->automation, $this->data_layer, $index );
		$this->logger->debug(
			sprintf(
				'Delegating action `%s` to executor `%s`.',
				get_class( $action ),
				get_class( $executor )
			)
		);
		$executor->execute(
			$this->automation,
			$this->automation->get_event(),
			$action,
			$index,
			(string) $unique_id
		);
	}

	private function cleanup(): void {
		do_action( 'shopmagic/core/automation/cleanup', $this );
	}

	public function initialize(): void {
		$event = $this->automation->get_event();

		$event->set_runner( $this );
		$event->set_data_layer( new DataLayer( [ Automation::class => $this->automation ] ) );
		$event->initialize();
	}

	public function set_automation( Automation $automation ): void {
		$this->automation = $automation;
	}

	/**
	 * FIXME: This method shouldn't really be here. Workflow doesn't have to know anything about
	 * an automation, as it fully operates on DataLayer provided by event. Workflow's
	 * reponsibility should be to first validate if passed data has the automation, then to
	 * execute, but there possibly may be cases when Automation is not required as such by
	 * Workflow, so it should be possible to execute Workflow without it.
	 *
	 * @return Automation
	 */
	public function get_automation(): Automation {
		return $this->automation;
	}

	/**
	 * We want to save any critical failure which occurred even before executing actions.
	 * At the moment this save method is kind of workaround as manager is not able to save
	 * outcome and note at once.
	 */
	public function save_failure( DataLayer $data_layer, \Throwable $e ): void {
		$outcome = new Outcome();
		$outcome->set_execution_id( uniqid( 'execute_', true ) );
		$outcome->set_automation_id( $this->automation->get_id() );
		$outcome->set_automation_name( $this->automation->get_name() );
		$outcome->set_action_name( 'Unknown' );
		$outcome->set_action_index( 0 );
		if ( $data_layer->has( Customer::class ) ) {
			$outcome->set_customer( $data_layer->get( Customer::class ) );
		}
		$outcome->set_finished( true );
		$outcome->set_success( false );
		$this->outcome_manager->save( $outcome );

		$note = new OutcomeMeta(
			sprintf( 'Automation execution prevented by critical error. Reason: %s', $e->getMessage() ),
			[
				'Error Code' => $e->getCode(),
				'Trace'      => $e->getTraceAsString(),
			]
		);
		$note->set_execution_id( (string) $outcome->get_id() );
		$outcome->set_note( $note );
		$this->outcome_manager->save( $outcome );
	}
}
