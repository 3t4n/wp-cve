<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator\ExecutionCreator;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeManager;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeSaver;
use WPDesk\ShopMagic\Workflow\Validator\FiltersValidator;
use WPDesk\ShopMagic\Workflow\Validator\FullyConfiguredValidator;
use WPDesk\ShopMagic\Workflow\Validator\NonExistingFilterFailure;
use WPDesk\ShopMagic\Workflow\Validator\WorkflowValidator;

final class WorkflowInitializer {

	/** @var Automation[] */
	private $automations = [];

	/** @var ExecutionCreator */
	private $execution_factory;

	/** @var WorkflowValidator */
	private $validator;

	/** @var OutcomeSaver */
	private $outcome_saver;

	/** @var AutomationRepository */
	private $repository;

	/** @var LoggerInterface */
	private $logger;

	/** @var OutcomeManager */
	private $outcome_manager;

	public function __construct(
		ExecutionCreator $execution_factory,
		OutcomeSaver $outcome_saver,
		OutcomeManager $outcome_manager,
		AutomationRepository $repository,
		LoggerInterface $logger
	) {
		$this->execution_factory = $execution_factory;
		$this->outcome_saver     = $outcome_saver;
		$this->repository        = $repository;
		$this->outcome_manager   = $outcome_manager;
		$this->logger            = $logger;
		$this->validator         = new WorkflowValidator();

		$this->validator
			->push( new FullyConfiguredValidator() )
			->push( new NonExistingFilterFailure( $this->logger ) )
			->push( new FiltersValidator() );
	}

	/** @return Automation[] */
	public function initialize_active_automations(): array {
		$automations = $this->repository->find_by(
			[
				'post_status' => Automation::STATUS_PUBLISH,
				'post_parent' => null,
			]
		);
		foreach ( $automations as $automation ) {
			$this->initialize_automation( $automation );
		}

		return $this->automations;
	}

	/**
	 * @internal This method is public only for backward compatibility.
	 */
	public function initialize_automation( Automation $automation ): void {
		$workflow = $this->create_runner( $automation );
		$workflow->initialize();
	}

	/**
	 * @param Automation $automation
	 *
	 * @return Runner
	 * @deprecated 3.0.9 Creating runners should not be public.
	 */
	public function create_runner( Automation $automation ): Runner {
		$runner = $this->create_workflow();
		$runner->set_automation( $automation );

		return $runner;
	}

	/**
	 * @deprecated 3.0.13 Append your validator to the existing one with add_validator() method.
	 */
	public function get_validator(): WorkflowValidator {
		return $this->validator;
	}

	/**
	 * @deprecated 3.0.9 Validators shouldn't be replaced, only appended.
	 * @codeCoverageIgnore
	 */
	public function set_validator( WorkflowValidator $validator ): void {
		$this->validator = $validator;
	}

	public function add_validator( WorkflowValidator $validator ): WorkflowValidator {
		return $this->validator->push( $validator );
	}

	private function create_workflow(): Workflow {
		return new Workflow(
			$this->validator,
			$this->execution_factory,
			$this->outcome_saver,
			$this->outcome_manager,
			$this->logger
		);
	}
}
