<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Routing\HttpProblemException;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;
use WPDesk\ShopMagic\Workflow\Queue\ActionSchedulerQueue;
use WPDesk\ShopMagic\Workflow\Queue\Queue;

class QueueController {

	/** @var Queue */
	private $queue;

	public function __construct( Queue $queue ) {
		$this->queue = $queue;
	}

	public function index(
		\WP_REST_Request $request,
		AutomationRepository $repository
	): \WP_REST_Response {
		$search = $this->queue->search(
			[
				'group'    => ActionSchedulerQueue::GROUP,
				'status'   => \ActionScheduler_Store::STATUS_PENDING,
				'per_page' => $request->get_param( 'pageSize' ),
				'offset'   => ( $request->get_param( 'page' ) - 1 ) * $request->get_param( 'pageSize' ),
			]
		);

		$queue_collection = new ArrayCollection( $search );

		return new \WP_REST_Response(
			$queue_collection->map(
				function ( \ActionScheduler_Action $queue_action, int $index ) use ( $repository ) {
					[
						$automation_data,
						$event_params,
						, // Unused serialized action.
						$action_index,
						$execution_id,
					] = $queue_action->get_args();

					$schedule = $queue_action->get_schedule();
					if ( $schedule instanceof \ActionScheduler_Abstract_Schedule ) {
						$timezone     = wp_timezone();
						$set_timezone = $schedule->get_date()->setTimezone( $timezone );
						$schedule     = $set_timezone->format( \DateTimeInterface::ATOM );
					} else {
						$schedule = null;
					}

					$customer   = null;
					$automation = null;

					try {
						$automation = $repository->find( $automation_data['id'] );

						$event = $automation->get_event();
						$event->set_from_json( $event_params );
						$data = $event->get_provided_data();
						if ( $data->has( Customer::class ) ) {
							$customer = $data->get( Customer::class );
						}
					} catch ( \Throwable $e ) {
					}

					return [
						'id'           => $index,
						'execution_id' => $execution_id ?? $index,
						'automation'   => $automation ? $this->normalize_automation( $automation, $action_index ) : null,
						'customer'     => $customer ? $this->normalize_customer( $customer ) : null,
						'schedule'     => $schedule,
					];
				}
			)->to_array()
		);
	}

	/**
	 * @return array{id: string, guest: bool, email: string}
	 */
	private function normalize_customer( Customer $customer ): array {
		return [
			'id'    => $customer->get_id(),
			'guest' => $customer->is_guest(),
			'email' => $customer->get_email(),
		];
	}

	/**
	 * @return array{id: string, name: string, actions: string[]}
	 */
	private function normalize_automation( Automation $automation, int $action_index ): array {
		return [
			'id'      => $automation->get_id(),
			'name'    => $automation->get_name(),
			'actions' => [
				$action_index => $automation->has_action( $action_index ) ? $automation->get_action( $action_index )->get_name() : null,
			],
		];
	}

	public function count(): \WP_REST_Response {
		$count = \ActionScheduler::store()->query_actions(
			[
				'group'    => ActionSchedulerQueue::GROUP,
				'status'   => \ActionScheduler_Store::STATUS_PENDING,
				'per_page' => - 1,
			],
			'count'
		);

		return new \WP_REST_Response( $count );
	}

	public function cancel( int $id ): \WP_REST_Response {
		$action = \ActionScheduler::store()->fetch_action( $id );
		if ( $action->get_group() !== ActionSchedulerQueue::GROUP ) {
			throw new HttpProblemException(
				[
					'title' => esc_html__( 'Cannot cancel action outside ShopMagic group.', 'shopmagic-for-woocommerce' ),
				]
			);
		}

		$this->queue->cancel( $id );

		return new \WP_REST_Response( null, \WP_Http::NO_CONTENT );
	}
}
