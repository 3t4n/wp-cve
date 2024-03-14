<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\ActionExecution;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Components\HookProvider\HookProvider;
use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Exception\AutomationNotFound;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\InvalidConfiguration;
use WPDesk\ShopMagic\Workflow\Queue\ActionSchedulerQueue;
use WPDesk\ShopMagic\Workflow\WorkflowInitializer;

/**
 * Runs specific action from ActionScheduler's queue hook.
 */
final class QueueActionRunner implements HookProvider {
	use HookTrait;

	/** @var WorkflowInitializer */
	private $automation_factory;

	/** @var ExecuteNow */
	private $executor;

	/** @var AutomationRepository */
	private $repository;

	/** @var LoggerInterface */
	private $logger;

	public function __construct(
		WorkflowInitializer $factory,
		AutomationRepository $repository,
		ExecuteNow $executor,
		LoggerInterface $logger
	) {
		$this->automation_factory = $factory;
		$this->repository         = $repository;
		$this->executor           = $executor;
		$this->logger             = $logger;
	}

	public function hooks(): void {
		$this->add_action(
			ActionSchedulerQueue::HOOK,
			[ $this, 'run_action' ],
			10,
			5
		);
	}

	/**
	 * @param array{id: int} $automation_serialized
	 * @param array          $event_serialized
	 * @param array          $action_serialized
	 * @param int            $action_index
	 * @param string|null    $unique_id
	 */
	private function run_action(
		array $automation_serialized,
		array $event_serialized,
		array $action_serialized,
		int $action_index,
		?string $unique_id = null
	): void {
		if ( $unique_id === null ) {
			$unique_id = uniqid( 'fallback_', true );
		}

		$run = function () use ( $automation_serialized, $event_serialized, $action_index, $unique_id ): void {
			try {
				$automation = $this->repository->find( (int) $automation_serialized['id'] );
			} catch ( AutomationNotFound $e ) {
				$this->logger->error( 'Automation #{id} could not be located in database.',
					[ 'id' => $automation_serialized['id'] ]
				);

				return;
			} catch ( InvalidConfiguration $e ) {
				$this->logger->error(
					'Automation #{id} failed to instantiate due to invalid configuration. Original error: {message}',
					[
						'id'      => $automation_serialized['id'],
						'message' => $e->getMessage(),
					]
				);

				return;
			}

			$event = $automation->get_event();
			$event->set_runner( $this->automation_factory->create_runner( $automation ) );
			$event->set_data_layer( new DataLayer( [ Automation::class => $automation ] ) );
			$event->set_from_json( $event_serialized );

			$this->executor->execute(
				$automation,
				$event,
				$automation->get_action( $action_index ),
				$action_index,
				$unique_id
			);
		};

		if ( \defined( 'DOING_CRON' ) && DOING_CRON ) {
			$run();
		} elseif ( did_action( 'wp_loaded' ) ) {
			$run();
		} else {
			add_action( 'wp_loaded', $run );
		}
	}

}
