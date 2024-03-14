<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Outcome;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\Psr\Log\LoggerTrait;
use ShopMagicVendor\Psr\Log\LogLevel;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMeta;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMetaManager;

final class OutcomeLogger implements LoggerInterface {
	use LoggerTrait;

	/** @var LoggerInterface */
	private $logger;

	/** @var string */
	private $execution_id;

	/** @var OutcomeMetaManager */
	private $outcome_manager;

	public function __construct(
		LoggerInterface $logger,
		OutcomeMetaManager $outcome_manager
	) {
		$this->logger       = $logger;
		$this->outcome_manager = $outcome_manager;
	}

	public function set_execution_id( string $execution_id ): void {
		$this->execution_id = $execution_id;
	}

	public function log( $level, $message, array $context = [] ): void {
		if ( $this->should_log_to_database( $level ) ) {
			$note = new OutcomeMeta(
				sprintf( '%s: %s', $level, $message ),
				$context
			);
			$note->set_execution_id( $this->execution_id );
			$this->outcome_manager->save( $note );
		}

		$this->logger->log( $level, $message, $context );
	}

	private function should_log_to_database(string $level): bool {
		if ( empty( $this->execution_id ) ) {
			return false;
		}

		if ( \in_array( $level, [ LogLevel::DEBUG, LogLevel::INFO, LogLevel::NOTICE, LogLevel::WARNING  ], true ) ) {
			return false;
		}

		return true;
	}
}

