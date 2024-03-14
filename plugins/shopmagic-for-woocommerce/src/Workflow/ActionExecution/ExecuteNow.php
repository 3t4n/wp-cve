<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\ActionExecution;

use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use WPDesk\ShopMagic\Exception\ActionDisabledAfterStatusRecheckException;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DeferredStateCheck\SupportsDeferredCheck;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeLogger;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeSaver;
use WPDesk\ShopMagic\Workflow\Placeholder\PlaceholderProcessor;

/**
 * Executes action NOW!. Do not use any queue od other async tools.
 */
final class ExecuteNow implements ExecutionStrategy, LoggerAwareInterface {
	use LoggerAwareTrait;

	/** @var bool */
	private $permit_exceptions;

	/** @var PlaceholderProcessor */
	private $processor;

	/** @var OutcomeSaver */
	private $outcome_saver;

	/** @var OutcomeLogger */
	private $outcome_logger;

	public function __construct(
		PlaceholderProcessor $processor,
		OutcomeSaver $outcome_saver,
		OutcomeLogger $outcome_logger,
		bool $permit_exceptions = false
	) {
		$this->permit_exceptions = $permit_exceptions;
		$this->processor         = $processor;
		$this->outcome_saver     = $outcome_saver;
		$this->outcome_logger    = $outcome_logger;
	}

	public function execute(
		Automation $automation,
		Event $event,
		Action $action,
		int $action_index,
		string $unique_id
	) {
		if ( ! $automation->has_action( $action_index ) ) {
			$this->logger->error(
				'Automation #{id} missing a valid action. Cancelling execution.',
				[ 'id' => $automation->get_id() ]
			);

			return;
		}

		$this->outcome_logger->set_execution_id( $unique_id );
		$action->setLogger( $this->outcome_logger );

		if ( $event instanceof SupportsDeferredCheck && ! $event->is_event_still_valid() ) {
			$this->logger->notice(
				'Event {event_name} associated with automation {automation_id} is no longer consistent with user settings.',
				[
					'event_name'    => $event->get_name(),
					'automation_id' => $automation->get_id(),
				]
			);

			throw new ActionDisabledAfterStatusRecheckException( esc_html__( 'Order linked to Event has changed status again and is no longer consistent with this event',
				'shopmagic-for-woocommerce' ) );
		}

		$this->processor->set_data_layer( $event->get_provided_data() );
		$action->set_placeholder_processor( $this->processor );

		try {
			do_action( 'shopmagic/core/action/before_execution', $action, $automation, $event );
			$result = $action->execute( $event->get_provided_data() );
			$this->outcome_saver->update_result( $unique_id, $result );
			do_action( 'shopmagic/core/action/successful_execution', $action, $automation, $event );
		} catch ( \Throwable $throwable ) {
			$this->outcome_saver->update_result(
				$unique_id,
				false,
				sprintf( 'error: %s', $throwable->getMessage() ),
				[
					'Error Code' => $throwable->getCode(),
					'Trace'      => $throwable->getTraceAsString(),
				]
			);
			do_action( 'shopmagic/core/action/failed_execution', $action, $automation, $event );
			if ( $this->permit_exceptions ) {
				throw $throwable;
			}
		} finally {
			do_action( 'shopmagic/core/action/after_execution', $action, $automation, $event );
		}
	}
}
