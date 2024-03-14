<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Automation;

use WPDesk\ShopMagic\Workflow\WorkflowInitializer;

/**
 * Can create automation from given id.
 *
 * @deprecated 3.0.9 Use WorkflowInitializer
 * @codeCoverageIgnore
 */
final class AutomationInitializer {

	/** @var WorkflowInitializer */
	private $initializer;

	/** @var AutomationRepository */
	private $repository;

	public function __construct(
		WorkflowInitializer $initializer,
		AutomationRepository $repository
	) {
		$this->initializer = $initializer;
		$this->repository  = $repository;
	}

	public function initialize_automation( int $automation_id ): Automation {
		$automation = $this->repository->find( $automation_id );
		$this->initializer->initialize_automation( $automation );

		return $automation;
	}

}
